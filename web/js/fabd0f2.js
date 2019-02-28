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
/*
 * VenoBox - jQuery Plugin
 * version: 1.8.3
 * @requires jQuery >= 1.7.0
 *
 * Examples at http://veno.es/venobox/
 * License: MIT License
 * License URI: https://github.com/nicolafranchini/VenoBox/blob/master/LICENSE
 * Copyright 2013-2017 Nicola Franchini - @nicolafranchini
 *
 */
!function(e){"use strict";var s,i,c,a,o,t,d,l,r,n,v,u,b,k,p,m,h,f,g,x,y,w,C,_,B,P,E,O,D,M,N,U,V,I,z,R,X,Y,j,W,q;e.fn.extend({venobox:function($){var A=this,H=e.extend({arrowsColor:"#B6B6B6",autoplay:!1,bgcolor:"#fff",border:"0",closeBackground:"#161617",closeColor:"#d2d2d2",framewidth:"",frameheight:"",gallItems:!1,infinigall:!1,htmlClose:"&times;",htmlNext:"<span>Next</span>",htmlPrev:"<span>Prev</span>",numeratio:!1,numerationBackground:"#161617",numerationColor:"#d2d2d2",numerationPosition:"top",overlayClose:!0,overlayColor:"rgba(23,23,23,0.85)",spinner:"double-bounce",spinColor:"#d2d2d2",titleattr:"title",titleBackground:"#161617",titleColor:"#d2d2d2",titlePosition:"top",cb_pre_open:function(){return!0},cb_post_open:function(){},cb_pre_close:function(){return!0},cb_post_close:function(){},cb_post_resize:function(){},cb_after_nav:function(){},cb_init:function(){}},$);return H.cb_init(A),this.each(function(){if((D=e(this)).data("venobox"))return!0;function $(){y=D.data("gall"),h=D.data("numeratio"),u=D.data("gallItems"),b=D.data("infinigall"),k=u||e('.vbox-item[data-gall="'+y+'"]'),w=k.eq(k.index(D)+1),C=k.eq(k.index(D)-1),w.length||!0!==b||(w=k.eq(0)),k.length>1?(M=k.index(D)+1,c.html(M+" / "+k.length)):M=1,!0===h?c.show():c.hide(),""!==x?a.show():a.hide(),w.length||!0===b?(e(".vbox-next").css("display","block"),_=!0):(e(".vbox-next").css("display","none"),_=!1),k.index(D)>0||!0===b?(e(".vbox-prev").css("display","block"),B=!0):(e(".vbox-prev").css("display","none"),B=!1),!0!==B&&!0!==_||(d.on(K.DOWN,F),d.on(K.MOVE,G),d.on(K.UP,J))}function Q(e){return!(e.length<1)&&(!p&&(p=!0,f=e.data("overlay")||e.data("overlaycolor"),n=e.data("framewidth"),v=e.data("frameheight"),o=e.data("border"),i=e.data("bgcolor"),l=e.data("href")||e.attr("href"),s=e.data("autoplay"),x=e.attr(e.data("titleattr"))||"",e===C&&d.addClass("animated").addClass("swipe-right"),e===w&&d.addClass("animated").addClass("swipe-left"),E.show(),void d.animate({opacity:0},500,function(){g.css("background",f),d.removeClass("animated").removeClass("swipe-left").removeClass("swipe-right").css({"margin-left":0,"margin-right":0}),"iframe"==e.data("vbtype")?ce():"inline"==e.data("vbtype")?oe():"ajax"==e.data("vbtype")?ie():"video"==e.data("vbtype")?ae(s):(d.html('<img src="'+l+'">'),te()),D=e,$(),p=!1,H.cb_after_nav(D,M,w,C)})))}function S(e){27===e.keyCode&&T(),37==e.keyCode&&!0===B&&Q(C),39==e.keyCode&&!0===_&&Q(w)}function T(){if(!1===H.cb_pre_close(D,M,w,C))return!1;e("body").off("keydown",S).removeClass("vbox-open"),D.focus(),g.animate({opacity:0},500,function(){g.remove(),p=!1,H.cb_post_close()})}A.VBclose=function(){T()},D.addClass("vbox-item"),D.data("framewidth",H.framewidth),D.data("frameheight",H.frameheight),D.data("border",H.border),D.data("bgcolor",H.bgcolor),D.data("numeratio",H.numeratio),D.data("gallItems",H.gallItems),D.data("infinigall",H.infinigall),D.data("overlaycolor",H.overlayColor),D.data("titleattr",H.titleattr),D.data("venobox",!0),D.on("click",function(u){if(u.preventDefault(),D=e(this),!1===H.cb_pre_open(D))return!1;switch(A.VBnext=function(){Q(w)},A.VBprev=function(){Q(C)},f=D.data("overlay")||D.data("overlaycolor"),n=D.data("framewidth"),v=D.data("frameheight"),s=D.data("autoplay")||H.autoplay,o=D.data("border"),i=D.data("bgcolor"),_=!1,B=!1,p=!1,l=D.data("href")||D.attr("href"),r=D.data("css")||"",x=D.attr(D.data("titleattr"))||"",P='<div class="vbox-preloader">',H.spinner){case"rotating-plane":P+='<div class="sk-rotating-plane"></div>';break;case"double-bounce":P+='<div class="sk-double-bounce"><div class="sk-child sk-double-bounce1"></div><div class="sk-child sk-double-bounce2"></div></div>';break;case"wave":P+='<div class="sk-wave"><div class="sk-rect sk-rect1"></div><div class="sk-rect sk-rect2"></div><div class="sk-rect sk-rect3"></div><div class="sk-rect sk-rect4"></div><div class="sk-rect sk-rect5"></div></div>';break;case"wandering-cubes":P+='<div class="sk-wandering-cubes"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div></div>';break;case"spinner-pulse":P+='<div class="sk-spinner sk-spinner-pulse"></div>';break;case"chasing-dots":P+='<div class="sk-chasing-dots"><div class="sk-child sk-dot1"></div><div class="sk-child sk-dot2"></div></div>';break;case"three-bounce":P+='<div class="sk-three-bounce"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>';break;case"circle":P+='<div class="sk-circle"><div class="sk-circle1 sk-child"></div><div class="sk-circle2 sk-child"></div><div class="sk-circle3 sk-child"></div><div class="sk-circle4 sk-child"></div><div class="sk-circle5 sk-child"></div><div class="sk-circle6 sk-child"></div><div class="sk-circle7 sk-child"></div><div class="sk-circle8 sk-child"></div><div class="sk-circle9 sk-child"></div><div class="sk-circle10 sk-child"></div><div class="sk-circle11 sk-child"></div><div class="sk-circle12 sk-child"></div></div>';break;case"cube-grid":P+='<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>';break;case"fading-circle":P+='<div class="sk-fading-circle"><div class="sk-circle1 sk-circle"></div><div class="sk-circle2 sk-circle"></div><div class="sk-circle3 sk-circle"></div><div class="sk-circle4 sk-circle"></div><div class="sk-circle5 sk-circle"></div><div class="sk-circle6 sk-circle"></div><div class="sk-circle7 sk-circle"></div><div class="sk-circle8 sk-circle"></div><div class="sk-circle9 sk-circle"></div><div class="sk-circle10 sk-circle"></div><div class="sk-circle11 sk-circle"></div><div class="sk-circle12 sk-circle"></div></div>';break;case"folding-cube":P+='<div class="sk-folding-cube"><div class="sk-cube1 sk-cube"></div><div class="sk-cube2 sk-cube"></div><div class="sk-cube4 sk-cube"></div><div class="sk-cube3 sk-cube"></div></div>'}return P+="</div>",O='<a class="vbox-next">'+H.htmlNext+'</a><a class="vbox-prev">'+H.htmlPrev+"</a>",U='<div class="vbox-title"></div><div class="vbox-num">0/0</div><div class="vbox-close">'+H.htmlClose+"</div>",t='<div class="vbox-overlay '+r+'" style="background:'+f+'">'+P+'<div class="vbox-container"><div class="vbox-content"></div></div>'+U+O+"</div>",e("body").append(t).addClass("vbox-open"),e(".vbox-preloader div:not(.sk-circle) .sk-child, .vbox-preloader .sk-rotating-plane, .vbox-preloader .sk-rect, .vbox-preloader div:not(.sk-folding-cube) .sk-cube, .vbox-preloader .sk-spinner-pulse").css("background-color",H.spinColor),g=e(".vbox-overlay"),e(".vbox-container"),d=e(".vbox-content"),c=e(".vbox-num"),a=e(".vbox-title"),(E=e(".vbox-preloader")).show(),a.css(H.titlePosition,"-1px"),a.css({color:H.titleColor,"background-color":H.titleBackground}),e(".vbox-close").css({color:H.closeColor,"background-color":H.closeBackground}),e(".vbox-num").css(H.numerationPosition,"-1px"),e(".vbox-num").css({color:H.numerationColor,"background-color":H.numerationBackground}),e(".vbox-next span, .vbox-prev span").css({"border-top-color":H.arrowsColor,"border-right-color":H.arrowsColor}),d.html(""),d.css("opacity","0"),g.css("opacity","0"),$(),g.animate({opacity:1},250,function(){"iframe"==D.data("vbtype")?ce():"inline"==D.data("vbtype")?oe():"ajax"==D.data("vbtype")?ie():"video"==D.data("vbtype")?ae(s):(d.html('<img src="'+l+'">'),te()),H.cb_post_open(D,M,w,C)}),e("body").keydown(S),e(".vbox-prev").on("click",function(){Q(C)}),e(".vbox-next").on("click",function(){Q(w)}),!1});var Z=".vbox-overlay";function F(e){d.addClass("animated"),I=R=e.pageY,z=X=e.pageX,N=!0}function G(e){if(!0===N){X=e.pageX,R=e.pageY,j=X-z,W=R-I;var s=Math.abs(j);s>Math.abs(W)&&s<=100&&(e.preventDefault(),d.css("margin-left",j))}}function J(e){if(!0===N){N=!1;var s=D,i=!1;(Y=X-z)<0&&!0===_&&(s=w,i=!0),Y>0&&!0===B&&(s=C,i=!0),Math.abs(Y)>=q&&!0===i?Q(s):d.css({"margin-left":0,"margin-right":0})}}H.overlayClose||(Z=".vbox-close"),e("body").on("click",Z,function(s){(e(s.target).is(".vbox-overlay")||e(s.target).is(".vbox-content")||e(s.target).is(".vbox-close")||e(s.target).is(".vbox-preloader"))&&T()}),z=0,X=0,Y=0,q=50,N=!1;var K={DOWN:"touchmousedown",UP:"touchmouseup",MOVE:"touchmousemove"},L=function(s){var i;switch(s.type){case"mousedown":i=K.DOWN;break;case"mouseup":case"mouseout":i=K.UP;break;case"mousemove":i=K.MOVE;break;default:return}var c=se(i,s,s.pageX,s.pageY);e(s.target).trigger(c)},ee=function(s){var i;switch(s.type){case"touchstart":i=K.DOWN;break;case"touchend":i=K.UP;break;case"touchmove":i=K.MOVE;break;default:return}var c,a=s.originalEvent.touches[0];c=i==K.UP?se(i,s,null,null):se(i,s,a.pageX,a.pageY),e(s.target).trigger(c)},se=function(s,i,c,a){return e.Event(s,{pageX:c,pageY:a,originalEvent:i})};function ie(){e.ajax({url:l,cache:!1}).done(function(e){d.html('<div class="vbox-inline">'+e+"</div>"),te()}).fail(function(){d.html('<div class="vbox-inline"><p>Error retrieving contents, please retry</div>'),de()})}function ce(){d.html('<iframe class="venoframe" src="'+l+'"></iframe>'),de()}function ae(e){var s,i=function(e){var s;e.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/),RegExp.$3.indexOf("youtu")>-1?s="youtube":RegExp.$3.indexOf("vimeo")>-1&&(s="vimeo");return{type:s,id:RegExp.$6}}(l),c=(e?"?rel=0&autoplay=1":"?rel=0")+function(e){var s="",i=decodeURIComponent(e).split("?");if(void 0!==i[1]){var c,a,o=i[1].split("&");for(a=0;a<o.length;a++)c=o[a].split("="),s=s+"&"+c[0]+"="+c[1]}return encodeURI(s)}(l);"vimeo"==i.type?s="https://player.vimeo.com/video/":"youtube"==i.type&&(s="https://www.youtube.com/embed/"),d.html('<iframe class="venoframe vbvid" webkitallowfullscreen mozallowfullscreen allowfullscreen frameborder="0" src="'+s+i.id+c+'"></iframe>'),de()}function oe(){d.html('<div class="vbox-inline">'+e(l).html()+"</div>"),de()}function te(){(V=d.find("img")).length?V.each(function(){e(this).one("load",function(){de()})}):de()}function de(){a.html(x),d.find(">:first-child").addClass("figlio").css({width:n,height:v,padding:o,background:i}),e("img.figlio").on("dragstart",function(e){e.preventDefault()}),le(),d.animate({opacity:"1"},"slow",function(){E.hide()})}function le(){var s=d.outerHeight(),i=e(window).height();m=s+60<i?(i-s)/2:"30px",d.css("margin-top",m),d.css("margin-bottom",m),H.cb_post_resize()}"ontouchstart"in window?(e(document).on("touchstart",ee),e(document).on("touchmove",ee),e(document).on("touchend",ee)):(e(document).on("mousedown",L),e(document).on("mouseup",L),e(document).on("mouseout",L),e(document).on("mousemove",L)),e(window).resize(function(){e(".vbox-content").length&&setTimeout(le(),800)})})}})}(jQuery);

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

	nsp.fn.CatalogRepository = (function(){

		function CatalogRepository(params){
			nsp.Repository.call(this,params);
		};

		Object.assign(CatalogRepository.prototype, nsp.Repository.prototype);

		CatalogRepository.prototype.customRequest = function(event){

			return new Promise((resolve,reject)=>{
	  			this.request({
	  				url:`/programmes/${event.type}/${this.current.id}`,
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
		return CatalogRepository;
	})();


	nsp.fn.CatalogView = (function(){
		function CatalogView(params){
			nsp.View.call(this,params);

			this.vars({
				$tpl:{
					errors:`
						<ul>
							{{#errors}}
								<li>{{ . }}</li>
							{{/errors}}
						</ul>
					`,
					entry:`
						
					`,
					entries:`
						{{#data}}
							{{> entry}}
						{{/data}}
					`
				}
			});
		};

		Object.assign(CatalogView.prototype, nsp.View.prototype);

		CatalogView.prototype.controller = function(){

			let mainMenu = $("#master-menu");
            var sections = $(".block");
            var win = $(window);

            sections.each(function(i,el){
                let obj = $(el);
                obj.addClass('section-animation');
            });

            win.on({
                scroll:function(e){

                    let pos = $(this).scrollTop();
                    if(pos >= 230){
                        mainMenu.addClass('active');
                    }
                    else if(pos <= 120){
                        mainMenu.removeClass('active');
                    }
                }
            });


            function genrePrintHook(){
                let gp = $("#gender-print");
                let gpp = gp.parent();
                let hr = gpp.innerHeight() + $("#master-menu").innerHeight();

                gp.css({
                    left:(gpp.innerWidth()/2) - (gp.innerWidth()/2),
                    top:(hr/2) - (gp.innerHeight()/2),
                    visibility:'visible'
                });
            }
            
            $(window).on({
                resize:function(e){
                    genrePrintHook();
                }
            });
            genrePrintHook();

            $(document).on('click', '.venobox', function(e){
            	var obj = $(e.target);

            	if(!obj.hasClass('venobox-clicked')){

	        		obj.venobox({
		                numeratio: true,
		                titleattr: 'data-title',
		                titlePosition: 'top',
		                spinner: 'wandering-cubes',
		                spinColor: '#007bff',
		                autoplay:true,
		            });

		            obj.addClass('venobox-clicked');
		            e.preventDefault();
		            obj.click();
            	}
            	
    		});


            /* 09. VENOBOX JS */
           /* $('.venobox').venobox({
                numeratio: true,
                titleattr: 'data-title',
                titlePosition: 'top',
                spinner: 'wandering-cubes',
                spinColor: '#007bff',
                autoplay:true,
            });*/

            $('[data-toggle="tooltip"]').tooltip();
			
			// on ecoute les evenements
			this.subscribe(event=>{
				if(event instanceof nsp.InfiniteScrollEvent){

					if(event.params.state == "start"){
						$(document.body).addClass("infinite-scroll-active");
					}
					else if(~['end','fails'].indexOf(event.params.state)){
						$(document.body).removeClass("infinite-scroll-active");

						if(event.params.state == 'end'){
							var data = event.params.data;

							if(data){
								$("#data-container > div:first").append($(data));
							}
						}
					}
				}
			});

			var scroller = nsp.container.get('Scroller');
			scroller.subscribe(event=>{
				if(event instanceof nsp.ScrollerEvent && event.params.percent <= 30 && event.params.dir == "ttb"){
					if(!$(document.body).hasClass("infinite-scroll-active")){

						var limit = 20;
						var offset = $("#data-container .data-item").length;
						nsp.utilis.merge(event.params,{limit:limit,offset:offset});

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
		return CatalogView;
	})();

})(AdminManager);
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


    var slider = new nsp.plugins.SlimBanerSlide();
    
    slider.subscribe(event=>{
        
    });

    slider.init({
        container:'header #master-cover',
        data:['the-river.jpg','mr-brau.jpg','edge-of-desire.jpg'],
        delay:5000,
        current:0,
    });
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