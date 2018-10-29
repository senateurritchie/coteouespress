$(document).ready(function($){
	var nsp = AdminManager;
	var repository = AdminManager.container.get('UserRepository');
	var view = AdminManager.container.get('UserView');
	var rightSection = $("#right-section");

	view.vars({
		rightSection:rightSection,
		currentUserView:$("#current-widget-user"),
		currentUserView:$("#current-widget-user"),
		currentUserModels:$('#current-widget-user .widget-user-privileges-model input[type=checkbox]'),
	})
	.controller();


	view.subscribe(event=>{
		if((event instanceof nsp.UserRevokeRoleEvent) || (event instanceof nsp.UserGrantRoleEvent)) {
			repository.roleRequest(event)
			.then(data=>{
				view.emit(new nsp.UserRoleUpdatingEvent({state:'end',data:data}));
			},msg=>{
				view.emit(new nsp.UserRoleUpdatingEvent({state:'fails'}));
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
});