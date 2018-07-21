var AdminManager = AdminManager || {};

(function(nsp){

	/**
	* evenement lorsqu'on lance la mise a jour
	*/
	nsp.CountryUpdatingEvent = (function(){
		function CountryUpdatingEvent(params){
			nsp.Event.call(this,'update',params);
		};
		Object.assign(CountryUpdatingEvent.prototype, nsp.Event.prototype);
		return CountryUpdatingEvent;
	})();

	/**
	* evenement lorsqu'on lance la suppression
	*/
	nsp.CountryDeletingEvent = (function(){
		function CountryDeletingEvent(params){
			nsp.Event.call(this,'delete',params);
		};
		Object.assign(CountryDeletingEvent.prototype, nsp.Event.prototype);
		return CountryDeletingEvent;
	})();

	nsp.fn.CountryRepository = (function(){

		function CountryRepository(params){
			nsp.Repository.call(this,params);
		};

		Object.assign(CountryRepository.prototype, nsp.Repository.prototype);

		CountryRepository.prototype.customRequest = function(event){

			return new Promise((resolve,reject)=>{
	  			this.request({
	  				url:`/admin/countries/${event.type}/${this.current.id}`,
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

		return CountryRepository;
	})();


	nsp.fn.CountryView = (function(){
		function CountryView(params){
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
					country:`
						<tr class="data-item" data-id="{{ id }}">
	              			<td class="data-item-name">
	              				{{ name }}
	              			</td>

	              			<td style="text-align:center">
	              				{{ movieNbr }}
	              			</td>

	              			<td style="text-align:center">
	              				{{ actorNbr }}
	              			</td>

	              			<td style="text-align:center">
	              				{{ movieNbr }}
	              			</td>

	              			<td style="text-align:center">
	              				{{ directorNbr }}
	              			</td>

	              			<td style="text-align:center">
	              				{{ producerNbr }}
	              			</td>

	              			<td class="data-item-tools">
	              				<a data-id="{{ id }}" href="" class="edit btn">
	              					<i class="fa fa-edit"></i>
	              				</a>
	              			</td>
	            		</tr>
					`,
					countries:`
						{{#data}}
							{{> country}}
		            	{{/data}}
					`
				}
			});
		};

		Object.assign(CountryView.prototype, nsp.View.prototype);

		CountryView.prototype.controller = function(){

			var repository = nsp.container.get('CountryRepository');

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

					this.emit(new nsp.CountryDeletingEvent({
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

					this.emit(new nsp.CountryUpdatingEvent({
						state:'start',
						model:data
					}));
				}
			});

			// on ecoute les evenements
			this.subscribe(event=>{
				if(event instanceof nsp.CountryUpdatingEvent){
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
				else if(event instanceof nsp.CountryDeletingEvent){
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
				else if(event instanceof nsp.InfiniteScrollEvent){
					if(event.params.state == "start"){
						$(document.body).addClass("infinite-scroll-active");

						var limit = 20;
						var offset = $("#data-container .data-item").length;
						repository.findBy({},{},limit,offset)
						.then(data=>{
							var tpl = this.render(this.params.$tpl.countries,{data:data},{
								country:this.params.$tpl.country
							});

							$("#data-container table:first").append($(tpl));

							$(document.body).removeClass("infinite-scroll-active");
						},msg=>{
							$(document.body).removeClass("infinite-scroll-active");
						});
					}
					else if(event.params.state == "end"){
						$(document.body).removeClass("infinite-scroll-active");
					}
				}

			});


			var scroller = nsp.container.get('Scroller');
			scroller.subscribe(event=>{
				if(event instanceof nsp.ScrollerEvent && event.params.percent <= 20 && event.params.dir == "ttb"){
					if(!$(document.body).hasClass("infinite-scroll-active")){
						this.emit(new nsp.InfiniteScrollEvent({
							state:'start',
							data:event.params
						}));
					}
				}

			});

			scroller.forWindow();

			return this;
		}

		CountryView.prototype.renderSelectedData = function(model){
			this.params.selectedDataView.attr('data-id',model.id);
			this.params.selectedDataView.find("#name").val(model.name);
		}

		return CountryView;
	})();

})(AdminManager);