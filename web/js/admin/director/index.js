$(document).ready(function($){
	var nsp = AdminManager;
	var repository = AdminManager.container.get('DirectorRepository');
	var view = AdminManager.container.get('DirectorView');
	view.controller();

	var rightSection = $("#right-section");

	view.subscribe(event=>{

		if((event instanceof nsp.DirectorUpdatingEvent) || (event instanceof nsp.DirectorDeletingEvent)) {
			if(event.params.state != "start") return;
			
			repository.customRequest(event)
			.then(data=>{

				if(event instanceof nsp.DirectorUpdatingEvent){
					view.emit(new nsp.DirectorUpdatingEvent({state:'end',data:data}));
				}
				else if(event instanceof nsp.DirectorDeletingEvent){
					view.emit(new nsp.DirectorDeletingEvent({state:'end',data:data}));
				}
			},msg=>{
				if(event instanceof nsp.DirectorUpdatingEvent){
					view.emit(new nsp.DirectorUpdatingEvent({state:'fails'}));
				}
				else if(event instanceof nsp.DirectorDeletingEvent){
					view.emit(new nsp.DirectorDeletingEvent({state:'fails'}));
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