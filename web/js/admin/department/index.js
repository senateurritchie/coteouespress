$(document).ready(function($){
	var nsp = AdminManager;
	var repository = AdminManager.container.get('DepartmentRepository');
	var view = AdminManager.container.get('DepartmentView');
	view.controller();

	var rightSection = $("#right-section");

	view.subscribe(event=>{
		if((event instanceof nsp.DepartmentUpdatingEvent) || (event instanceof nsp.DepartmentDeletingEvent)) {
			if(event.params.state != "start") return;
			
			repository.customRequest(event)
			.then(data=>{

				if(event instanceof nsp.DepartmentUpdatingEvent){
					view.emit(new nsp.DepartmentUpdatingEvent({state:'end',data:data}));
				}
				else if(event instanceof nsp.DepartmentDeletingEvent){
					view.emit(new nsp.DepartmentDeletingEvent({state:'end',data:data}));
				}
			},msg=>{
				if(event instanceof nsp.DepartmentUpdatingEvent){
					view.emit(new nsp.DepartmentUpdatingEvent({state:'fails'}));
				}
				else if(event instanceof nsp.DepartmentDeletingEvent){
					view.emit(new nsp.DepartmentDeletingEvent({state:'fails'}));
				}
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
				repository.setCurrent(data);
				view.renderSelectedData(data);
			},msg=>{
				rightSection.removeClass('data-active');
				rightSection.removeClass('data-loading');
				self.removeClass('disabled');
			})
		}
	});
});