var AdminManager = AdminManager || {};

(function(nsp){

	/**
	* evenement lorsqu'on supprime un role
	*/
	nsp.UserRevokeRoleEvent = (function(){
		function UserRevokeRoleEvent(params){
			nsp.Event.call(this,'revoke-role',params);
		};
		Object.assign(UserRevokeRoleEvent.prototype, nsp.Event.prototype);
		return UserRevokeRoleEvent;
	})();

	/**
	* evenement lorsqu'on ajoute un role
	*/
	nsp.UserGrantRoleEvent = (function(){
		function UserGrantRoleEvent(params){
			nsp.Event.call(this,'grant-role',params);
		};
		Object.assign(UserGrantRoleEvent.prototype, nsp.Event.prototype);
		return UserGrantRoleEvent;
	})();

	/**
	* evenement lorsqu'on fait une mise a jour
	*/
	nsp.UserRoleUpdatingEvent = (function(){
		function UserRoleUpdatingEvent(params){
			nsp.Event.call(this,'role-update',params);
		};
		Object.assign(UserRoleUpdatingEvent.prototype, nsp.Event.prototype);
		return UserRoleUpdatingEvent;
	})();

	nsp.fn.UserRepository = (function(){

		function UserRepository(params){
			nsp.Repository.call(this,params);
		};

		Object.assign(UserRepository.prototype, nsp.Repository.prototype);


		UserRepository.prototype.roleRequest = function(event){

			return new Promise((resolve,reject)=>{
	  			this.request({
	  				url:`/fr/admin/users/${this.current.id}/${event.type}`,
	  				method:"POST",
	  				data:{
	  					role_id:event.params.role_id
	  				}
		  		})
		  		.done(data=>{
		  			resolve(data);
		  		})
		  		.fail(msg=>{
		  			reject(msg);
		  		});
	  		});
		};

		return UserRepository;
	})();


	nsp.fn.UserView = (function(){
		function UserView(params){
			nsp.View.call(this,params);
			this.params.$tpl = {
			}
		};

		Object.assign(UserView.prototype, nsp.View.prototype);

		UserView.prototype.controller = function(){

			this.params.currentUserView.find('button[type=reset]').on({
				click:e=>{
					this.params.rightSection.removeClass('user-active');
				}
			});

			$("body").on("change","#modal-update input[type=checkbox]",e=>{

				var modal = $(e.target).parents("#modal-update");

				var selected = e.target;
				var value = selected.value;

				// on ajoute
				if(selected.checked){
					this.emit(new nsp.UserGrantRoleEvent({role_id:value}));
				}
				// on supprime
				else{
					this.emit(new nsp.UserRevokeRoleEvent({role_id:value}));
				}
				modal.addClass('updating');
			});


			// on ecoute les evenements
			this.subscribe(event=>{
				if(event instanceof nsp.UserRoleUpdatingEvent){
					if(~['end','fails'].indexOf(event.params.state)){

						var modal = $("#modal-update");
						var modal_info = $('#modal-info');

						modal.removeClass('updating');
						var data = event.params.data;

						var fn1 = function () {
			  				modal_info.modal('show');
			  				modal.off('hidden.bs.modal',fn1);
						};

						var fn2 = function () {
							modal.modal('show');
							modal_info.off('hidden.bs.modal',fn2);
						};

						modal.on('hidden.bs.modal',fn1);
						modal_info.on('hidden.bs.modal',fn2);
						modal.modal('hide');

						if(data.hasOwnProperty('message')){
							$('#modal-info .modal-body h4').html(data.message);
							$('#modal-info').modal('show');
						}
						else if(data.hasOwnProperty('errors')){
							var tpl = this.render(this.params.$tpl.errors,data);
							$('#modal-info .modal-body h4').html(tpl);
							$('#modal-info').modal('show');
						}
					}
				}
			});

			return this;
		}

		UserView.prototype.renderSelectedData = function(view){
			var ref = $("#modal-update-area").html(view);
			var modal = ref.find('#modal-update');
			modal.modal({
				backdrop:'static',
				show:true
			});
		}

		return UserView;
	})();

})(AdminManager);