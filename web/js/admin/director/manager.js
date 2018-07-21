var AdminManager = AdminManager || {};

(function(nsp){

	/**
	* evenement lorsqu'on lance la mise a jour
	*/
	nsp.DirectorUpdatingEvent = (function(){
		function DirectorUpdatingEvent(params){
			nsp.Event.call(this,'update',params);
		};
		Object.assign(DirectorUpdatingEvent.prototype, nsp.Event.prototype);
		return DirectorUpdatingEvent;
	})();

	/**
	* evenement lorsqu'on lance la suppression
	*/
	nsp.DirectorDeletingEvent = (function(){
		function DirectorDeletingEvent(params){
			nsp.Event.call(this,'delete',params);
		};
		Object.assign(DirectorDeletingEvent.prototype, nsp.Event.prototype);
		return DirectorDeletingEvent;
	})();

	/**
	* evenement lorsqu'on lance l'insertion d'un pays
	*/
	nsp.CountryInsertEvent = (function(){
		function CountryInsertEvent(params){
			nsp.Event.call(this,'insert',params);
		};
		Object.assign(CountryInsertEvent.prototype, nsp.Event.prototype);
		return CountryInsertEvent;
	})();

	/**
	* evenement lorsqu'on lance la suppression d'un pays
	*/
	nsp.CountryDeleteEvent = (function(){
		function CountryDeleteEvent(params){
			nsp.Event.call(this,'delete',params);
		};
		Object.assign(CountryDeleteEvent.prototype, nsp.Event.prototype);
		return CountryDeleteEvent;
	})();

	nsp.fn.DirectorRepository = (function(){

		function DirectorRepository(params){
			nsp.Repository.call(this,params);
		};

		Object.assign(DirectorRepository.prototype, nsp.Repository.prototype);

		DirectorRepository.prototype.customRequest = function(event){

			return new Promise((resolve,reject)=>{
	  			this.request({
	  				url:`/admin/directors/${event.type}/${this.current.id}`,
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

		DirectorRepository.prototype.customCountryRequest = function(event){

			return new Promise((resolve,reject)=>{
	  			this.request({
	  				url:`/admin/directors/${this.current.id}/country/${event.type}`,
	  				method:"POST",
	  				data:{country_id:event.params.model.id}
		  		})
		  		.done(data=>{
		  			resolve(data);
		  		})
		  		.fail(msg=>{
		  			reject(msg);
		  		});
	  		});
		};

		DirectorRepository.prototype.uploadImage = function(file){
			var formData = new FormData();
			formData.append('file',file,file.getName());

			
			return new Promise((resolve,reject)=>{
	  			this.request({
	  				enctype: 'multipart/form-data',
	  				url:`/admin/directors/${this.current.id}/image/${event.type}`,
	  				method:"POST",
	  				data:formData
		  		})
		  		.done(data=>{
		  			resolve(data);
		  		})
		  		.fail(msg=>{
		  			reject(msg);
		  		});
	  		});
		};

		return DirectorRepository;
	})();


	nsp.fn.DirectorView = (function(){
		function DirectorView(params){
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
					`,
					countries:`
						{{#countries}}
							{{> country}}
						{{/countries}}
					`,
					country:`
						<span  style="padding:5px;margin-right:5px" data-name="{{ name }}" data-id="{{ id }}" data-name="{{ name }}" class="badge bg-aqua text-white"> 
							{{ name }} 

							<input type="hidden" name="pays[]" value="{{ id }}" />

							<a data-title="supprimer" data-toggle="tooltip" href="" style="margin-left: 10px;color:#fff"><i class="fa fa-times"></i></a>
						</span>
					`
				}
			});
		};

		Object.assign(DirectorView.prototype, nsp.View.prototype);

		DirectorView.prototype.controller = function(){
			this.params.selectedDataView.find('#area-persist button[type=reset]').on({
				click:e=>{
					e.preventDefault();
					//this.params.rightSection.removeClass('data-active');
					$(e.target).parents(".box").removeClass('action-update-active');
				}
			});

			this.params.selectedDataView.find('#area-action button[type=button]:first').on({
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

					this.emit(new nsp.DirectorDeletingEvent({
						state:'start',
						model:data
					}));
				}
			});

			// le model pour modification du pays d'origine de l'element en cours
			var currentUserCmodel = $("#data-secondary-box select").clone();
			currentUserCmodel.removeAttr("multiple");
			currentUserCmodel.removeAttr("name");
			currentUserCmodel.addClass("input-sm");
			$("#current-user-cmodel").html(currentUserCmodel);
			currentUserCmodel.on({
				change:e=>{

					this.params.selectedDataView.addClass('updating');

					var selected = $(e.target);
					var value = e.target.value;
					var opt = selected.find(`*[value=${value}]`);

					var model = {
						id:value,
						name:opt.html(),
					};
					var item = this.params.selectedDataView.find(`#countries *[data-id=${model.id}]`);

					if(!item.length){
						var country = $(this.render(this.params.$tpl.country,model));
						this.applyRemoveCountryEvent(country);
						this.params.selectedDataView.find("#countries").append(country);

						this.emit(new nsp.CountryInsertEvent({
							state:'start',
							model:model
						}));
					}
				}
			});

			this.params.selectedDataView.find('form .box-footer #area-action button.update').on({
				click:e=>{
					$(e.target).parents(".box").addClass('action-update-active');
				}
			});

			this.params.selectedDataView.find('form').on({
				submit:e=>{
					e.preventDefault();
					this.params.selectedDataView.addClass('updating');
					var data = $(e.target).serialize();

					this.emit(new nsp.DirectorUpdatingEvent({
						state:'start',
						model:data
					}));
				}
			});

			// on ecoute les evenements
			this.subscribe(event=>{
				if(event instanceof nsp.DirectorUpdatingEvent){
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
							}
							
							alertShow();
						}
					}
				}
				else if(event instanceof nsp.DirectorDeletingEvent){
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
				else if(event instanceof nsp.CountryInsertEvent || event instanceof nsp.CountryDeleteEvent){
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

							alertShow();
						}
					}
				}
				else if(event instanceof nsp.UploadEvent){
					if(event.params.state == "start"){
						this.params.selectedDataView.addClass('updating');
						this.uploadImage(file)
				    	.then(data=>{

				    		this.emit(new nsp.UploadEvent({
								state:'end',
								data:data
							}));

				    	},msg=>{
				    		this.emit(new nsp.UploadEvent({
								state:'fails',
							}));
				    	});
					}
					else{
				    	this.params.selectedDataView.removeClass('updating');
					}
				}

			});

			var dropper = document.getElementById("current-widget-data");

			dropper.addEventListener("dragenter",e=>{
				this.params.selectedDataView.addClass('dragenter');
				e.preventDefault();
			});

			dropper.addEventListener("dragover",e=>{
				this.params.selectedDataView.addClass('dragenter');
				e.preventDefault();
			});
			
			dropper.addEventListener("dragleave",e=>{
				e.preventDefault();
				this.params.selectedDataView.removeClass('dragenter');
			});

			dropper.addEventListener("drop",e=>{
				e.preventDefault();
				this.params.selectedDataView.removeClass('dragenter');

				var file = e.dataTransfer.files[0];
		        var filenames = file.name;

			    var reader = new FileReader();

			    reader.addEventListener('load', ()=> {
			    	this.params.selectedDataView.find('img:first').attr('src',reader.result);

			    	this.emit(new nsp.UploadEvent({
						state:'start',
						file:file
					}));
			    });

			    reader.readAsDataURL(file);
			});

			

			$(document.body).on({
				dragend:e=>{
					
				}
			});



			return this;
		}

		DirectorView.prototype.renderSelectedData = function(model){
			this.params.selectedDataView.attr('data-id',model.id);
			this.params.selectedDataView.find("#name").val(model.name);
			this.params.selectedDataView.find("#description").val(model.description);
			this.params.selectedDataView.find("#aboutme").html(model.description);
			this.params.selectedDataView.find(".widget-user-username").html(model.name);
			this.params.selectedDataView.find("#countries").html('');

			if(model.hasOwnProperty('countries')){
				model.countries = model.countries.map(function(el){
					el.id = el.slug;
					return el;
				})
				var countries = this.render(this.params.$tpl.countries,model,{
					country:this.params.$tpl.country
				});

				countries = $(countries);
				this.applyRemoveCountryEvent(countries);
				this.params.selectedDataView.find("#countries").html(countries);
			}
			
		}

		DirectorView.prototype.applyRemoveCountryEvent = function(elts){
			elts.each((i,el)=>{
				var obj = $(el);
				$(el).find("a").on({
					click:e=>{

						this.params.selectedDataView.addClass('updating');

						e.preventDefault();
						obj.remove();
						var model = {
							id:obj.data('id'),
							name:obj.data('name'),
						};

						this.emit(new nsp.CountryDeleteEvent({
							state:'start',
							model:model
						}));
					}
				});
			});
		}

		return DirectorView;
	})();

})(AdminManager);