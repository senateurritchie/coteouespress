$(document).ready(function($){
    var nsp = AdminManager;
    var repository = AdminManager.container.get('CatalogRepository');
    var view = AdminManager.container.get('CatalogView');
    view.controller();

    view.subscribe(event=>{

        if(event instanceof nsp.InfiniteScrollEvent){
            if(event.params.state != "start") return;

            var limit = event.params.data.limit;
            var offset = event.params.data.offset;
            repository.findBy({
                headers:{
                    accept:"text/html",
                },
                dataType:'text',
            },{},limit,offset)
            .then(data=>{

                view.emit(new nsp.InfiniteScrollEvent({
                    state:'end',
                    data:data
                }));

            },msg=>{
                view.emit(new nsp.InfiniteScrollEvent({
                    state:'fails',
                }));
            });
        }
    });
});