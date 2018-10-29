var AdminManager = AdminManager || {};

(function(nsp){

	// namespace initial de l'espace d'administration
	nsp.container;
	nsp.fn = {};
	nsp.plugins = {};
	nsp.utilis = {
	  	merge:function(target={},source={}){
	   		for(let i in source){
	    		target[i] = source[i];
	   		}
	   		return target;
	  	}
	};

	nsp.initialize = function(){

		nsp.container.set('EntityManager',nsp.EntityManager);
		nsp.container.set('EventDispatcher',nsp.EventDispatcher);
		nsp.container.set('Scroller',nsp.Scroller);

		for(var i in nsp.fn){
			nsp.container.set(i,nsp.fn[i]);
		}
	}

	/**
	* Base Objet des events 
	* @return {Event}              
	*/
	nsp.Event = (function(){
		function Event(type,params){
	   		this.type = type;
	   		this.params = {};
	   		this.isPropagationStoped = false;
	   		nsp.utilis.merge(this.params,params);
		};

	  	/**
	  	* permet de stopper la propagation d'un événement
	  	*
	  	* @return {null}
	   	*/
	  	Event.prototype.stopPropagation = function(){
	   		this.isPropagationStoped = true;
	  	};

	  	return Event;
	 })();

	/**
	* EventDispatcherSubscriber est le soucripteur d'evenement
	* @return {EventDispatcherSubscriber}              
	*/
	nsp.EventDispatcherSubscriber = (function(){
		function EventDispatcherSubscriber(dispatcher){
	   		this.dispatcher = dispatcher;
	  	};

	  	/**
	   	* annulation de souscription au gestionnaire d'évenements
	   	* 
	   	* @return {null}
	   	*/
	  	EventDispatcherSubscriber.prototype.unsubscribe = function(){
	   		this.dispatcher.remove(this);
	  	};
	  
	  	return EventDispatcherSubscriber;
	})();

	/**
	* EventDispatcher est le gestionnaire d'evenement
	* @return {EventDispatcher}              
	*/
	nsp.EventDispatcher = (function(){
	  	function EventDispatcher(){
	   		this.$_data = new Map();
	  	};

	  	/**
	    * souscription au gestionnaire d'évenements
	    *
	    * @param  {Function} cbk callback à appeler à chaque nouvel évenement
	    * @return {null}
	   	*/
	  	EventDispatcher.prototype.subscribe = function(cbk){
	   		var subscriber = new nsp.EventDispatcherSubscriber(this);
	   		this.$_data.set(subscriber,cbk);
	   		return subscriber;
	  	};
	  
	  	/**
	    * supprime un souscripteur d'évenement
	    *
	    * @param  {EventDispatcherSubscriber} subscriber
	    * @return {null}         
	   	*/
	  	EventDispatcher.prototype.remove = function(subscriber){
	   		this.$_data.delete(subscriber);
	  	};

	  	/**
	    * emetteur d'évenements
	    *
	    * @param  {Event} event
	    * @return {null}         
	   	*/
	  	EventDispatcher.prototype.emit = function(event){
	   		for(let [i,cbk] of this.$_data){
	    		cbk.call(null,event);
	    		if(event.isPropagationStoped) break;
	   		}
	  	};
	  	return EventDispatcher;
	})();


	/**
	* EntityManager 
	* @return {EntityManager}              
	*/
	nsp.EntityManager = (function(){

		function EntityManager(params){
			nsp.EventDispatcher.call(this);

	   		this.params = {
	   			endpoint:null
	   		};
	   		this.xhr;
	   		nsp.utilis.merge(this.params,params);
		};

		Object.assign(EntityManager.prototype,nsp.EventDispatcher.prototype);

	  	/**
	  	* permet d'initialiser les repositories
	  	*
	  	* @return {null}
	   	*/
	  	EntityManager.prototype.init = function(params){
	  		nsp.utilis.merge(this.params,params);
	  	};

	  
	  	EntityManager.prototype.persist = function(params){

	  	};

	  	EntityManager.prototype.remove = function(params){

	  	};

	  	EntityManager.prototype.request = function(options){
	  		options = options || {};

	  		var params = {
	  			url: this.endpoint,
	  			dataType:'json',
	  			method:'POST',
	  			success:function(data){
					resolve(data);
				},
				error:function(xhr,textStatus,errorThrown){
					reject(textStatus);
				}
	  		};

	  		for(var i in options){
	  			if(typeof params[i] == "function") continue;
	  			params[i] = options[i];
	  		}

	  		return $.ajax(params);
		};

	  	return EntityManager;
	})();


	/**
	* Base Objet les repositories 
	* @return {AbstractRepository}              
	*/
	nsp.AbstractRepository = (function(){

		function AbstractRepository(type,params){
			nsp.EventDispatcher.call(this);

	   		this.params = {
	   			endpoint:null
	   		};
	   		this.current;
	   		this.xhr;
	   		nsp.utilis.merge(this.params,params);
		};

		Object.assign(AbstractRepository.prototype,nsp.EventDispatcher.prototype);

	  	/**
	  	* permet d'initialiser les repositories
	  	*
	  	* @return {null}
	   	*/
	  	AbstractRepository.prototype.init = function(params){
	  		nsp.utilis.merge(this.params,params);
	  	};


	  	AbstractRepository.prototype.setCurrent = function(user){
	  		this.current = user;
	  	};

	  	/**	
	  	* les methodes abstraites
	  	*/
	  	AbstractRepository.prototype.find = function(id){};
	  	AbstractRepository.prototype.findAll = function(){};
	  	AbstractRepository.prototype.findBy = function(params,orderBy,limit,offset){};
	  	AbstractRepository.prototype.findOneBy = function(params){};

	  	/**
	  	* les methodes public
	  	*/
	  	AbstractRepository.prototype.request = function(options){
	  		options = options || {};

	  		var params = {
	  			url: this.endpoint,
	  			dataType:'json',
	  			method:'GET',
	  			
	  		};

	  		for(var i in options){
	  			params[i] = options[i];
	  		}

	  		return $.ajax(params);
		};

	  	return AbstractRepository;
	})();

	nsp.Repository = (function(){

		function Repository(params){
			nsp.AbstractRepository.call(this,params);
		};

		Object.assign(Repository.prototype, nsp.AbstractRepository.prototype);

		Repository.prototype.find = function(id){

			return this.findBy({
				data:{
					id:id
				}
			});
		};
	  	Repository.prototype.findAll = function(){
	  		return this.findBy();
	  	};
	  	Repository.prototype.findBy = function(params = {},orderBy = {},limit,offset){
	  		
	  		if(!params.hasOwnProperty('data')){
	  			params.data = {};
	  		}

	  		if(limit){
	  			params["data"].limit = limit;
	  			params["data"].offset = offset;
	  		}

	  		return new Promise((resolve,reject)=>{
	  			this.request(params)
		  		.done(data=>{
		  			resolve(data);
		  		})
		  		.fail(msg=>{
		  			console.log(msg)
		  			reject(msg);
		  		});
	  		});
	  	};
	  	Repository.prototype.findOneBy = function(params){
	  		return this.findBy(params,null,1,0);
	  	};

		return Repository;
	})();


	/**	
	* Base class pour les vues
	*/
	nsp.View = (function(){

		function View(){
			nsp.EventDispatcher.call(this);
			this._data = new Map();
			this.params = {
				$tpl:{}
			};
		};

		Object.assign(View.prototype,nsp.EventDispatcher.prototype);

		View.prototype.vars = function(params){
	  		nsp.utilis.merge(this.params,params);
	  		return this;
	  	};

	  	/**
	  	* methode abstraite
	  	* demarre la logic de la vue
	  	*/
	  	View.prototype.controller = function(){};

		View.prototype.render = function(view,model,options){
			return Mustache.render(view,model,options);
		}
		return View;
	})();

	/**	
	* le container de service
	*/
	nsp.Container = (function(){

		function Container(){
			nsp.EventDispatcher.call(this);
			this._data = new Map();
		};

		Object.assign(Container.prototype,nsp.EventDispatcher.prototype);

		Container.prototype.has = function(key){
			return this._data.has(key);
		}

		Container.prototype.set = function(name,_constructor){
			this._data.set(name,new _constructor());
		}

		Container.prototype.get = function(value){
			return this._data.get(value);
		}

		return Container;
	})();


	/**
	* evenement de filereader
	*/
	nsp.FileReaderEvent = (function(){
		function FileReaderEvent(params){
			nsp.Event.call(this,'upload',params);
		};
		Object.assign(FileReaderEvent.prototype, nsp.Event.prototype);
		return FileReaderEvent;
	})();

	/**
	* evenement de traduction
	*/
	nsp.TranslationEvent = (function(){
		function TranslationEvent(params){
			nsp.Event.call(this,'translate',params);
		};
		Object.assign(TranslationEvent.prototype, nsp.Event.prototype);
		return TranslationEvent;
	})();

	/**
	* evenement de upload
	*/
	nsp.UploadEvent = (function(){
		function UploadEvent(params){
			nsp.Event.call(this,'upload',params);
		};
		Object.assign(UploadEvent.prototype, nsp.Event.prototype);
		return UploadEvent;
	})();

	/**
	* evenement de scrolling dynamique
	*/
	nsp.ScrollerEvent = (function(){
		function ScrollerEvent(params){
			nsp.Event.call(this,'scroll',params);
		};
		Object.assign(ScrollerEvent.prototype, nsp.Event.prototype);
		return ScrollerEvent;
	})();

	/**
	* evenement de scrolling dynamique
	*/
	nsp.InfiniteScrollEvent = (function(){
		function InfiniteScrollEvent(params){
			nsp.Event.call(this,'infinite-scroll',params);
		};
		Object.assign(InfiniteScrollEvent.prototype, nsp.Event.prototype);
		return InfiniteScrollEvent;
	})();

	/**	
	* l'infinite scrolling
	*/
	nsp.Scroller = (function(){

		function Scroller(){
			nsp.EventDispatcher.call(this);
		};

		Object.assign(Scroller.prototype,nsp.EventDispatcher.prototype);

		Scroller.prototype.forWindow = function(key){

			var doc = $(document);
			var win = $(window);
			var oldPos = win.scrollTop();

			win.on({
				scroll:e=>{
					var tHeight = doc.height() - win.height();
					var scrollTop = win.scrollTop();
					var pos = tHeight-scrollTop;
					var percent = (pos*100)/tHeight;
					var dir = scrollTop > oldPos ? "ttb":"btt";
					oldPos = scrollTop;

					var ev = {
						scrollTop:scrollTop,
						pos:pos,
						percent:percent,
						dir:dir
					};
					this.emit(new nsp.ScrollerEvent(nsp.utilis.merge({state:"scrolling"},ev)));

					if (tHeight == scrollTop) {
						this.emit(new nsp.ScrollerEvent(nsp.utilis.merge({state:"end"},ev)));
					}
					else if (scrollTop == 0) {
						this.emit(new nsp.ScrollerEvent(nsp.utilis.merge({state:"start"},ev)));
					}

				}
			});
		}

		return Scroller;
	})();

	(function(){
		nsp.container = new nsp.Container();
	})();

})(AdminManager);


$(document).ready(function($){
	AdminManager.initialize();
});
var AdminManager = AdminManager || {};

(function(nsp){

	/**
	* evenement lorsqu'on lance la mise a jour
	*/
	nsp.ProducerUpdatingEvent = (function(){
		function ProducerUpdatingEvent(params){
			nsp.Event.call(this,'update',params);
		};
		Object.assign(ProducerUpdatingEvent.prototype, nsp.Event.prototype);
		return ProducerUpdatingEvent;
	})();

	/**
	* evenement lorsqu'on lance la suppression
	*/
	nsp.ProducerDeletingEvent = (function(){
		function ProducerDeletingEvent(params){
			nsp.Event.call(this,'delete',params);
		};
		Object.assign(ProducerDeletingEvent.prototype, nsp.Event.prototype);
		return ProducerDeletingEvent;
	})();

	/**
	* evenement lorsqu'on lance l'insertion d'un pays
	*/
	nsp.CountryInsertEvent = (function(){
		function CountryInsertEvent(params){
			nsp.Event.call(this,'insert',params);
		};
		Object.assign(CountryInsertEvent.prototype, nsp.Event.prototype);
		return CountryInsertEvent;
	})();

	/**
	* evenement lorsqu'on lance la suppression d'un pays
	*/
	nsp.CountryDeleteEvent = (function(){
		function CountryDeleteEvent(params){
			nsp.Event.call(this,'delete',params);
		};
		Object.assign(CountryDeleteEvent.prototype, nsp.Event.prototype);
		return CountryDeleteEvent;
	})();

	nsp.fn.ProducerRepository = (function(){

		function ProducerRepository(params){
			nsp.Repository.call(this,params);
		};

		Object.assign(ProducerRepository.prototype, nsp.Repository.prototype);

		ProducerRepository.prototype.customRequest = function(event){

			return new Promise((resolve,reject)=>{
	  			this.request({
	  				url:`/admin/producers/${event.type}/${this.current.id}`,
	  				method:"POST",
	  				data:event.params.model
		  		})
		  		.done(data=>{
		  			resolve(data);
		  		})
		  		.fail(msg=>{
		  			reject(msg);
		  		});
	  		});
		};

		ProducerRepository.prototype.customCountryRequest = function(event){

			return new Promise((resolve,reject)=>{
	  			this.request({
	  				url:`/admin/producers/${this.current.id}/country/${event.type}`,
	  				method:"POST",
	  				data:{country_id:event.params.model.id}
		  		})
		  		.done(data=>{
		  			resolve(data);
		  		})
		  		.fail(msg=>{
		  			reject(msg);
		  		});
	  		});
		};

		ProducerRepository.prototype.uploadImage = function(event){
			var file = event.params.file;
			var formData = new FormData();
			formData.append('image',file);

			return new Promise((resolve,reject)=>{

	  			this.request({
	  				enctype: 'multipart/form-data',
	  				url:`/admin/producers/${this.current.id}/image/${event.type}`,
	  				method:"POST",
	  				data:formData,
	  				processData: false,
	  				cache: false,
            		contentType: false,
		  		})
		  		.done(data=>{
		  			resolve(data);
		  		})
		  		.fail(msg=>{
		  			reject(msg);
		  		});
	  		});
		};

		return ProducerRepository;
	})();


	nsp.fn.ProducerView = (function(){
		function ProducerView(params){
			nsp.View.call(this,params);

			this.vars({
				selectedDataView:$("#current-widget-data"),
				rightSection:$("#right-section"),
				model:{
					name:$("#current-widget-data #name"),
					email:$("#current-widget-data #email"),
				},
				$tpl:{
					errors:`
						<ul>
							{{#errors}}
								<li>{{ . }}</li>
							{{/errors}}
						</ul>
					`,
				}
			});
		};

		Object.assign(ProducerView.prototype, nsp.View.prototype);

		ProducerView.prototype.controller = function(){
			
			$('#myModal').on('shown.bs.modal', function () {
  				$('#myModal button').removeAttr('disabled');
			});


			$('#myModal button[type=submit]').on({
				click:e=>{
					e.preventDefault();
					$('#myModal button').attr('disabled','disabled');

					this.params.selectedDataView.addClass('updating');
					var data = $(e.target).serialize();

					this.emit(new nsp.ProducerDeletingEvent({
						state:'start',
						model:data
					}));
				}
			});

			// gestion des ajouts pays
			$('body').on("click",".modal-form .has-collection .collection-add",
				e=>{
				e.preventDefault();
				var parent = $(e.target).parent();
				var tpl = parent.find('[data-prototype]').data('prototype');
				var index = parent.children(" .input-group ").length;

				tpl = tpl.replace(/__name__/g,index);

				var a = $('<div class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-trash"></i></button></div>');
				var li = $('<div class="input-group input-group-sm">').append(a).append(tpl);
				li.find('label').remove();

				li.insertBefore(parent.find('a'));
				a.find('button').on({
					click:e=>{
						li.remove();
					}
				})
			});


			// gestion des upload images
			$('body').on("click",".modal-form .user-image",e=>{
				e.preventDefault();
				var modal = $(e.target).parents(".modal-form");
				modal.find("input[type=file]").trigger('click');
			});

			$('body').on("change",".modal-form input[type=file]",
				e=>{
				e.preventDefault();

				var files = e.target.files;
				var modal = $(e.target).parents(".modal-form");
				var file = files[0];

		        var ext = file.name.split('.');
				ext = ext.slice(-1);
				ext = ext[0];
				ext = ext.toLowerCase();
				if (["jpg","jpeg","png"].indexOf(ext.toLowerCase()) == -1) return;

			    var reader = new FileReader();

			    reader.addEventListener('load', ()=> {
			    	modal.find('img:first').attr('src',reader.result);
			    });
			    reader.readAsDataURL(file);
			});

			// gestion de suppression 
			$('body').on("click","#modal-update button.delete",e=>{
				e.preventDefault();
				var modal = $(e.target).parents("#modal-update");
				var modal_confirm = $('#myModal');

				var fn1 = function () {
	  				modal_confirm.modal('show');
	  				modal.off('hidden.bs.modal',fn1);

				};

				var fn2 = function () {
					modal.modal('show');
					modal_confirm.off('hidden.bs.modal',fn2);
				};

				modal.on('hidden.bs.modal',fn1);
				modal_confirm.on('hidden.bs.modal',fn2);
				modal.modal('hide');

			});

			$("body").on("click","#modal-update .has-collection .collection-badge .old-value a",e=>{
    			e.preventDefault();

    			var obj = $(e.currentTarget);
    			var parentModal = obj.parents("#modal-update");
    			var collectionModal = $("#modal-update-gallery");
    			
    			var oldValue = obj.parent();
    			var collectionBadge = oldValue.parent();
    			var route = collectionBadge.data('route');
    			var dataId = oldValue.data('id');

    			collectionModal.find('.collection-alert-msg').html(collectionBadge.data('alert'));

    			var removeCbk = function(){
    				oldValue.remove();
    				if(!collectionBadge.find('.old-value').length){
    					collectionBadge.remove();
    				}
    			};

    			if(oldValue.length && dataId){
    				collectionModal.attr('data-id',dataId);

    				var fn = (e)=>{

    					var shownFn = (ee)=>{
    						var submitBtn = collectionModal.find('button[type=submit]');
    						submitBtn.on({
    							click:ee=>{
    								ee.preventDefault();

    								removeCbk();
    								submitBtn.off();

    								var evt = new nsp.CountryDeleteEvent({
										state:'start',
										model:{
											id:dataId
										},
									});

    								this.emit(evt);

    								collectionModal.modal("hide");
    							}
    						});
    					};

    					collectionModal.on('shown.bs.modal', shownFn);
    					collectionModal.modal("show");

    					parentModal.off('hidden.bs.modal', fn);

    					var fn2 = (ee)=>{
    						collectionModal.off('hidden.bs.modal', fn2);
    						collectionModal.off('shown.bs.modal', shownFn);
    						parentModal.modal("show");
    						collectionModal.attr('data-id',null);
    					};

    					collectionModal.on('hidden.bs.modal', fn2);
    				};

    				parentModal.on('hidden.bs.modal', fn);
    				parentModal.modal("hide");
    			}
    			else{
    				removeCbk();
    			}
    		});

    		$("body").on("dragenter",".modal-form",e=>{
				var obj = $(e.currentTarget);
				obj.addClass('dragenter');
				e.preventDefault();
			});

			$("body").on("dragover",".modal-form",e=>{
				var obj = $(e.currentTarget);
				obj.addClass('dragenter');
				e.preventDefault();
			});

			$("body").on("dragleave",".modal-form",e=>{
				var obj = $(e.currentTarget);
				obj.removeClass('dragenter');
				e.preventDefault();
			});

			$("body").on("dragend",".modal-form",e=>{
				var obj = $(e.currentTarget);
				obj.removeClass('dragenter');
				e.preventDefault();
			});

			$("body").on("drop",".modal-form",e=>{
				e.preventDefault();
				var obj = $(e.currentTarget);
				obj.removeClass('dragenter');

				var files = e.originalEvent.dataTransfer.files;
				var file = files[0];

		        var ext = file.name.split('.');
				ext = ext.slice(-1);
				ext = ext[0];
				ext = ext.toLowerCase();
				if (["jpg","jpeg","png"].indexOf(ext.toLowerCase()) == -1) return;

				var input = obj.find('input[type=file]');

			    var reader = new FileReader();

			    reader.addEventListener('load', ()=> {
			    	obj.find('img:first').attr('src',reader.result);
					input.get()[0].files = files;
			    });

			    reader.readAsDataURL(file);
			});		

			// on ecoute les evenements
			this.subscribe(event=>{
				if(event instanceof nsp.ProducerUpdatingEvent){
					if(~['end','fails'].indexOf(event.params.state)){
						this.params.selectedDataView.removeClass('updating');

						if(event.params.state == 'end'){
							var data = event.params.data;

							var alertShow = ()=>{
								if(data.hasOwnProperty('message')){
									$('#modal-info .modal-body h4').html(data.message);
									$('#modal-info').modal('show');
								}
								else if(data.hasOwnProperty('errors')){
									var tpl = this.render(this.params.$tpl.errors,data);
									$('#modal-info .modal-body h4').html(tpl);
									$('#modal-info').modal('show');
								}
							};

							if(data.status){
								var rol_sel = `#data-container .data-item[data-id=${this.params.selectedDataView.data('id')}]`;
								var rowItem = $(rol_sel);

								rowItem.find('.data-item-name').html(data.data.name);
							}
							
							alertShow();
						}
					}
				}
				else if(event instanceof nsp.ProducerDeletingEvent){
					if(~['end','fails'].indexOf(event.params.state)){
						this.params.selectedDataView.removeClass('updating');

						if(event.params.state == 'end'){
							var data = event.params.data;

							var alertShow = ()=>{
								if(data.hasOwnProperty('message')){
									$('#modal-info .modal-body h4').html(data.message);
									$('#modal-info').modal('show');
								}
								else if(data.hasOwnProperty('errors')){
									var tpl = this.render(this.params.$tpl.errors,data);
									$('#modal-info .modal-body h4').html(tpl);
									$('#modal-info').modal('show');
								}
								$('#myModal').off('hidden.bs.modal');
							};

							if(data.status){
								var rol_sel = `#data-container .data-item[data-id=${this.params.selectedDataView.data('id')}]`;
								var rowItem = $(rol_sel);
								rowItem.remove();

								
								$('#myModal').on('hidden.bs.modal', function () {
					  				alertShow();
								});

								$('#myModal').modal('hide');
								
								this.params.rightSection.removeClass('data-active');

								window.location.reload();
							}
							else{
								alertShow();
							}
						}
					}
				}
				else if(event instanceof nsp.CountryInsertEvent || event instanceof nsp.CountryDeleteEvent || event instanceof nsp.UploadEvent){
					if(~['end','fails'].indexOf(event.params.state)){
						this.params.selectedDataView.removeClass('updating');

						if(event.params.state == 'end'){
							var data = event.params.data;

							if(data && data.status){
								if(event instanceof nsp.UploadEvent){
									var src = $("#data-container .data-item[data-id="+data.data.id+"] .data-item-image img");

									src.attr("src","/upload/public/"+data.data.image);

								}
							}

							

							var alertShow = ()=>{
								if(data.hasOwnProperty('message')){
									$('#modal-info .modal-body h4').html(data.message);
									$('#modal-info').modal('show');
								}
								else if(data.hasOwnProperty('errors')){
									var tpl = this.render(this.params.$tpl.errors,data);
									$('#modal-info .modal-body h4').html(tpl);
									$('#modal-info').modal('show');
								}
								$('#myModal').off('hidden.bs.modal');
							};

							alertShow();
						}
					}
				}
				else if(event instanceof nsp.InfiniteScrollEvent){

					if(event.params.state == "start"){
						$(document.body).addClass("infinite-scroll-active");
					}
					else if(~['end','fails'].indexOf(event.params.state)){
						$(document.body).removeClass("infinite-scroll-active");

						if(event.params.state == 'end'){
							var data = event.params.data;
							var model = {data:data};

							if(data){
								$("#data-container table:first tbody:first").append(data);
							}
						}
					}
				}
			});


				

			var scroller = nsp.container.get('Scroller');
			scroller.subscribe(event=>{
				if(event instanceof nsp.ScrollerEvent && event.params.percent <= 20 && event.params.dir == "ttb"){
					if(!$(document.body).hasClass("infinite-scroll-active")){

						var limit = 20;
						var offset = $("#data-container .data-item").length;
						nsp.utilis.merge(event.params,{limit:limit,offset:offset});

						this.emit(new nsp.InfiniteScrollEvent({
							state:'start',
							data:event.params
						}));
					}
				}

			});

			scroller.forWindow();

			return this;
		}

		ProducerView.prototype.renderSelectedData = function(view){
			var ref = $("#modal-update-area").html(view);
			var modal = ref.find('#modal-update');
			modal.modal({
				backdrop:'static',
				show:true
			});
		}

		return ProducerView;
	})();

})(AdminManager);
$(document).ready(function($){
	var nsp = AdminManager;
	var repository = AdminManager.container.get('ProducerRepository');
	var view = AdminManager.container.get('ProducerView');
	view.controller();

	var rightSection = $("#right-section");

	view.subscribe(event=>{

		if((event instanceof nsp.ProducerUpdatingEvent) || (event instanceof nsp.ProducerDeletingEvent)) {
			if(event.params.state != "start") return;
			
			repository.customRequest(event)
			.then(data=>{

				if(event instanceof nsp.ProducerUpdatingEvent){
					view.emit(new nsp.ProducerUpdatingEvent({state:'end',data:data}));
				}
				else if(event instanceof nsp.ProducerDeletingEvent){
					view.emit(new nsp.ProducerDeletingEvent({state:'end',data:data}));
				}
			},msg=>{
				if(event instanceof nsp.ProducerUpdatingEvent){
					view.emit(new nsp.ProducerUpdatingEvent({state:'fails'}));
				}
				else if(event instanceof nsp.ProducerDeletingEvent){
					view.emit(new nsp.ProducerDeletingEvent({state:'fails'}));
				}
			});
		}
		else if((event instanceof nsp.CountryInsertEvent) || (event instanceof nsp.CountryDeleteEvent)) {
			if(event.params.state != "start") return;

			repository.customCountryRequest(event)
			.then(data=>{

				if(event instanceof nsp.CountryInsertEvent){
					view.emit(new nsp.CountryInsertEvent({state:'end',data:data}));
				}
				else if(event instanceof nsp.CountryDeleteEvent){
					view.emit(new nsp.CountryDeleteEvent({state:'end',data:data}));
				}
			},msg=>{

				if(event instanceof nsp.CountryInsertEvent){
					view.emit(new nsp.CountryInsertEvent({state:'fails'}));
				}
				else if(event instanceof nsp.CountryDeleteEvent){
					view.emit(new nsp.CountryDeleteEvent({state:'fails'}));
				}
			});
		}
		else if(event instanceof nsp.UploadEvent){
			if(event.params.state != "start") return;

			repository.uploadImage(event)
	    	.then(data=>{

	    		view.emit(new nsp.UploadEvent({
					state:'end',
					data:data
				}));

	    	},msg=>{
	    		view.emit(new nsp.UploadEvent({
					state:'fails',
				}));
	    	});
		}
		else if(event instanceof nsp.InfiniteScrollEvent){
			if(event.params.state != "start") return;

			var limit = event.params.data.limit;
			var offset = event.params.data.offset;
			repository.findBy({
				headers:{accept:"text/html"},
				dataType:'text'
			},{},limit,offset)
			.then(data=>{

				view.emit(new nsp.InfiniteScrollEvent({
					state:'end',
					data:data
				}));

			},msg=>{
				view.emit(new nsp.InfiniteScrollEvent({
					state:'fails',
				}));
			});
		}
	});

	var modal = $("#modal-loading");

	$("#data-container").on("click",".data-item .edit",function(e){

		e.preventDefault();

		var self = $(this);
		self.addClass('disabled');
		var id = self.parents("tr").data('id');

		modal.modal({backdrop:'static',show:true});

		repository.find(id)
		.then(data=>{
			self.removeClass('disabled');
			repository.setCurrent(data.model);
			var fn = (e)=> {
				$('#modal-loading').off('hidden.bs.modal',fn);
  				view.renderSelectedData(data.view);
			};

			$('#modal-loading').on('hidden.bs.modal',fn);

			modal.modal('hide');
			
		},msg=>{
			modal.modal('hide');
			self.removeClass('disabled');
		})
	});
});