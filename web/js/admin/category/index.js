$(document).ready(function($){
	var nsp = AdminManager;
	var repository = AdminManager.container.get('CategoryRepository');
	var view = AdminManager.container.get('CategoryView');
	view.controller();

	var rightSection = $("#right-section");

	view.subscribe(event=>{
		if((event instanceof nsp.CategoryUpdatingEvent) || (event instanceof nsp.CategoryDeletingEvent)) {
			if(event.params.state != "start") return;
			
			repository.customRequest(event)
			.then(data=>{

				if(event instanceof nsp.CategoryUpdatingEvent){
					view.emit(new nsp.CategoryUpdatingEvent({state:'end',data:data}));
				}
				else if(event instanceof nsp.CategoryDeletingEvent){
					view.emit(new nsp.CategoryDeletingEvent({state:'end',data:data}));
				}
			},msg=>{
				if(event instanceof nsp.CategoryUpdatingEvent){
					view.emit(new nsp.CategoryUpdatingEvent({state:'fails'}));
				}
				else if(event instanceof nsp.CategoryDeletingEvent){
					view.emit(new nsp.CategoryDeletingEvent({state:'fails'}));
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
	})

});