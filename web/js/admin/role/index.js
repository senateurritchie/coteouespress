$(document).ready(function($){
	var nsp = AdminManager;
	var repository = AdminManager.container.get('RoleRepository');
	var view = AdminManager.container.get('RoleView');
	view.controller();

	var rightSection = $("#right-section");

	view.subscribe(event=>{
		if((event instanceof nsp.RoleUpdatingEvent) || (event instanceof nsp.RoleDeletingEvent)) {
			if(event.params.state != "start") return;
			
			repository.customRequest(event)
			.then(data=>{

				if(event instanceof nsp.RoleUpdatingEvent){
					view.emit(new nsp.RoleUpdatingEvent({state:'end',data:data}));
				}
				else if(event instanceof nsp.RoleDeletingEvent){
					view.emit(new nsp.RoleDeletingEvent({state:'end',data:data}));
				}
			},msg=>{
				if(event instanceof nsp.RoleUpdatingEvent){
					view.emit(new nsp.RoleUpdatingEvent({state:'fails'}));
				}
				else if(event instanceof nsp.RoleDeletingEvent){
					view.emit(new nsp.RoleDeletingEvent({state:'fails'}));
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
				console.log(msg);
				rightSection.removeClass('data-active');
				rightSection.removeClass('data-loading');
				self.removeClass('disabled');
			})
		}
	});

	


});