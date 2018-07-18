$(document).ready(function($){
	var nsp = AdminManager;
	var repository = AdminManager.container.get('UserRepository');
	var view = AdminManager.container.get('UserView');
	view.vars({
		currentUserView:$("#current-widget-user"),
		currentUserView:$("#current-widget-user"),
		currentUserModels:$('#current-widget-user .widget-user-privileges-model input[type=checkbox]'),
	})
	.controller();

	var rightSection = $("#right-section");

	view.subscribe(event=>{
		if((event instanceof nsp.UserRevokeRoleEvent) || (event instanceof nsp.UserGrantRoleEvent)) {
			repository.roleRequest(event)
			.then(e=>{

			},msg=>{

			});
		}
	});

	$("#users-container .user-item .user-item-tools .edit").on({
		click:function(e){
			e.preventDefault();

			var self = $(this);
			self.addClass('disabled');
			var id = self.data('id');
			rightSection.addClass('user-loading');

			repository.find(id)
			.then(data=>{
				rightSection.addClass('user-active');
				rightSection.removeClass('user-loading');
				self.removeClass('disabled');
				view.renderCurrentUser(data);
				repository.setCurrent(data);
			},msg=>{
				console.log(msg);
				rightSection.removeClass('user-active');
				rightSection.removeClass('user-loading');
				self.removeClass('disabled');
			})
		}
	});

	


});