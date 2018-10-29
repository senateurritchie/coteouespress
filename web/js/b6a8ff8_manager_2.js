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