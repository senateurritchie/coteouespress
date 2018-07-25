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
	  				url:`/admin/Movies/${event.type}/${this.current.id}`,
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
			var _token = event.params._token;
			var formData = new FormData();
			formData.append('image',file);
			formData.append('_token',_token);

			return new Promise((resolve,reject)=>{

	  			this.request({
	  				enctype: 'multipart/form-data',
	  				url:`/admin/movies/${this.current.id}/image/${event.type}`,
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

            $('#modal-update').on('shown.bs.modal', (e)=> {
  				//this.eventsToSelectedDataView();
			});
			
			/*$('#myModal').on('shown.bs.modal', function () {
  				$('#myModal button').removeAttr('disabled');
			});

			$('#myModal button[type=submit]').on({
				click:e=>{
					e.preventDefault();
					$('#myModal button').attr('disabled','disabled');

					this.params.selectedDataView.addClass('updating');
					var data = $(e.target).serialize();

					this.emit(new nsp.MovieDeletingEvent({
						state:'start',
						model:data
					}));
				}
			});

			$('#data-secondary-box form input[type=file]').on('change',(e)=> {
  				this.previewImage(e.target.files,this.params.renderAs);
  				this.params.renderAs = 1;
			});

			$('#data-secondary-box form .trigger-file').on('click',(e)=> {
  				$('#data-secondary-box form input[type=file]').click();
			});


			var img = $('#data-secondary-box .thumbnail-trailer');
		    this.copyDefaultImage = img.attr('src');

			$('#data-secondary-box #thumbnail-trailer-container button').on('click',(e)=> {
  				e.preventDefault();
  				img.attr('src',this.copyDefaultImage);
			});*/

			

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

						if(event.params.pos == 1){
							var files = event.params.files;
							var input  = $('#data-secondary-box form input[type=file]');
							input.get()[0].files = files;
						}
						else if(event.params.pos == 2){
							var files = event.params.files;
							var input  = $('#current-widget-data form input[type=file]');
							var _token  = $('#current-widget-data form input[name=_token]').val();
							input.get()[0].files = files;

					    	this.params.selectedDataView.addClass('updating');

					    	this.emit(new nsp.UploadEvent({
								state:'start',
								file:files[0],
								_token:_token,
							}));

							console.log(files)
						}
					}
				}
				else if(event instanceof nsp.UploadEvent){
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

			/*var dropper_1 = document.getElementById("thumbnail-trailer-container");

			dropper_1.addEventListener("drop",e=>{
				e.preventDefault();
				$(document.body).removeClass('dragenter');
				this.previewImage(e.dataTransfer.files);
			});*/

			

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

		MovieView.prototype.renderSelectedData = function(view){

			var ref = $("#modal-update-area").html(view);
			var modal = ref.find('#modal-update');
			modal.modal('show');
		}

		MovieView.prototype.previewImage = function(files,pos=1){
			var file = files[0];
			var filenames = file.name;
			var reader = new FileReader();

		    reader.addEventListener('load', ()=> {
		    	var img;
		    	if(pos == 1){
		    		img = $('#data-secondary-box .thumbnail-trailer');
		    	}
		    	else if(pos == 2){
		    		img = $('#current-widget-data .thumbnail-trailer');
		    	}
		    	img.attr('src',reader.result);
		    	this.emit(new nsp.FileReaderEvent({state:"load",pos:pos,files:files}));
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

		

		MovieView.prototype.eventsToSelectedDataView = function(){

			this.params.selectedDataView.find('button[type=reset]').on({
				click:e=>{
					e.preventDefault();
					this.params.rightSection.removeClass('data-active');
				}
			});

			this.params.selectedDataView.find('button.trigger-file').on({
				click:e=>{
					this.params.renderAs = 2;
					$('#data-secondary-box form input[type=file]').click();
				}
			});


			this.params.selectedDataView.find('form').on({
				submit:e=>{
					e.preventDefault();
					this.params.selectedDataView.addClass('updating');
					var data = $(e.target).serialize();
					this.emit(new nsp.MovieUpdatingEvent({
						state:'start',
						model:data
					}));
				}
			});

			var dropper = document.getElementById("current-widget-data");
			dropper.addEventListener("drop",e=>{
				e.preventDefault();
				$(document.body).removeClass('dragenter');
				this.previewImage(e.dataTransfer.files,2);
			});

		}

		return MovieView;
	})();

})(AdminManager);