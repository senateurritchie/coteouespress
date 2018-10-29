var AdminManager = AdminManager || {};

(function(nsp){

	nsp.plugins.Cart = (function(){
		function Cart(params){
			nsp.View.call(this,params);
            this.bag = [];
            this.currentDragged = null;

	       this.vars({
                entries:".cart-entry",
                container:"#cart",
                storageKey:"catalog-cart",
                audio:null
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

            var audio = document.createElement('audio');
            audio.addEventListener('canplay',(e)=>{
                this.vars({audio:audio})
            },false)

            audio.src = '/sounds/cart_entry_new.mp3';

            var body = $("body");

            // initialisation des evenements
            this.subscribe(evt=>{
                if(!(evt instanceof nsp.Event)) return;

                switch(evt.type){
                    case "initialized":
                        container.addClass('ready');
                    break;
                }
            });

            

            container.find(".cart-open-list").on('click',e=>{
                e.preventDefault();
                container.toggleClass('open');
            });

            container.on({
                drop:e=>{
                    e.preventDefault();
                    body.removeClass('cart-dragenter');
                    var isExists = false;
                    var tr = this.currentDragged.parents('.data-item').clone();

                    for(let i in this.bag){
                        var item = this.bag[i];
                        if(item.id == tr.data('id')){
                            isExists = true;
                            break;
                        }
                    }

                    if(!isExists){
                        
                        var src = tr.find('.data-item-image img').attr('src');
                        var name = tr.find('.data-item-name').text();
                        var id = tr.attr('data-id');
                        var entry = {name:name,id:id,image:src};
                        this.render([entry]);
                        this.emit(new nsp.Event('insert',{model:entry}));

                        if(this.params.audio)
                            audio.play();
                    }
                }
            });


            document.addEventListener("dragenter",e=>{
                e.preventDefault();
                if(this.currentDragged && e.currentTarget == document){
                    body.addClass('cart-dragenter');
                }
            });

            document.addEventListener("drop",e=>{
                e.preventDefault();
            });


            body.on("dragstart",this.params.entries,e=>{
                var obj = $(e.currentTarget);
                var evt = e.originalEvent;
                evt.dataTransfer.setData("text/plain",obj.attr('href'));
                this.currentDragged = obj;
            });

            body.on("dragend",this.params.entries,e=>{
                this.currentDragged = null;
                var evt = e.originalEvent;
                body.removeClass('cart-dragenter');
                e.stopPropagation();
            });

            this.emit(new nsp.Event('ready',[568]));

			return this;
		}

        Cart.prototype.render = function(data,from){

            if(!(data instanceof Array)) return;

            var container = $(this.params.container);
            var counterDiv = container.find(".info-box-number");
            var tableContainer = container.find('.cart-list table');


            for(let i in data){

                let entry = data[i];
                let entry_pos = this.bag.length;

                var counter = parseInt(counterDiv.text())+1;
                counterDiv.text(counter);

                let item = $(`
                    <tr data-id="${entry.id}">
                        <td width="10" >
                            <a href="">
                                <i class="fa fa-times"></i>
                            </a>
                        </td>

                        <td width="40" valign="top">
                            <img width="30" height="30" src="${entry.image}" alt="" />
                        </td>

                        <td valign="top">
                            ${entry.name}
                        </td>
                    </tr>`);

                item.find('a').on('click',e=>{
                    e.preventDefault();
                    item.remove();
                    
                    for(let p in this.bag){
                        if(this.bag[p].id == entry.id){
                            this.bag.splice(p,1);
                            break;
                        }
                    }

                    var counter = parseInt(counterDiv.text())-1;
                    counterDiv.text(counter);
                    this.emit(new nsp.Event('remove',{model:entry}));
                    this.saveStorage();
                    
                });

                this.bag.push(entry);
                tableContainer.append(item);
            }
            this.saveStorage();
            return this;
        }

        Cart.prototype.saveStorage = function(){
            var ids = this.bag.map(e=>{ return e.id; });
            var container = $(this.params.container);
            var a = container.find('.cart-export-list');
            var link = a.attr('href').split("?");
            var path = link[0];
            var query = link.length > 1 ? link[1] : null;
            var queryObj = {id:ids};

            if(query){
                query = query.split("&");

                for(let i in query){
                    let item = query[i].split('=');
                    let key = item[0].trim();
                    let val = null;
                    if(item.length > 1){
                        val = item[1].trim();
                        if(val){
                            queryObj[key] = val;
                        }
                    }
                }
            }

          
            var querystring = path+"?"+$.param(queryObj);
            a.attr('href',querystring);
            

            localStorage.setItem(this.params.storageKey,JSON.stringify(this.bag));
            return this;
        }

        Cart.prototype.loadStorage = function(){
            var data = [];

            try{
                data = JSON.parse(localStorage.getItem(this.params.storageKey))
            }catch(e){

            }
            return data;
        }

		return Cart;
	})();

})(AdminManager);