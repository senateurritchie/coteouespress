var AdminManager = AdminManager || {};

(function(nsp){

	/**
	* evenement lorsqu'on lance la mise a jour
	*/
	nsp.MovieUpdatingEvent = (function(){
		function MovieUpdatingEvent(params){
			nsp.Event.call(this,'update',params);
		};
		Object.assign(MovieUpdatingEvent.prototype, nsp.Event.prototype);
		return MovieUpdatingEvent;
	})();

	/**
	* evenement lorsqu'on lance la suppression
	*/
	nsp.MovieDeletingEvent = (function(){
		function MovieDeletingEvent(params){
			nsp.Event.call(this,'delete',params);
		};
		Object.assign(MovieDeletingEvent.prototype, nsp.Event.prototype);
		return MovieDeletingEvent;
	})();
	

	nsp.fn.MovieRepository = (function(){

		function MovieRepository(params){
			nsp.Repository.call(this,params);
		};

		Object.assign(MovieRepository.prototype, nsp.Repository.prototype);

		MovieRepository.prototype.customRequest = function(event){

			return new Promise((resolve,reject)=>{
	  			this.request({
	  				url:`/admin/movies/${event.type}/${this.current.id}`,
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


		MovieRepository.prototype.uploadImage = function(event){
			var file = event.params.file;
			var _target = event.params._target;
			var formData = new FormData();
			formData.append('image',file);
			formData.append('_target',_target);


			return new Promise((resolve,reject)=>{

	  			this.request({
	  				enctype: 'multipart/form-data',
	  				url:`/admin/movies/${this.current.id}/image/${event.type}`,
	  				method:"POST",
	  				data:formData,
	  				processData: false,
	  				cache: false,
            		contentType: false,
            		xhr: () =>{
			            var myXhr = $.ajaxSettings.xhr();
		                if(myXhr.upload){
		                    myXhr.upload.addEventListener('progress',e=>{
				            	if (e.lengthComputable) {
								    var percent = (e.loaded / e.total)*100;
								    
								    this.emit(new nsp.UploadEvent({
										state:'progress',
										total:e.total,
										loaded:e.loaded,
										percent:percent,
										file:file
									}));

								  } else {
								    // Impossible de calculer la progression puisque la taille totale est inconnue
								  }
				            })
		                }
		                return myXhr;
			        },
            		
		  		})
		  		.done(data=>{
		  			resolve(data);
		  		})
		  		.fail(msg=>{
		  			reject(msg);
		  		});
	  		});
		};

		return MovieRepository;
	})();


	nsp.fn.MovieView = (function(){
		function MovieView(params){
			nsp.View.call(this,params);

			this.vars({
				selectedDataView:$("#current-widget-data"),
				rightSection:$("#right-section"),
				renderAs:1,
				copyDefaultImage:null,
				copyDefaultImageUpdate:null,
				model:null,
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

		Object.assign(MovieView.prototype, nsp.View.prototype);


		MovieView.prototype.controller = function(){

			/* 09. VENOBOX JS */
            $('.venobox').venobox({
                numeratio: true,
                titleattr: 'data-title',
                titlePosition: 'top',
                spinner: 'wandering-cubes',
                spinColor: '#007bff',
                autoplay:true
            });


			$('#myModal').on('shown.bs.modal', function () {
  				$('#myModal button').removeAttr('disabled');
			});

			$('#myModal button[type=submit]').on({
				click:e=>{
					e.preventDefault();
					$('#myModal button').attr('disabled','disabled');
					$(".modal.form-views").addClass('updating');

					var data = $(e.target).serialize();
					this.emit(new nsp.MovieDeletingEvent({
						state:'start',
						model:data
					}));
				}
			});

			$('body').on('change','.dropper input[type=file]',(e)=> {
  				this.previewImage(e.target.files,this.params.renderAs,e);
  				this.params.renderAs = 1;
			});

			$('body').on('click','.dropper .trigger-file',(e)=> {
  				$(e.target).parents(".dropper").find('input[type=file]').click();
			});

			this.saveDefaultImgs($("#modal-insert"));

		    $("body").on("click",".dropper .reset-file",(e)=> {
  				e.preventDefault();
  				var parentModal = $(e.target).parents('.modal:first');
  				var parent = $(e.target).parents('.dropper');
  				var img = parent.find('.dropper-target');
				var input  = parent.find('input[type=file]');
				var files = input.get()[0].files;
				if(files.length){
					input.val('');
				}

  				parentModal.find('.dropper .reset-file').each((i,el)=>{
  					if(el === e.target){
  						if(parentModal.data('id')){
  							img.css('background-image',this.copyDefaultImageUpdate[i]);
  						}
  						else{
  							img.css('background-image',this.copyDefaultImage[i]);
  						}
  					  	return true;
  					}
  				});
		    });

			$('body').on("click",".modal-form .has-collection .collection-add",
				e=>{
					e.preventDefault();
					var parent = $(e.target).parent();
					var tpl = parent.find('[data-prototype]').data('prototype');
					var index = parent.children(" .input-group ").length;

					tpl = tpl.replace(/__name__/g,index);

					var a = $('<span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-trash"></i></button></span>');
					var li = $('<div class="input-group input-group-sm">').append(a).append(tpl);
					li.find('label').remove();

					li.insertBefore(parent.find('a'));
					a.find('button').on({
						click:e=>{
							li.remove();
						}
					})
			})

			// on ecoute les evenements
			this.subscribe(event=>{
				if(event instanceof nsp.MovieUpdatingEvent){
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

								rowItem.find('.data-item-title').html(data.data.title);
								rowItem.find('.data-item-url').html(data.data.fullUrl);
							}
							alertShow();
						}
					}
				}
				else if(event instanceof nsp.MovieDeletingEvent){
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
							}
							else{
								alertShow();
							}
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
				else if(event instanceof nsp.FileReaderEvent){

					if(event.params.state == "load"){

						var files = event.params.files;
						var target = event.params.target;
						var input  = target.parents('.dropper:first').find('input[type=file]');
						input.get()[0].files = files;

						if(event.params.pos == 1){
							
						}
						else if(event.params.pos == 2){
							/*var files = event.params.files;
							var input  = $('#current-widget-data form input[type=file]');
							var _token  = $('#current-widget-data form input[name=_token]').val();
							input.get()[0].files = files;

					    	this.params.selectedDataView.addClass('updating');

					    	this.emit(new nsp.UploadEvent({
								state:'start',
								file:files[0],
								_token:_token,
							}));*/

						}
					}
				}
			});

			document.addEventListener("dragenter",e=>{
				$(document.body).addClass('dragenter');
				e.preventDefault();
			});
			document.addEventListener("dragover",e=>{
				$(document.body).addClass('dragenter');
				e.preventDefault();
			});
			document.addEventListener("dragleave",e=>{
				e.preventDefault();
				$(document.body).removeClass('dragenter');
			});
			document.addEventListener("dragend",e=>{
				e.preventDefault();
				$(document.body).removeClass('dragenter');
			});

			var droppers = document.getElementsByClassName("dropper");
			for (let i = 0,c = droppers.length; i < c; i++) {
				let el = droppers[i];
				this.applyDropEvent(el);
			}

			// galerie photo
			$("body").on('drop','.modal .scene-dropper',e=>{

				e.preventDefault();
				$(document.body).removeClass('dragenter');				
				var files = e.originalEvent.dataTransfer.files;
				if(!files.length) return;

				var dropper = $(e.currentTarget);
				var divProto = dropper.parent().find('div[data-prototype]');
				dropper.addClass('dropped');

				var parentModal = dropper.parents('div[data-id]:first');

				var render = (file)=>{
					var reader = new FileReader();

				    reader.addEventListener('load', ()=> {
				    	var counter = dropper.find(".scene-thumbnail").length;

				    	var image = $('<img width="211" height="180" draggable="false">');
				    	var div = $(`
				    		<div class="scene-thumbnail">
				    			<a href="">
				    				<i class="fa fa-times"></i>
				    			</a>

				    			<div class="progress progress-sm active">
					            	<div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
					                	<span class="sr-only">0% Complete</span>
					                </div>
					            </div>
				    		</div>`);

				    	var tpl = divProto.data('prototype');
						tpl = $(tpl.replace(/__name__/g,"")).hide();
						tpl.find('label').remove();
						tpl.find('input').removeAttr('id');

						div.append(tpl);

				    	div.find('a').on({
				    		click:e=>{
				    			e.preventDefault();
				    			div.remove();
				    		}
				    	})

				    	image.on({
				    		load:()=>{
				    			div.append(image);
				    			dropper.append(div);

				    			if(parentModal.length){

				    				var progressBar = div.find('.progress .progress-bar');

				    				var ref = this.subscribe(event=>{
										if(event instanceof nsp.UploadEvent){
											if(event.params.state != "progress") return;

											if(event.params.file === file){
												progressBar.css('width',`${event.params.percent}%`);

												if(event.params.percent == 100){
													ref.unsubscribe();
													setTimeout(function(){
														progressBar.parent().remove();
													},5000);
												}
											}
										}
									});


									this.emit(new nsp.UploadEvent({
										state:'start',
										file:file,
										_target:"scene",
									}));
								}

				    		}
				    	});
				    	image.attr('src',reader.result);
				    });
				    reader.readAsDataURL(file);
				}

				for(let file of files){
					render(file);
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
							data:event.params,
							dataType:"text",
						}));
					}
				}
			});

			scroller.forWindow();

			return this;
		}

		MovieView.prototype.saveDefaultImgs = function(modal){
			var img270x360;
			var img640x360;
			var img1920x360;

			modal.find('.dropper .dropper-target').each((i,el)=>{
				if(i == 0){
					img270x360 = $(el).css('background-image');
				}
				else if(i == 1){
					img640x360 = $(el).css('background-image');
				}
				else if(i == 2){
					img1920x360 = $(el).css('background-image');
				}

				if(modal.data('id')){
					this.copyDefaultImageUpdate = [img270x360,img640x360,img1920x360];
				}
				else{
					this.copyDefaultImage = [img270x360,img640x360,img1920x360];
				}
			});
		}

		MovieView.prototype.renderSelectedData = function(view){
			var ref = $("#modal-update-area").html(view);
			var modal = ref.find('#modal-update');
			modal.modal({
				backdrop:'static',
				show:true
			});
			this.saveDefaultImgs(modal);
		}

		MovieView.prototype.previewImage = function(files, pos = 1, event){
			if(!files.length) return;

			var file = files[0];
			var filenames = file.name;
			var reader = new FileReader();

		    reader.addEventListener('load', ()=> {
		    	var img = $(event.target).parents('.dropper').find('.dropper-target');
		    	img.css('background-image',`url(${reader.result})`);
		    	this.emit(new nsp.FileReaderEvent({state:"load",pos:pos,files:files,target:img}));
		    });

		    reader.addEventListener('error', ()=> {
		    	this.emit(new nsp.FileReaderEvent({state:"error",pos:pos,files:files}));
		    });

		    reader.addEventListener('loadend', ()=> {
		    	this.emit(new nsp.FileReaderEvent({state:"end",pos:pos,files:files}));
		    });

		    reader.addEventListener('progress', ()=> {
		    	this.emit(new nsp.FileReaderEvent({state:"progress",pos:pos,files:files}));
		    });

		    reader.addEventListener('abort', ()=> {
		    	this.emit(new nsp.FileReaderEvent({state:"abort",pos:pos,files:files}));
		    });

		    this.emit(new nsp.FileReaderEvent({state:"start",pos:pos,files:files}));

		    reader.readAsDataURL(file);
		}


		MovieView.prototype.applyDropEvent = function(dropper){
			dropper.addEventListener("drop",e=>{
				e.preventDefault();
				$(document.body).removeClass('dragenter');
				this.previewImage(e.dataTransfer.files,2,e);
			});
		};

		return MovieView;
	})();

})(AdminManager);