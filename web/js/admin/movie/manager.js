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

	/**
	* evenement lorsqu'on lance la suppression d'une photo de gallery
	*/
	nsp.MovieSceneDeletingEvent = (function(){
		function MovieSceneDeletingEvent(params){
			nsp.Event.call(this,'gallery/delete',params);
		};
		Object.assign(MovieSceneDeletingEvent.prototype, nsp.Event.prototype);
		return MovieSceneDeletingEvent;
	})();

	/**
	* evenement lorsqu'on lance un upload de metadata
	*/
	nsp.UploadMetadataEvent = (function(){
		function UploadMetadataEvent(params){
			nsp.Event.call(this,'metadata/upload',params);
		};
		Object.assign(UploadMetadataEvent.prototype, nsp.Event.prototype);
		return UploadMetadataEvent;
	})();
	

	nsp.fn.MovieRepository = (function(){

		function MovieRepository(params){
			nsp.Repository.call(this,params);
		};

		Object.assign(MovieRepository.prototype, nsp.Repository.prototype);

		

		MovieRepository.prototype.customRequest = function(event,METHOD = 'POST'){

			return new Promise((resolve,reject)=>{
	  			this.request({
	  				url:`/admin/movies/${this.current.id}/${event.type}`,
	  				method:METHOD,
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
			var _token = event.params._token;

			var formData = new FormData();
			formData.append('gallery[]',file);
			formData.append('_token',_token);

			var url = `/admin/movies/${this.current.id}/image/${event.type}`;

			if(_target == "gallery"){
				url = `/admin/movies/${this.current.id}/gallery/${event.type}`
			}
			else{
				formData.append('_target',_target);
			}

			return new Promise((resolve,reject)=>{

	  			this.request({
	  				enctype: 'multipart/form-data',
	  				url:url,
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

		MovieRepository.prototype.uploadMetadata = function(event){

			var file = event.params.file;
			var _model = event.params._model;
			var _token = event.params._token;

			var formData = new FormData();
			formData.append('file',file);
			formData.append('_token',_token);
			var url = `/admin/movies/metadata/upload/${_model}`;

			return new Promise((resolve,reject)=>{

	  			this.request({
	  				enctype: 'multipart/form-data',
	  				url:url,
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

								    this.emit(new nsp.UploadMetadataEvent({
										state:'progress',
										total:e.total,
										loaded:e.loaded,
										percent:percent,
										file:file
									}));

								} else {
								    // Impossible de calculer la progression puisque la taille totale est inconnue
								}
				            });
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

			this.translations;

			this.vars({
				selectedDataView:$("#current-widget-data"),
				rightSection:$("#right-section"),
				renderAs:1,
				copyDefaultImage:null,
				copyDefaultImageUpdate:null,
				model:null,
				uploadExt:['jpg','jpeg','png'],
				$tpl:{
					errors:`
						<ul>
							{{#errors}}
								<li>{{ . }}</li>
							{{/errors}}
						</ul>
					`,
					translation:`
						<div class="box box-danger translation-item">
                            <div class="box-body"> 
								<div class="form-group locale">
	                                <label>Langue</label>
	                            </div>

	                            <div class="form-group">
	                                <label>Tagline</label>
	                                <textarea name="translation[__locale__][tagline]" class="form-control" rows="3" placeholder="Saisir la traduction..."></textarea>
	                            </div>

	                            <div class="form-group">
	                                <label>Logline</label>
	                                <textarea name="translation[__locale__][logline]" class="form-control" rows="3" placeholder="Saisir la traduction..."></textarea>
	                            </div>

	                            <div class="form-group">
	                                <label>Synopsis</label>
	                                <textarea name="translation[__locale__][synopsis]" class="form-control" rows="3" placeholder="Saisir la traduction..."></textarea>
	                            </div>
		                    </div>

		                    <div class="box-footer response-area"> 
		                    	<div class="alert alert-info alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

                                    <p class="response-msg"></p>
                                </div>
		                    </div>

		                    <div class="box-footer"> 
		                    	<button type="button" class="btn btn-primary remove">
                                    <i class="fa fa-trash"></i> Retirer cette traduction
                                </button>

                                <button type="button" class="btn btn-primary pull-right save">
                                    <i class="fa fa-save"></i> Enregistrer cette traduction
                                </button>
		                    </div>

		                    <div class="overlay">
								<i class="fa fa-spinner fa-spin fa-3x"></i>
							</div>

                        </div>
					`
				}
			});
		};

		Object.assign(MovieView.prototype, nsp.View.prototype);


		MovieView.prototype.controller = function(){

			var markdown  = new showdown.Converter();

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
  				this.previewImage(e.target.files,e);
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
				if(e.currentTarget == document){
					$(document.body).removeClass('dragenter');
				}
			});
			document.addEventListener("dragend",e=>{
				e.preventDefault();
				$(document.body).removeClass('dragenter');
			});

			// les vignettes du programme
			$("body").on('drop','.modal .dropper',e=>{
				e.preventDefault();

				$(document.body).removeClass('dragenter');				
				var files = e.originalEvent.dataTransfer.files;
				if(!files.length) return;

				var file = files[0];
				var ext = file.name.split('.');
				ext = ext.slice(-1);
				ext = ext[0];
				ext = ext.toLowerCase();

				if (~this.params.uploadExt.indexOf(ext)){
					this.previewImage(files,e);
				}
			});


			// galerie photo
			$("body").on('drop','.modal .scene-dropper',e=>{

				e.preventDefault();
				$(document.body).removeClass('dragenter');				
				var files = e.originalEvent.dataTransfer.files;
				if(!files.length) return;

				var dropper = $(e.currentTarget);
				var divProto = dropper.parent().find('div[data-prototype]');

				var parentModal = dropper.parents('div[data-id]:first');

				var render = (file)=>{
					var reader = new FileReader();

				    reader.addEventListener('load', ()=> {

				    	var image = $('<img width="250" height="140" draggable="false">');
				    	var div = $(`
				    		<div class="scene-thumbnail">
				    			<a href="" class="btn-close" data-title="retirer de la liste" data-toggle="tooltip">
				    				<i class="fa fa-times"></i>
				    			</a>

				    			<div class="custom_overlay"></div>
				    		</div>`);

				    	var tpl = divProto.data('prototype');
						tpl = $(tpl.replace(/__name__/g,"")).hide();
						tpl.find('label').remove();
						tpl.find('input').removeAttr('id');

						div.append(tpl);

						if(parentModal.length){
							div.append('<div class="upload-statut fa fa-check fa-3x statut-success"></div>');

							div.append(`<div class="progress progress-sm active">
					            	<div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
					                	<span class="sr-only">0% Complete</span>
					                </div>
					            </div>`);
						}

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
													div.removeClass("uploading");
													div.addClass("uploaded");
												}
											}
										}
									});

									var ref2 = this.subscribe(event=>{
										if(event instanceof nsp.UploadEvent){
											if(event.params.state != "end") return;

											if(event.params.file === file){
												ref2.unsubscribe();
												div.attr('data-id',event.params.data.data.id);
											}
										}
									});

									var _token  = parentModal.find('input[name=_token]').val();

									this.emit(new nsp.UploadEvent({
										state:'start',
										file:file,
										_target:"gallery",
										_token:_token,
									}));

									div.addClass("uploading");
								}
				    		}
				    	});
				    	image.attr('src',reader.result);
				    });
				    reader.readAsDataURL(file);
				}

				for(let file of files){
					var ext = file.name.split('.');
					ext = ext.slice(-1);
					ext = ext[0];
					ext = ext.toLowerCase();
					if (~this.params.uploadExt.indexOf(ext)){
						dropper.addClass('dropped');
						render(file);
					}
				}
			});

			$("body").on("click","#modal-update .scene-dropper .scene-thumbnail a",e=>{
    			e.preventDefault();
    			var parentModal = $(e.target).parents("#modal-update");
    			var galleryModal = $("#modal-update-gallery");
    			var parentThumbnail = $(e.target).parents(".scene-thumbnail:first");
    			var dropper = parentThumbnail.parents(".scene-dropper:first");

    			var dataId = parentThumbnail.data('id');

    			var removeCbk = function(){
    				parentThumbnail.remove();
	    			var counter = dropper.find(".scene-thumbnail").length;
	    			if(counter == 0){
	    				dropper.removeClass('dropped');
	    			}
    			};

				parentModal.off('shown.bs.modal');

    			var _shownFn = (ee)=>{
					parentModal.animate({ scrollTop: dropper.offset().top }, 1000);
				};

				parentModal.on('shown.bs.modal', _shownFn);


    			if(parentThumbnail.length && dataId){
    				galleryModal.attr('data-id',dataId);

    				var fn = (e)=>{

    					var shownFn = (ee)=>{
    						var submitBtn = galleryModal.find('button[type=submit]');
    						submitBtn.on({
    							click:ee=>{
    								ee.preventDefault();

    								removeCbk();
    								submitBtn.off();

    								this.emit(new nsp.MovieSceneDeletingEvent({
										state:'start',
										model:{
											scene_id:dataId
										},
									}));

    								galleryModal.modal("hide");


    							}
    						});
    					};

    					galleryModal.on('shown.bs.modal', shownFn);
    					galleryModal.modal("show");

    					parentModal.off('hidden.bs.modal', fn);

    					var fn2 = (ee)=>{
    						galleryModal.off('hidden.bs.modal', fn2);
    						galleryModal.off('shown.bs.modal', shownFn);
    						parentModal.modal("show");
    						galleryModal.attr('data-id',null);
    					};

    					galleryModal.on('hidden.bs.modal', fn2);
    				};

    				parentModal.on('hidden.bs.modal', fn);
    				parentModal.modal("hide");
    			}
    			else{
    				removeCbk();
    			}
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

				parentModal.off('shown.bs.modal');

    			var _shownFn = (ee)=>{
					parentModal.animate({ scrollTop: collectionBadge.offset().top - 200 }, 1000);
				};

				parentModal.on('shown.bs.modal', _shownFn);

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

    								var evt = new nsp.MovieSceneDeletingEvent({
										state:'start',
										model:{
											id:dataId
										},
									});

									evt.type = route;
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


    		var translatablesProxy = function(el){
				var obj = $(el);
				var target = $('[data-translatable-target='+obj.data('translatable')+']');
				var text = obj.val();
				text = text.replace(/<(.+?)>/ig,'$1');
				//text = text.replace(/\n/ig,'<br>');
    			html      = markdown.makeHtml(text);
				target.html(html);
    		}

    		$('body').on('keyup','[data-translatable]',e=>{
    			translatablesProxy(e.target);
    		});


    		$('body').on('shown.bs.modal','.modal-form',(e)=> {
    			var obj = $(e.target);
  				var translatables = obj.find('[data-translatable]');

    			translatables.each((i,el)=>{
    				translatablesProxy(el);
    			});

    			var btnAdd = obj.find("#translate-area-tools button");
    			btnAdd.off();

    			btnAdd.on({
    				click:e=>{
    					this.insertNewTranslation('',obj);
    				}
    			});

			});

			// recuperation de traduction en base de donnée
			$("body").on('shown.bs.tab','a[data-toggle="tab"]', (e)=> {
				var obj = $(e.target);
				var modal = obj.parents('.modal:first');

				if(modal.attr('id') == 'modal-update'){

					if(obj.attr('href') == "#m-translate-up"){

						var ref = this.subscribe(event=>{
							if(event instanceof nsp.TranslationEvent){

								if(event instanceof nsp.TranslationEvent){
									if(~['end','fails'].indexOf(event.params.state)){

										ref.unsubscribe();
										modal.removeClass('fetching-translation');
										var data = event.params.data;

										if(data && data.data){

											modal.find('#translate-area-data').html('');

											for(var lang in data.data){
												this.insertNewTranslation(lang,modal,data.data[lang]);
											}
										}
									}
								}
							}
						});

						// lance la demande des traductions
						var evt = new nsp.TranslationEvent({
							state:'load',
							model:{},
						});

						evt.type = "translations";
						modal.addClass('fetching-translation');
						this.emit(evt);
					}
				}
			});



			// upload par metadonnées
			$("#modal-metadata .dropzone").on('drop',e=>{

				e.preventDefault();
				$(document.body).removeClass('dragenter');		
				var dropper = $(e.currentTarget);
				var modal = dropper.parents("#modal-metadata");
				var modalInfo = $('#modal-info');


				if(dropper.hasClass("uploading")) return;

				dropper.removeClass("uploading");
				dropper.removeClass("uploaded");

				var progressBar = dropper.find('.progress .progress-bar');
				var buttons = modal.find('button');

				var files = e.originalEvent.dataTransfer.files;
				if(!files.length) return;
				var file = files[0];

				var input = dropper.find('input[type=file]');

				var ext = file.name.split('.');
				ext = ext.slice(-1);
				ext = ext[0];
				ext = ext.toLowerCase();
				if (ext.toLowerCase() == "zip"){

					input.get()[0].files = files;
					buttons.attr('disabled','disabled');

					var ref = this.subscribe(event=>{
						if(event instanceof nsp.UploadMetadataEvent){
							if(event.params.state != "progress") return;

							if(event.params.file === file){
								progressBar.css('width',`${event.params.percent}%`);

								if(event.params.percent == 100){
									ref.unsubscribe();
									dropper.removeClass("uploading");
									dropper.find('button').removeAttr('disabled');
									setTimeout(function(){
										dropper.addClass("saving");
										dropper.addClass("uploaded");
										progressBar.css('width',`0%`);
									},1000);
								}
							}
						}
					});

					var ref2 = this.subscribe(event=>{
						if(event instanceof nsp.UploadMetadataEvent){
							if(['end','fails'].indexOf(event.params.state) == -1) return;

							if(event.params.file === file){

								var data = event.params.data;


								var alertShow = ()=>{

									modalInfo.on('hidden.bs.modal', function () {
										modalInfo.off('hidden.bs.modal');
						  				modal.modal('show');
									});

									if(data && data.hasOwnProperty('message')){
										$('#modal-info .modal-body h4').html(data.message);
										$('#modal-info').modal('show');
									}
									else if(data && data.hasOwnProperty('errors')){
										var tpl = this.render(this.params.$tpl.errors,data);
										$('#modal-info .modal-body h4').html(tpl);
										$('#modal-info').modal('show');
									}
									modal.off('hidden.bs.modal');
								};

								modal.on('hidden.bs.modal', function () {
					  				alertShow();
								});

								modal.modal('hide');

								dropper.removeClass("saving");
								buttons.removeAttr('disabled');
								ref2.unsubscribe();
							}
						}
					});

					var _token  = dropper.find('input[name=_token]').val();

					this.emit(new nsp.UploadMetadataEvent({
						state:'start',
						file:file,
						_model:"webmaster",
						_token:_token,
					}));

					dropper.find('button').attr('disabled','disabled');

					dropper.addClass('dropped');
					dropper.addClass("uploading");
				}
			});

			$("#modal-metadata .dropzone").on('click',e=>{
				var obj = $(e.target);
				var input = obj.find('input[type=file]');
				input.trigger('click');
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

		MovieView.prototype.previewImage = function(files, event){
			if(!files.length) return;

			var file = files[0];
			var filenames = file.name;

			var ext = file.name.split('.');
			ext = ext.slice(-1);
			ext = ext[0];
			ext = ext.toLowerCase();
			if (this.params.uploadExt.indexOf(ext) == -1){
				return;
			}

			var reader = new FileReader();

		    reader.addEventListener('load', ()=> {
		    	var img = $(event.target).parents('.dropper').find('.dropper-target');
		    	img.css('background-image',`url(${reader.result})`);
		    	this.emit(new nsp.FileReaderEvent({state:"load",files:files,target:img}));
		    });

		    reader.addEventListener('error', ()=> {
		    	this.emit(new nsp.FileReaderEvent({state:"error",files:files}));
		    });

		    reader.addEventListener('loadend', ()=> {
		    	this.emit(new nsp.FileReaderEvent({state:"end",files:files}));
		    });

		    reader.addEventListener('progress', ()=> {
		    	this.emit(new nsp.FileReaderEvent({state:"progress",files:files}));
		    });

		    reader.addEventListener('abort', ()=> {
		    	this.emit(new nsp.FileReaderEvent({state:"abort",files:files}));
		    });

		    this.emit(new nsp.FileReaderEvent({state:"start",files:files}));

		    reader.readAsDataURL(file);
		}

		MovieView.prototype.insertNewTranslation = function(lang,modal,data){

			var select = `
				<select class="translation-btn-add form-control" name="locale[]" >
					<option value="">Selectionner une langue...</option>
					<option value="fr">Français</option>
					<option value="en">Anglais</option>
					<option value="ar">Arabe</option>
					<option value="pt">Portugais</option>
				</select>
			`;

			var tranlationsDiv = modal.find('#translate-area-data');
			var locale = $(select);
			
			var tpl = $(this.render(this.params.$tpl.translation,{}));

			var taglineInput = tpl.find("[name*=tagline]");
			var loglineInput = tpl.find("[name*=logline]");
			var synopsisInput = tpl.find("[name*=synopsis]");
			var responseMsg = tpl.find(".response-msg");

			if(typeof data != "undefined"){
				taglineInput.val(data.tagline);
				loglineInput.val(data.logline);
				synopsisInput.val(data.synopsis);
			}
			
			locale.on({
				change:ee=>{
					tpl.find('textarea').each((i,el)=>{
						var name = $(el).attr('name');
						var reg = /(\w+)\[.+?\](\[.+?\])/ig;
						name = name.replace(reg,`$1[${ee.target.value}]$2`);
						$(el).attr('name',name);
					});
				}
			});

			locale.val(lang).trigger('change');

			tpl.find('.locale').append(locale);
			tpl.find('button.remove').on({
				click:ee=>{
					ee.preventDefault();
					tpl.remove();
				}
			});

			var btnSave = tpl.find('button.save');

			if(modal.attr('id') == "modal-update"){
				btnSave.on({
					click:ee=>{
						ee.preventDefault();

						tpl.addClass('updating');

						var ref = this.subscribe(event=>{
							if(event instanceof nsp.TranslationEvent){

								if(event instanceof nsp.TranslationEvent){
									if(~['end','fails'].indexOf(event.params.state)){
										ref.unsubscribe();
										tpl.removeClass('updating');
										tpl.addClass('updated');
										var data = event.params.data;

										if(data && data.message){
											responseMsg.html(data.message);
										}

										setTimeout(()=>{
											tpl.removeClass('updated');
										},5000);
									}
								}
							}
						});

						var evt = new nsp.TranslationEvent({
							state:'update',
							model:{
								locale:locale.val(),
								tagline:taglineInput.val(),
								logline:loglineInput.val(),
								synopsis:synopsisInput.val()
							},
						});
						this.emit(evt);
					}
				});
			}
			else{
				btnSave.remove();
			}
			
			tranlationsDiv.append(tpl);
		}


		return MovieView;
	})();

})(AdminManager);