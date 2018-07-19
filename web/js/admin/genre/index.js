$(document).ready(function($){
	var nsp = AdminManager;
	var repository = AdminManager.container.get('GenreRepository');
	var view = AdminManager.container.get('GenreView');
	view.controller();

	var rightSection = $("#right-section");

	view.subscribe(event=>{
		if((event instanceof nsp.GenreUpdatingEvent) || (event instanceof nsp.GenreDeletingEvent)) {
			if(event.params.state != "start") return;
			
			repository.customRequest(event)
			.then(data=>{

				if(event instanceof nsp.GenreUpdatingEvent){
					view.emit(new nsp.GenreUpdatingEvent({state:'end',data:data}));
				}
				else if(event instanceof nsp.GenreDeletingEvent){
					view.emit(new nsp.GenreDeletingEvent({state:'end',data:data}));
				}
			},msg=>{
				if(event instanceof nsp.GenreUpdatingEvent){
					view.emit(new nsp.GenreUpdatingEvent({state:'fails'}));
				}
				else if(event instanceof nsp.GenreDeletingEvent){
					view.emit(new nsp.GenreDeletingEvent({state:'fails'}));
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