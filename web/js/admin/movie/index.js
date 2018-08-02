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

	$("#data-container .data-item .edit").on({
		click:function(e){
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
		}
	});
});