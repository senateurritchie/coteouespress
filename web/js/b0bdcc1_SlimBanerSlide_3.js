var AdminManager = AdminManager || {};

(function(nsp){

    nsp.plugins.SlimBanerSlide = (function(){
        function SlimBanerSlide(params){
            this.timerId = null;
           
            nsp.View.call(this,params);
            this.bag = [];
            this.currentDragged = null;
            this.jqContainer = null;

            this.vars({
                container:null,
                data:[],
                delay:5000,
                current:0,
            });

            this.baseUrl = null;
        };

        Object.assign(SlimBanerSlide.prototype, nsp.View.prototype);

        SlimBanerSlide.prototype.init = function(params){
            this.vars(params);
            this.controller();
            return this;
        }

        SlimBanerSlide.prototype.stop = function(){
            clearTimeout(this.timerId);
        };

        SlimBanerSlide.prototype.move = function(){

            if(this.params.current == 0){
                this.emit(new nsp.Event('FIRST'));
            }

            if(this.params.current >= this.params.data.length){
                this.params.current = 0;
                this.emit(new nsp.Event('LAST'));
            }
            
            this.emit(new nsp.Event('INDEX',{index:this.params.current}));
            

            let url = this.baseUrl.split("/");
            url.splice(-1,1);
            url.push(this.params.data[this.params.current]);
            url = url.join('/');


            this.timerId = setTimeout(()=>{
                this.jqContainer.fadeOut(1000,()=>{
                    this.jqContainer.css('background-image',`url("${url}")`)

                    this.jqContainer.fadeIn(500,()=>{
                        this.params.current++;
                        this.move();
                    });
                });
            },this.params.delay);
        };

        SlimBanerSlide.prototype.controller = function(){

            this.jqContainer = $(this.params.container);

            // initialisation des evenements
            this.subscribe(evt=>{
                if(!(evt instanceof nsp.Event)) return;

                switch(evt.type){
                    case "initialized":
                        
                    break;
                }
            });

            var regUrl = /url\("(.+)"\)/ig;
            var baseUrl = null;

            if(regUrl.test(this.jqContainer.css('background-image'))){
                this.baseUrl = RegExp.$1;
                this.move();
            }

            this.emit(new nsp.Event('ready'));

            return this;
        }

        return SlimBanerSlide;
    })();

})(AdminManager);