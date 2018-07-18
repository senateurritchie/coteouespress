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

	nsp.fn.UserRepository = (function(){

		function UserRepository(params){
			nsp.Repository.call(this,params);
		};

		Object.assign(UserRepository.prototype, nsp.Repository.prototype);


		UserRepository.prototype.roleRequest = function(event){

			return new Promise((resolve,reject)=>{
	  			this.request({
	  				url:`/admin/users/${event.type}/${this.current.id}`,
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
				currentUserHeader:
				`<div class="widget-user-image">
                	<img class="img-circle" src="/admin/dist/img/user7-128x128.jpg" alt="User Avatar">
              	</div>

              	<h3 class="widget-user-username">{{ username }}</h3>
              	<h5 class="widget-user-desc">{{ masterRole.name }}</h5>
              	<div data-title="état du compte" data-toggle="tooltip" class="label label-warning widget-user-state">{{ state }}</div>`,

				currentUserRoles:`
				{{#privileges}}
					{{> role}}
				{{/privileges}}
				{{^privileges}}
					<tr><td>- aucun autre</td></tr>
				{{/privileges}}
				`,
				currentUserRole:`
				<div><span data-label="{{ label }}" data-id="{{ id }}" class="label label-info"> {{ name }} </span></div>`,
			}
		};

		Object.assign(UserView.prototype, nsp.View.prototype);

		UserView.prototype.controller = function(){

			this.params.currentUserModels.on({
				change:(e)=>{
					var selected = e.target;
					var value = selected.value;
					var label = $(selected).data('label');
					var name = $(selected).data('name');

					var sel = `.widget-user-privileges [data-id=${value}]`;
					var rol_sel = `#users-container .user-item[data-id=${this.params.currentUserView.data('id')}] .user-item-privilege`;
					var item = this.params.currentUserView.find(sel);
					var rowItem = $(rol_sel);
					var item2 = rowItem.find(`[data-label=${label}]`);

					// on ajoute
					if(selected.checked){
						if(item.length) return;

						var model = {
							name:name,
							id:value,
							label:label,
						};

						var role = this.render(this.params.$tpl.currentUserRole,model);	
						this.params.currentUserView.find('.widget-user-privileges').append($(role.trim())).app;

						if(!item2.length){
							model.name = label;
							var role = this.render(this.params.$tpl.currentUserRole,model);	
							rowItem.append($(role.trim()));
						}
						

						this.emit(new nsp.UserGrantRoleEvent({role_id:model.id}));
					}
					// on supprime
					else{
						var model = {
							name:item.text(),
							id:value
						};
						item.remove();
						item2.remove();
						this.emit(new nsp.UserRevokeRoleEvent({role_id:model.id}));
					}
				}
			});

			return this;
		}

		UserView.prototype.renderCurrentUser = function(model){
			this.params.currentUserView.attr('data-id',model.id);

			// les roles
			var roles = this.render(this.params.$tpl.currentUserRoles,model,{
				role:this.params.$tpl.currentUserRole
			});				
			this.params.currentUserView.find('.widget-user-privileges').html(roles);

			// l'entete
			var header = this.render(this.params.$tpl.currentUserHeader,model);	
			this.params.currentUserView.find('.widget-user-header').html(header);


			for(var item of model.privileges){
				this.params.currentUserModels.each(function(i,el){
					var obj = $(el);
					if(obj.attr('value') == item.id){
						obj.prop('checked',true);
					}
				});
			}
		}

		return UserView;
	})();

})(AdminManager);