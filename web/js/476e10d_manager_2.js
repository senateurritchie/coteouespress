var AdminManager = AdminManager || {};

(function(nsp){

	/**
	* evenement lorsqu'on supprime un role
	*/
	nsp.DepartmentDeleteEvent = (function(){
		function DepartmentDeleteEvent(params){
			nsp.Event.call(this,'delete',params);
		};
		Object.assign(DepartmentDeleteEvent.prototype, nsp.Event.prototype);
		return DepartmentDeleteEvent;
	})();

	/**
	* evenement lorsqu'on ajoute un role
	*/
	nsp.DepartmentInsertEvent = (function(){
		function DepartmentInsertEvent(params){
			nsp.Event.call(this,'insert',params);
		};
		Object.assign(DepartmentInsertEvent.prototype, nsp.Event.prototype);
		return DepartmentInsertEvent;
	})();

	/**
	* evenement lorsqu'on modifie
	*/
	nsp.DepartmentUpdateEvent = (function(){
		function DepartmentUpdateEvent(params){
			nsp.Event.call(this,'insert',params);
		};
		Object.assign(DepartmentUpdateEvent.prototype, nsp.Event.prototype);
		return DepartmentUpdateEvent;
	})();

	/**
	* evenement lorsqu'on lance la mise a jour
	*/
	nsp.DepartmentUpdatingEvent = (function(){
		function DepartmentUpdatingEvent(params){
			nsp.Event.call(this,'update',params);
		};
		Object.assign(DepartmentUpdatingEvent.prototype, nsp.Event.prototype);
		return DepartmentUpdatingEvent;
	})();

	/**
	* evenement lorsqu'on lance la suppression
	*/
	nsp.DepartmentDeletingEvent = (function(){
		function DepartmentDeletingEvent(params){
			nsp.Event.call(this,'delete',params);
		};
		Object.assign(DepartmentDeletingEvent.prototype, nsp.Event.prototype);
		return DepartmentDeletingEvent;
	})();

	nsp.fn.DepartmentRepository = (function(){

		function DepartmentRepository(params){
			nsp.Repository.call(this,params);
		};

		Object.assign(DepartmentRepository.prototype, nsp.Repository.prototype);

		DepartmentRepository.prototype.customRequest = function(event){

			return new Promise((resolve,reject)=>{
	  			this.request({
	  				url:`/admin/department/${event.type}/${this.current.id}`,
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

		return DepartmentRepository;
	})();


	nsp.fn.DepartmentView = (function(){
		function DepartmentView(params){
			nsp.View.call(this,params);

			this.vars({
				selectedDataView:$("#current-widget-data"),
				rightSection:$("#right-section"),
				model:{
					name:$("#current-widget-data #name"),
					email:$("#current-widget-data #email"),
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

		Object.assign(DepartmentView.prototype, nsp.View.prototype);

		DepartmentView.prototype.controller = function(){
			this.params.selectedDataView.find('button[type=reset]').on({
				click:e=>{
					this.params.rightSection.removeClass('data-active');
				}
			});

			$('#myModal').on('shown.bs.modal', function () {
  				$('#myModal button').removeAttr('disabled');
			});


			$('#myModal button[type=submit]').on({
				click:e=>{
					e.preventDefault();
					$('#myModal button').attr('disabled','disabled');

					this.params.selectedDataView.addClass('updating');
					var data = $(e.target).serialize();

					this.emit(new nsp.DepartmentDeletingEvent({
						state:'start',
						model:data
					}));
				}
			})

			this.params.selectedDataView.find('form').on({
				submit:e=>{
					e.preventDefault();
					this.params.selectedDataView.addClass('updating');
					var data = $(e.target).serialize();

					this.emit(new nsp.DepartmentUpdatingEvent({
						state:'start',
						model:data
					}));
				}
			});

			// on ecoute les evenements
			this.subscribe(event=>{
				if(event instanceof nsp.DepartmentUpdatingEvent){
					if(~['end','fails'].indexOf(event.params.state)){
						this.params.selectedDataView.removeClass('updating');

						if(event.params.state == 'end'){
							var data = event.params.data;

							var alertShow = ()=>{
								if(data.hasOwnProperty('message')){
									$('#modal-info .modal-body h4').html(data.message);
									$('#modal-info').modal('show');
								}
								else if(data.hasOwnProperty('errors')){
									var tpl = this.render(this.params.$tpl.errors,data);
									$('#modal-info .modal-body h4').html(tpl);
									$('#modal-info').modal('show');
								}
							};

							if(data.status){
								var rol_sel = `#data-container .data-item[data-id=${this.params.selectedDataView.data('id')}]`;
								var rowItem = $(rol_sel);

								rowItem.find('.data-item-name').html(data.data.name);
								rowItem.find('.data-item-email').html(data.data.email);
							}
							
							alertShow();
						}
					}
				}
				else if(event instanceof nsp.DepartmentDeletingEvent){
					if(~['end','fails'].indexOf(event.params.state)){
						this.params.selectedDataView.removeClass('updating');

						if(event.params.state == 'end'){
							var data = event.params.data;

							var alertShow = ()=>{
								if(data.hasOwnProperty('message')){
									$('#modal-info .modal-body h4').html(data.message);
									$('#modal-info').modal('show');
								}
								else if(data.hasOwnProperty('errors')){
									var tpl = this.render(this.params.$tpl.errors,data);
									$('#modal-info .modal-body h4').html(tpl);
									$('#modal-info').modal('show');
								}
								$('#myModal').off('hidden.bs.modal');
							};

							if(data.status){
								var rol_sel = `#data-container .data-item[data-id=${this.params.selectedDataView.data('id')}]`;
								var rowItem = $(rol_sel);
								rowItem.remove();

								
								$('#myModal').on('hidden.bs.modal', function () {
					  				alertShow();
								});

								$('#myModal').modal('hide');
								
								this.params.rightSection.removeClass('data-active');
							}
							else{
								alertShow();
							}
						}
					}
				}

			});

			return this;
		}

		DepartmentView.prototype.renderSelectedData = function(model){
			this.params.selectedDataView.attr('data-id',model.id);
			this.params.selectedDataView.find("#name").val(model.name);
			this.params.selectedDataView.find("#email").val(model.email);
		}

		return DepartmentView;
	})();

})(AdminManager);