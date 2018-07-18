var AdminManager = AdminManager || {};

(function(nsp){

	/**
	* evenement lorsqu'on supprime un role
	*/
	nsp.RoleDeleteEvent = (function(){
		function RoleDeleteEvent(params){
			nsp.Event.call(this,'delete',params);
		};
		Object.assign(RoleDeleteEvent.prototype, nsp.Event.prototype);
		return RoleDeleteEvent;
	})();

	/**
	* evenement lorsqu'on ajoute un role
	*/
	nsp.RoleInsertEvent = (function(){
		function RoleInsertEvent(params){
			nsp.Event.call(this,'insert',params);
		};
		Object.assign(RoleInsertEvent.prototype, nsp.Event.prototype);
		return RoleInsertEvent;
	})();

	/**
	* evenement lorsqu'on modifie
	*/
	nsp.RoleUpdateEvent = (function(){
		function RoleUpdateEvent(params){
			nsp.Event.call(this,'insert',params);
		};
		Object.assign(RoleUpdateEvent.prototype, nsp.Event.prototype);
		return RoleUpdateEvent;
	})();

	/**
	* evenement lorsqu'on lance la mise a jour
	*/
	nsp.RoleUpdatingEvent = (function(){
		function RoleUpdatingEvent(params){
			nsp.Event.call(this,'update',params);
		};
		Object.assign(RoleUpdatingEvent.prototype, nsp.Event.prototype);
		return RoleUpdatingEvent;
	})();

	/**
	* evenement lorsqu'on lance la suppression
	*/
	nsp.RoleDeletingEvent = (function(){
		function RoleDeletingEvent(params){
			nsp.Event.call(this,'delete',params);
		};
		Object.assign(RoleDeletingEvent.prototype, nsp.Event.prototype);
		return RoleDeletingEvent;
	})();

	nsp.fn.RoleRepository = (function(){

		function RoleRepository(params){
			nsp.Repository.call(this,params);
		};

		Object.assign(RoleRepository.prototype, nsp.Repository.prototype);


		RoleRepository.prototype.customRequest = function(event){

			return new Promise((resolve,reject)=>{
	  			this.request({
	  				url:`/admin/roles/${event.type}/${this.current.id}`,
	  				method:"POST",
	  				data:event.params.model
		  		})
		  		.done(data=>{
		  			resolve(data);
		  		})
		  		.fail(msg=>{
		  			reject(msg);
		  		});
	  		});
		};

		return RoleRepository;
	})();


	nsp.fn.RoleView = (function(){
		function RoleView(params){
			nsp.View.call(this,params);

			this.vars({
				selectedDataView:$("#current-widget-data"),
				rightSection:$("#right-section"),
				model:{
					name:$("#current-widget-data #name"),
					label:$("#current-widget-data #label"),
					type:$("#current-widget-data .role-type"),
					description:$("#current-widget-data #description"),
				},
				$tpl:{
					errors:`
						<ul>
							{{#errors}}
								<li>{{ . }}</li>
							{{/errors}}
						</ul>
					`
				}
			});
		};

		Object.assign(RoleView.prototype, nsp.View.prototype);

		RoleView.prototype.controller = function(){
			this.params.selectedDataView.find('button[type=reset]').on({
				click:e=>{
					this.params.rightSection.removeClass('data-active');
				}
			});

			this.params.selectedDataView.find('form').on({
				submit:e=>{
					e.preventDefault();
					this.params.selectedDataView.addClass('updating');
					var data = $(e.target).serialize();

					this.emit(new nsp.RoleUpdatingEvent({
						state:'start',
						model:data
					}));
				}
			});

			// on ecoute les evenements
			this.subscribe(event=>{
				if(event instanceof nsp.RoleUpdatingEvent){
					if(~['end','fails'].indexOf(event.params.state)){
						this.params.selectedDataView.removeClass('updating');

						if(event.params.state == 'end'){
							var data = event.params.data;
							console.log(data)

							if(data.status){
								var rol_sel = `#data-container .data-item[data-id=${this.params.selectedDataView.data('id')}]`;
								var rowItem = $(rol_sel);

								rowItem.find('.data-item-name').html(data.data.name);
								rowItem.find('.data-item-label .label').html(data.data.label);
								rowItem.find('.data-item-description').html(data.data.description);
								rowItem.find('.data-item-type .label').html(data.data.type);

								var mAlert = this.params.selectedDataView.find("#alert-success");

								mAlert.find(".message").html(data.message);
								mAlert.fadeIn(500);
								setTimeout(function(){
									mAlert.fadeOut(500);
								},5000);
							}
							
							if(data.hasOwnProperty('errors')){
								var mAlert = this.params.selectedDataView.find("#alert-error");
								
								var tpl = this.render(this.params.$tpl.errors,data);	
								mAlert.find(".message").html(tpl);
								mAlert.fadeIn(500);

								setTimeout(function(){
									mAlert.fadeOut(500);
								},5000);
							}
						}
					}
				}
			});

			return this;
		}

		RoleView.prototype.renderSelectedData = function(model){
			this.params.selectedDataView.attr('data-id',model.id);
			this.params.selectedDataView.find("#name").val(model.name);
			this.params.selectedDataView.find("#label").val(model.label);
			this.params.selectedDataView.find("#description").val(model.description);
			this.params.selectedDataView.find(".role-type[value="+model.type+"]").prop("checked",true);

			//var preview = this.render(this.params.$tpl.preview,model);			
			//this.params.selectedDataView.find('.preview').html(preview);*/
		}

		return RoleView;
	})();

})(AdminManager);