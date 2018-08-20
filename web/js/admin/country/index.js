$(document).ready(function($){
	var nsp = AdminManager;
	var repository = AdminManager.container.get('CountryRepository');
	var view = AdminManager.container.get('CountryView');
	view.controller();

	var rightSection = $("#right-section");

	view.subscribe(event=>{
		if((event instanceof nsp.CountryUpdatingEvent) || (event instanceof nsp.CountryDeletingEvent)) {
			if(event.params.state != "start") return;
			
			repository.customRequest(event)
			.then(data=>{

				if(event instanceof nsp.CountryUpdatingEvent){
					view.emit(new nsp.CountryUpdatingEvent({state:'end',data:data}));
				}
				else if(event instanceof nsp.CountryDeletingEvent){
					view.emit(new nsp.CountryDeletingEvent({state:'end',data:data}));
				}
			},msg=>{
				if(event instanceof nsp.CountryUpdatingEvent){
					view.emit(new nsp.CountryUpdatingEvent({state:'fails'}));
				}
				else if(event instanceof nsp.CountryDeletingEvent){
					view.emit(new nsp.CountryDeletingEvent({state:'fails'}));
				}
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