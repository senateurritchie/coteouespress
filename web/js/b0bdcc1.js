$(document).ready(function($){

	let mainMenu = $("#master-menu");
	let timerId;
	let menuHook = function(){
		$("body").removeClass('sleep');

		clearTimeout(timerId);
		timerId = setTimeout(function(){
			$("body").addClass('sleep');
		},5000);
	};

	$("body").on('mousemove wheel',e=>{
		menuHook();
	});

	$("#header-mobile-menu").on({
		click:function(e){
			e.preventDefault();
			$("#master-menu-mobile").toggleClass('active');
		}
	});

	/*$('.nav-contacts').click(function(e){
		e.preventDefault();

		$("html, body").animate({ scrollTop: $("#contacts").offset().top }, 1000);
	});*/

	/*$("header#header #search input").on({
		focus:function(e){
			$(this).parent().prev().removeClass('d-md-block');
			//$(this).parent().next().hide();
			//$(this).attr('data-width',$(this).innerWidth());
			//$(this).animate({width:'200px'},100);
		},
		blur:function(e){
			$(this).parent().prev().addClass('d-md-block');
			//$(this).parent().next().show();
			//$(this).animate({width:'100px'},200);
		}
	});*/
});
var AdminManager = AdminManager || {};

(function(nsp){

	// namespace initial de l'espace d'administration
	nsp.container;
	nsp.fn = {};
	nsp.plugins = {};
	nsp.utilis = {
	  	merge:function(target={},source={}){
	   		for(let i in source){
	    		target[i] = source[i];
	   		}
	   		return target;
	  	}
	};

	nsp.initialize = function(){

		nsp.container.set('EntityManager',nsp.EntityManager);
		nsp.container.set('EventDispatcher',nsp.EventDispatcher);
		nsp.container.set('Scroller',nsp.Scroller);

		for(var i in nsp.fn){
			nsp.container.set(i,nsp.fn[i]);
		}
	}

	/**
	* Base Objet des events 
	* @return {Event}              
	*/
	nsp.Event = (function(){
		function Event(type,params){
	   		this.type = type;
	   		this.params = {};
	   		this.isPropagationStoped = false;
	   		nsp.utilis.merge(this.params,params);
		};

	  	/**
	  	* permet de stopper la propagation d'un événement
	  	*
	  	* @return {null}
	   	*/
	  	Event.prototype.stopPropagation = function(){
	   		this.isPropagationStoped = true;
	  	};

	  	return Event;
	 })();

	/**
	* EventDispatcherSubscriber est le soucripteur d'evenement
	* @return {EventDispatcherSubscriber}              
	*/
	nsp.EventDispatcherSubscriber = (function(){
		function EventDispatcherSubscriber(dispatcher){
	   		this.dispatcher = dispatcher;
	  	};

	  	/**
	   	* annulation de souscription au gestionnaire d'évenements
	   	* 
	   	* @return {null}
	   	*/
	  	EventDispatcherSubscriber.prototype.unsubscribe = function(){
	   		this.dispatcher.remove(this);
	  	};
	  
	  	return EventDispatcherSubscriber;
	})();

	/**
	* EventDispatcher est le gestionnaire d'evenement
	* @return {EventDispatcher}              
	*/
	nsp.EventDispatcher = (function(){
	  	function EventDispatcher(){
	   		this.$_data = new Map();
	  	};

	  	/**
	    * souscription au gestionnaire d'évenements
	    *
	    * @param  {Function} cbk callback à appeler à chaque nouvel évenement
	    * @return {null}
	   	*/
	  	EventDispatcher.prototype.subscribe = function(cbk){
	   		var subscriber = new nsp.EventDispatcherSubscriber(this);
	   		this.$_data.set(subscriber,cbk);
	   		return subscriber;
	  	};
	  
	  	/**
	    * supprime un souscripteur d'évenement
	    *
	    * @param  {EventDispatcherSubscriber} subscriber
	    * @return {null}         
	   	*/
	  	EventDispatcher.prototype.remove = function(subscriber){
	   		this.$_data.delete(subscriber);
	  	};

	  	/**
	    * emetteur d'évenements
	    *
	    * @param  {Event} event
	    * @return {null}         
	   	*/
	  	EventDispatcher.prototype.emit = function(event){
	   		for(let [i,cbk] of this.$_data){
	    		cbk.call(null,event);
	    		if(event.isPropagationStoped) break;
	   		}
	  	};
	  	return EventDispatcher;
	})();


	/**
	* EntityManager 
	* @return {EntityManager}              
	*/
	nsp.EntityManager = (function(){

		function EntityManager(params){
			nsp.EventDispatcher.call(this);

	   		this.params = {
	   			endpoint:null
	   		};
	   		this.xhr;
	   		nsp.utilis.merge(this.params,params);
		};

		Object.assign(EntityManager.prototype,nsp.EventDispatcher.prototype);

	  	/**
	  	* permet d'initialiser les repositories
	  	*
	  	* @return {null}
	   	*/
	  	EntityManager.prototype.init = function(params){
	  		nsp.utilis.merge(this.params,params);
	  	};

	  
	  	EntityManager.prototype.persist = function(params){

	  	};

	  	EntityManager.prototype.remove = function(params){

	  	};

	  	EntityManager.prototype.request = function(options){
	  		options = options || {};

	  		var params = {
	  			url: this.endpoint,
	  			dataType:'json',
	  			method:'POST',
	  			success:function(data){
					resolve(data);
				},
				error:function(xhr,textStatus,errorThrown){
					reject(textStatus);
				}
	  		};

	  		for(var i in options){
	  			if(typeof params[i] == "function") continue;
	  			params[i] = options[i];
	  		}

	  		return $.ajax(params);
		};

	  	return EntityManager;
	})();


	/**
	* Base Objet les repositories 
	* @return {AbstractRepository}              
	*/
	nsp.AbstractRepository = (function(){

		function AbstractRepository(type,params){
			nsp.EventDispatcher.call(this);

	   		this.params = {
	   			endpoint:null
	   		};
	   		this.current;
	   		this.xhr;
	   		nsp.utilis.merge(this.params,params);
		};

		Object.assign(AbstractRepository.prototype,nsp.EventDispatcher.prototype);

	  	/**
	  	* permet d'initialiser les repositories
	  	*
	  	* @return {null}
	   	*/
	  	AbstractRepository.prototype.init = function(params){
	  		nsp.utilis.merge(this.params,params);
	  	};


	  	AbstractRepository.prototype.setCurrent = function(user){
	  		this.current = user;
	  	};

	  	/**	
	  	* les methodes abstraites
	  	*/
	  	AbstractRepository.prototype.find = function(id){};
	  	AbstractRepository.prototype.findAll = function(){};
	  	AbstractRepository.prototype.findBy = function(params,orderBy,limit,offset){};
	  	AbstractRepository.prototype.findOneBy = function(params){};

	  	/**
	  	* les methodes public
	  	*/
	  	AbstractRepository.prototype.request = function(options){
	  		options = options || {};

	  		var params = {
	  			url: this.endpoint,
	  			dataType:'json',
	  			method:'GET',
	  			
	  		};

	  		for(var i in options){
	  			params[i] = options[i];
	  		}

	  		return $.ajax(params);
		};

	  	return AbstractRepository;
	})();

	nsp.Repository = (function(){

		function Repository(params){
			nsp.AbstractRepository.call(this,params);
		};

		Object.assign(Repository.prototype, nsp.AbstractRepository.prototype);

		Repository.prototype.find = function(id){

			return this.findBy({
				data:{
					id:id
				}
			});
		};
	  	Repository.prototype.findAll = function(){
	  		return this.findBy();
	  	};
	  	Repository.prototype.findBy = function(params = {},orderBy = {},limit,offset){
	  		
	  		if(!params.hasOwnProperty('data')){
	  			params.data = {};
	  		}

	  		if(limit){
	  			params["data"].limit = limit;
	  			params["data"].offset = offset;
	  		}

	  		return new Promise((resolve,reject)=>{
	  			this.request(params)
		  		.done(data=>{
		  			resolve(data);
		  		})
		  		.fail(msg=>{
		  			console.log(msg)
		  			reject(msg);
		  		});
	  		});
	  	};
	  	Repository.prototype.findOneBy = function(params){
	  		return this.findBy(params,null,1,0);
	  	};

		return Repository;
	})();


	/**	
	* Base class pour les vues
	*/
	nsp.View = (function(){

		function View(){
			nsp.EventDispatcher.call(this);
			this._data = new Map();
			this.params = {
				$tpl:{}
			};
		};

		Object.assign(View.prototype,nsp.EventDispatcher.prototype);

		View.prototype.vars = function(params){
	  		nsp.utilis.merge(this.params,params);
	  		return this;
	  	};

	  	/**
	  	* methode abstraite
	  	* demarre la logic de la vue
	  	*/
	  	View.prototype.controller = function(){};

		View.prototype.render = function(view,model,options){
			return Mustache.render(view,model,options);
		}
		return View;
	})();

	/**	
	* le container de service
	*/
	nsp.Container = (function(){

		function Container(){
			nsp.EventDispatcher.call(this);
			this._data = new Map();
		};

		Object.assign(Container.prototype,nsp.EventDispatcher.prototype);

		Container.prototype.has = function(key){
			return this._data.has(key);
		}

		Container.prototype.set = function(name,_constructor){
			this._data.set(name,new _constructor());
		}

		Container.prototype.get = function(value){
			return this._data.get(value);
		}

		return Container;
	})();


	/**
	* evenement de filereader
	*/
	nsp.FileReaderEvent = (function(){
		function FileReaderEvent(params){
			nsp.Event.call(this,'upload',params);
		};
		Object.assign(FileReaderEvent.prototype, nsp.Event.prototype);
		return FileReaderEvent;
	})();

	/**
	* evenement de traduction
	*/
	nsp.TranslationEvent = (function(){
		function TranslationEvent(params){
			nsp.Event.call(this,'translate',params);
		};
		Object.assign(TranslationEvent.prototype, nsp.Event.prototype);
		return TranslationEvent;
	})();

	/**
	* evenement de upload
	*/
	nsp.UploadEvent = (function(){
		function UploadEvent(params){
			nsp.Event.call(this,'upload',params);
		};
		Object.assign(UploadEvent.prototype, nsp.Event.prototype);
		return UploadEvent;
	})();

	/**
	* evenement de scrolling dynamique
	*/
	nsp.ScrollerEvent = (function(){
		function ScrollerEvent(params){
			nsp.Event.call(this,'scroll',params);
		};
		Object.assign(ScrollerEvent.prototype, nsp.Event.prototype);
		return ScrollerEvent;
	})();

	/**
	* evenement de scrolling dynamique
	*/
	nsp.InfiniteScrollEvent = (function(){
		function InfiniteScrollEvent(params){
			nsp.Event.call(this,'infinite-scroll',params);
		};
		Object.assign(InfiniteScrollEvent.prototype, nsp.Event.prototype);
		return InfiniteScrollEvent;
	})();

	/**	
	* l'infinite scrolling
	*/
	nsp.Scroller = (function(){

		function Scroller(){
			nsp.EventDispatcher.call(this);
		};

		Object.assign(Scroller.prototype,nsp.EventDispatcher.prototype);

		Scroller.prototype.forWindow = function(key){

			var doc = $(document);
			var win = $(window);
			var oldPos = win.scrollTop();

			win.on({
				scroll:e=>{
					var tHeight = doc.height() - win.height();
					var scrollTop = win.scrollTop();
					var pos = tHeight-scrollTop;
					var percent = (pos*100)/tHeight;
					var dir = scrollTop > oldPos ? "ttb":"btt";
					oldPos = scrollTop;

					var ev = {
						scrollTop:scrollTop,
						pos:pos,
						percent:percent,
						dir:dir
					};
					this.emit(new nsp.ScrollerEvent(nsp.utilis.merge({state:"scrolling"},ev)));

					if (tHeight == scrollTop) {
						this.emit(new nsp.ScrollerEvent(nsp.utilis.merge({state:"end"},ev)));
					}
					else if (scrollTop == 0) {
						this.emit(new nsp.ScrollerEvent(nsp.utilis.merge({state:"start"},ev)));
					}

				}
			});
		}

		return Scroller;
	})();

	(function(){
		nsp.container = new nsp.Container();
	})();

})(AdminManager);


$(document).ready(function($){
	AdminManager.initialize();
});
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