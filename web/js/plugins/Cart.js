var AdminManager = AdminManager || {};

(function(nsp){

	nsp.plugins.Cart = (function(){
		function Cart(params){
			nsp.View.call(this,params);
            this.bag = [];

	       this.vars({
                entries:".catalog-cart-entry",
                container:"#cart"
            });
		};

		Object.assign(Cart.prototype, nsp.View.prototype);

        Cart.prototype.init = function(params){
            this.vars(params);
            this.controller();
            return this;
        }

		Cart.prototype.controller = function(){

            var container = $(this.params.container);

            container.on({
                dragenter:e=>{
                    $(document.body).addClass('cart-dragenter');
                    e.preventDefault();
                },
                dragover:e=>{
                    $(document.body).addClass('cart-dragenter');
                    e.preventDefault();
                },
                dragleave:e=>{
                    e.preventDefault();
                    $(document.body).removeClass('cart-dragenter');
                },
                dragend:e=>{
                    e.preventDefault();
                    $(document.body).removeClass('cart-dragenter');
                },
                drop:e=>{
                    e.preventDefault();
                    $(document.body).removeClass('cart-dragenter');              
                }
            });


            document.addEventListener("dragenter",e=>{
                e.preventDefault();
                if(e.currentTarget == document){
                    $(document.body).addClass('cart-dragenter');
                }
            });
            document.addEventListener("dragover",e=>{
                e.preventDefault();
            });
            document.addEventListener("dragleave",e=>{
                e.preventDefault();
                var obj = $(e.target);

                if(!$(document).has(obj).length && !container.has(obj).length && container.get()[0] != obj.get()[0] ) {
                    console.log('on quite')
                    $(document.body).removeClass('cart-dragenter');
                }
            });
            document.addEventListener("dragend",e=>{
                e.preventDefault();
                $(document.body).removeClass('cart-dragenter');
            });
           
            $("body").on("dragstart",this.params.entries,e=>{
                var obj = $(e.target);
                var evt = e.originalEvent;
                evt.dataTransfer.setData("text/plain",null);
                console.log("dragstart")
            });

            $("body").on("dragend",this.params.entries,e=>{
                var evt = e.originalEvent;
                e.preventDefault();
                $(document.body).removeClass('cart-dragenter');
                console.log(evt.dataTransfer.getData("text/plain"))
                e.stopPropagation();
            });


            this.emit(new nsp.Event('ready',[568]));

			return this;
		}

		return Cart;
	})();

})(AdminManager);