$(document).ready(function($){
	var nsp = AdminManager;
	var repository = AdminManager.container.get('TrailerRepository');
	var view = AdminManager.container.get('TrailerView');
	view.controller();

	var rightSection = $("#right-section");

	view.subscribe(event=>{

		if((event instanceof nsp.TrailerUpdatingEvent) || (event instanceof nsp.TrailerDeletingEvent)) {
			if(event.params.state != "start") return;
			
			repository.customRequest(event)
			.then(data=>{

				if(event instanceof nsp.TrailerUpdatingEvent){
					view.emit(new nsp.TrailerUpdatingEvent({state:'end',data:data}));
				}
				else if(event instanceof nsp.TrailerDeletingEvent){
					view.emit(new nsp.TrailerDeletingEvent({state:'end',data:data}));
				}
			},msg=>{
				if(event instanceof nsp.TrailerUpdatingEvent){
					view.emit(new nsp.TrailerUpdatingEvent({state:'fails'}));
				}
				else if(event instanceof nsp.TrailerDeletingEvent){
					view.emit(new nsp.TrailerDeletingEvent({state:'fails'}));
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
			repository.findBy({},{},limit,offset)
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

	$("#data-container .data-item .data-item-tools .edit").on({
		click:function(e){
			e.preventDefault();

			var self = $(this);
			self.addClass('disabled');
			var id = self.data('id');
			rightSection.addClass('data-loading');

			repository.find(id)
			.then(data=>{
				rightSection.addClass('data-active');
				rightSection.removeClass('data-loading');
				self.removeClass('disabled');
				repository.setCurrent(data.model);
				view.renderSelectedData(data.view);
			},msg=>{
				rightSection.removeClass('data-active');
				rightSection.removeClass('data-loading');
				self.removeClass('disabled');
			})
		}
	});
});