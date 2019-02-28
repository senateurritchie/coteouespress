$(document).ready(function($){
    var nsp = AdminManager;
    var view = AdminManager.container.get('MovieSingleView');
    var repository = AdminManager.container.get('MovieSingleRepository');

    view.controller();

   
    repository.subscribe(event=>{

		if(event instanceof nsp.DownloadMovieListEvent){
			if(event.params.state != "start") return;

			
		}
		
	});


	view.subscribe(event=>{

    	if(event instanceof nsp.DownloadMovieListEvent){
			if(event.params.state == "start"){
				repository.downloadListRequest(event)
				.then(data=>{

					view.emit(new nsp.DownloadMovieListEvent({
						state:'end',
						data:data
					}));

				},msg=>{
					view.emit(new nsp.DownloadMovieListEvent({
						state:'fails',
					}));
				});
			}
		}
        
    });

});