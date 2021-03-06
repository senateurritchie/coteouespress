$(document).ready(function($){
	var nsp = AdminManager;
	var repository = AdminManager.container.get('MovieRepository');
	var view = AdminManager.container.get('MovieView');
	view.controller();

	var rightSection = $("#right-section");

	repository.subscribe(event=>{

		if(event instanceof nsp.UploadEvent){
			if(event.params.state != "progress") return;
			view.emit(event);
		}
		else if(event instanceof nsp.UploadMetadataEvent){
			if(event.params.state != "progress") return;
			view.emit(event);
		}
	});

	view.subscribe(event=>{
		
		if(event instanceof nsp.MovieSceneDeletingEvent){
			if(event.params.state != "start") return;
			repository.customRequest(event)
			.then(data=>{

				view.emit(new nsp.MovieSceneDeletingEvent({
					state:'end',
					data:data
				}));

			},msg=>{
				view.emit(new nsp.MovieSceneDeletingEvent({
					state:'fails',
				}));
			});
		}
		if(event instanceof nsp.TranslationEvent){

			if(~['update','load'].indexOf(event.params.state)){
				var METHOD = event.params.state == "update"?"POST":"GET";
				repository.customRequest(event,METHOD)
				.then(data=>{

					view.emit(new nsp.TranslationEvent({
						state:'end',
						data:data
					}));

				},msg=>{
					view.emit(new nsp.TranslationEvent({
						state:'fails',
					}));
				});
			}
		}
		else if(event instanceof nsp.UploadEvent){
			if(event.params.state != "start") return;

			repository.uploadImage(event)
	    	.then(data=>{

	    		view.emit(new nsp.UploadEvent({
					state:'end',
					data:data,
					file:event.params.file,
				}));

	    	},msg=>{
	    		view.emit(new nsp.UploadEvent({
					state:'fails',
				}));
	    	});
		}
		else if(event instanceof nsp.UploadMetadataEvent){
			if(event.params.state != "start") return;

			repository.uploadMetadata(event)
	    	.then(data=>{

	    		view.emit(new nsp.UploadMetadataEvent({
					state:'end',
					data:data,
					file:event.params.file,
				}));

	    	},msg=>{
	    		view.emit(new nsp.UploadMetadataEvent({
					state:'fails',
					file:event.params.file,
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


	var cart = new nsp.plugins.Cart();
	cart.subscribe(event=>{
		
    });

    cart.init({
    	entries:'.cart-entry',
    	container:'#cart',
    });

    var storage = cart.loadStorage();
	cart.render(storage,'cookies');
	cart.emit(new nsp.Event('initialized'));


    /*repository.cartRequest(new nsp.Event(''),"GET")
	.then(data=>{
		if(data && data.hasOwnProperty('data') && data.data instanceof Array){
			cart.render(data.data,'cookies');
		}
		cart.emit(new nsp.Event('initialized'));
	},msg=>{
		cart.emit(new nsp.Event('initialized'));
	});*/
});