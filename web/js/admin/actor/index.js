$(document).ready(function($){
	var nsp = AdminManager;
	var repository = AdminManager.container.get('ActorRepository');
	var view = AdminManager.container.get('ActorView');
	view.controller();

	var rightSection = $("#right-section");

	view.subscribe(event=>{

		if((event instanceof nsp.ActorUpdatingEvent) || (event instanceof nsp.ActorDeletingEvent)) {
			if(event.params.state != "start") return;
			
			repository.customRequest(event)
			.then(data=>{

				if(event instanceof nsp.ActorUpdatingEvent){
					view.emit(new nsp.ActorUpdatingEvent({state:'end',data:data}));
				}
				else if(event instanceof nsp.ActorDeletingEvent){
					view.emit(new nsp.ActorDeletingEvent({state:'end',data:data}));
				}
			},msg=>{
				if(event instanceof nsp.ActorUpdatingEvent){
					view.emit(new nsp.ActorUpdatingEvent({state:'fails'}));
				}
				else if(event instanceof nsp.ActorDeletingEvent){
					view.emit(new nsp.ActorDeletingEvent({state:'fails'}));
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

	$("#data-container").on("click",".data-item .data-item-tools .edit",function(e){

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
			repository.setCurrent(data);
			view.renderSelectedData(data);

		},msg=>{
			rightSection.removeClass('data-active');
			rightSection.removeClass('data-loading');
			self.removeClass('disabled');
		})
	});
});