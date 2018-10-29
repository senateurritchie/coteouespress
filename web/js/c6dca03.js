/**
 * Owl Carousel v2.3.4
 * Copyright 2013-2018 David Deutsch
 * Licensed under: SEE LICENSE IN https://github.com/OwlCarousel2/OwlCarousel2/blob/master/LICENSE
 */
!function(a,b,c,d){function e(b,c){this.settings=null,this.options=a.extend({},e.Defaults,c),this.$element=a(b),this._handlers={},this._plugins={},this._supress={},this._current=null,this._speed=null,this._coordinates=[],this._breakpoint=null,this._width=null,this._items=[],this._clones=[],this._mergers=[],this._widths=[],this._invalidated={},this._pipe=[],this._drag={time:null,target:null,pointer:null,stage:{start:null,current:null},direction:null},this._states={current:{},tags:{initializing:["busy"],animating:["busy"],dragging:["interacting"]}},a.each(["onResize","onThrottledResize"],a.proxy(function(b,c){this._handlers[c]=a.proxy(this[c],this)},this)),a.each(e.Plugins,a.proxy(function(a,b){this._plugins[a.charAt(0).toLowerCase()+a.slice(1)]=new b(this)},this)),a.each(e.Workers,a.proxy(function(b,c){this._pipe.push({filter:c.filter,run:a.proxy(c.run,this)})},this)),this.setup(),this.initialize()}e.Defaults={items:3,loop:!1,center:!1,rewind:!1,checkVisibility:!0,mouseDrag:!0,touchDrag:!0,pullDrag:!0,freeDrag:!1,margin:0,stagePadding:0,merge:!1,mergeFit:!0,autoWidth:!1,startPosition:0,rtl:!1,smartSpeed:250,fluidSpeed:!1,dragEndSpeed:!1,responsive:{},responsiveRefreshRate:200,responsiveBaseElement:b,fallbackEasing:"swing",slideTransition:"",info:!1,nestedItemSelector:!1,itemElement:"div",stageElement:"div",refreshClass:"owl-refresh",loadedClass:"owl-loaded",loadingClass:"owl-loading",rtlClass:"owl-rtl",responsiveClass:"owl-responsive",dragClass:"owl-drag",itemClass:"owl-item",stageClass:"owl-stage",stageOuterClass:"owl-stage-outer",grabClass:"owl-grab"},e.Width={Default:"default",Inner:"inner",Outer:"outer"},e.Type={Event:"event",State:"state"},e.Plugins={},e.Workers=[{filter:["width","settings"],run:function(){this._width=this.$element.width()}},{filter:["width","items","settings"],run:function(a){a.current=this._items&&this._items[this.relative(this._current)]}},{filter:["items","settings"],run:function(){this.$stage.children(".cloned").remove()}},{filter:["width","items","settings"],run:function(a){var b=this.settings.margin||"",c=!this.settings.autoWidth,d=this.settings.rtl,e={width:"auto","margin-left":d?b:"","margin-right":d?"":b};!c&&this.$stage.children().css(e),a.css=e}},{filter:["width","items","settings"],run:function(a){var b=(this.width()/this.settings.items).toFixed(3)-this.settings.margin,c=null,d=this._items.length,e=!this.settings.autoWidth,f=[];for(a.items={merge:!1,width:b};d--;)c=this._mergers[d],c=this.settings.mergeFit&&Math.min(c,this.settings.items)||c,a.items.merge=c>1||a.items.merge,f[d]=e?b*c:this._items[d].width();this._widths=f}},{filter:["items","settings"],run:function(){var b=[],c=this._items,d=this.settings,e=Math.max(2*d.items,4),f=2*Math.ceil(c.length/2),g=d.loop&&c.length?d.rewind?e:Math.max(e,f):0,h="",i="";for(g/=2;g>0;)b.push(this.normalize(b.length/2,!0)),h+=c[b[b.length-1]][0].outerHTML,b.push(this.normalize(c.length-1-(b.length-1)/2,!0)),i=c[b[b.length-1]][0].outerHTML+i,g-=1;this._clones=b,a(h).addClass("cloned").appendTo(this.$stage),a(i).addClass("cloned").prependTo(this.$stage)}},{filter:["width","items","settings"],run:function(){for(var a=this.settings.rtl?1:-1,b=this._clones.length+this._items.length,c=-1,d=0,e=0,f=[];++c<b;)d=f[c-1]||0,e=this._widths[this.relative(c)]+this.settings.margin,f.push(d+e*a);this._coordinates=f}},{filter:["width","items","settings"],run:function(){var a=this.settings.stagePadding,b=this._coordinates,c={width:Math.ceil(Math.abs(b[b.length-1]))+2*a,"padding-left":a||"","padding-right":a||""};this.$stage.css(c)}},{filter:["width","items","settings"],run:function(a){var b=this._coordinates.length,c=!this.settings.autoWidth,d=this.$stage.children();if(c&&a.items.merge)for(;b--;)a.css.width=this._widths[this.relative(b)],d.eq(b).css(a.css);else c&&(a.css.width=a.items.width,d.css(a.css))}},{filter:["items"],run:function(){this._coordinates.length<1&&this.$stage.removeAttr("style")}},{filter:["width","items","settings"],run:function(a){a.current=a.current?this.$stage.children().index(a.current):0,a.current=Math.max(this.minimum(),Math.min(this.maximum(),a.current)),this.reset(a.current)}},{filter:["position"],run:function(){this.animate(this.coordinates(this._current))}},{filter:["width","position","items","settings"],run:function(){var a,b,c,d,e=this.settings.rtl?1:-1,f=2*this.settings.stagePadding,g=this.coordinates(this.current())+f,h=g+this.width()*e,i=[];for(c=0,d=this._coordinates.length;c<d;c++)a=this._coordinates[c-1]||0,b=Math.abs(this._coordinates[c])+f*e,(this.op(a,"<=",g)&&this.op(a,">",h)||this.op(b,"<",g)&&this.op(b,">",h))&&i.push(c);this.$stage.children(".active").removeClass("active"),this.$stage.children(":eq("+i.join("), :eq(")+")").addClass("active"),this.$stage.children(".center").removeClass("center"),this.settings.center&&this.$stage.children().eq(this.current()).addClass("center")}}],e.prototype.initializeStage=function(){this.$stage=this.$element.find("."+this.settings.stageClass),this.$stage.length||(this.$element.addClass(this.options.loadingClass),this.$stage=a("<"+this.settings.stageElement+">",{class:this.settings.stageClass}).wrap(a("<div/>",{class:this.settings.stageOuterClass})),this.$element.append(this.$stage.parent()))},e.prototype.initializeItems=function(){var b=this.$element.find(".owl-item");if(b.length)return this._items=b.get().map(function(b){return a(b)}),this._mergers=this._items.map(function(){return 1}),void this.refresh();this.replace(this.$element.children().not(this.$stage.parent())),this.isVisible()?this.refresh():this.invalidate("width"),this.$element.removeClass(this.options.loadingClass).addClass(this.options.loadedClass)},e.prototype.initialize=function(){if(this.enter("initializing"),this.trigger("initialize"),this.$element.toggleClass(this.settings.rtlClass,this.settings.rtl),this.settings.autoWidth&&!this.is("pre-loading")){var a,b,c;a=this.$element.find("img"),b=this.settings.nestedItemSelector?"."+this.settings.nestedItemSelector:d,c=this.$element.children(b).width(),a.length&&c<=0&&this.preloadAutoWidthImages(a)}this.initializeStage(),this.initializeItems(),this.registerEventHandlers(),this.leave("initializing"),this.trigger("initialized")},e.prototype.isVisible=function(){return!this.settings.checkVisibility||this.$element.is(":visible")},e.prototype.setup=function(){var b=this.viewport(),c=this.options.responsive,d=-1,e=null;c?(a.each(c,function(a){a<=b&&a>d&&(d=Number(a))}),e=a.extend({},this.options,c[d]),"function"==typeof e.stagePadding&&(e.stagePadding=e.stagePadding()),delete e.responsive,e.responsiveClass&&this.$element.attr("class",this.$element.attr("class").replace(new RegExp("("+this.options.responsiveClass+"-)\\S+\\s","g"),"$1"+d))):e=a.extend({},this.options),this.trigger("change",{property:{name:"settings",value:e}}),this._breakpoint=d,this.settings=e,this.invalidate("settings"),this.trigger("changed",{property:{name:"settings",value:this.settings}})},e.prototype.optionsLogic=function(){this.settings.autoWidth&&(this.settings.stagePadding=!1,this.settings.merge=!1)},e.prototype.prepare=function(b){var c=this.trigger("prepare",{content:b});return c.data||(c.data=a("<"+this.settings.itemElement+"/>").addClass(this.options.itemClass).append(b)),this.trigger("prepared",{content:c.data}),c.data},e.prototype.update=function(){for(var b=0,c=this._pipe.length,d=a.proxy(function(a){return this[a]},this._invalidated),e={};b<c;)(this._invalidated.all||a.grep(this._pipe[b].filter,d).length>0)&&this._pipe[b].run(e),b++;this._invalidated={},!this.is("valid")&&this.enter("valid")},e.prototype.width=function(a){switch(a=a||e.Width.Default){case e.Width.Inner:case e.Width.Outer:return this._width;default:return this._width-2*this.settings.stagePadding+this.settings.margin}},e.prototype.refresh=function(){this.enter("refreshing"),this.trigger("refresh"),this.setup(),this.optionsLogic(),this.$element.addClass(this.options.refreshClass),this.update(),this.$element.removeClass(this.options.refreshClass),this.leave("refreshing"),this.trigger("refreshed")},e.prototype.onThrottledResize=function(){b.clearTimeout(this.resizeTimer),this.resizeTimer=b.setTimeout(this._handlers.onResize,this.settings.responsiveRefreshRate)},e.prototype.onResize=function(){return!!this._items.length&&(this._width!==this.$element.width()&&(!!this.isVisible()&&(this.enter("resizing"),this.trigger("resize").isDefaultPrevented()?(this.leave("resizing"),!1):(this.invalidate("width"),this.refresh(),this.leave("resizing"),void this.trigger("resized")))))},e.prototype.registerEventHandlers=function(){a.support.transition&&this.$stage.on(a.support.transition.end+".owl.core",a.proxy(this.onTransitionEnd,this)),!1!==this.settings.responsive&&this.on(b,"resize",this._handlers.onThrottledResize),this.settings.mouseDrag&&(this.$element.addClass(this.options.dragClass),this.$stage.on("mousedown.owl.core",a.proxy(this.onDragStart,this)),this.$stage.on("dragstart.owl.core selectstart.owl.core",function(){return!1})),this.settings.touchDrag&&(this.$stage.on("touchstart.owl.core",a.proxy(this.onDragStart,this)),this.$stage.on("touchcancel.owl.core",a.proxy(this.onDragEnd,this)))},e.prototype.onDragStart=function(b){var d=null;3!==b.which&&(a.support.transform?(d=this.$stage.css("transform").replace(/.*\(|\)| /g,"").split(","),d={x:d[16===d.length?12:4],y:d[16===d.length?13:5]}):(d=this.$stage.position(),d={x:this.settings.rtl?d.left+this.$stage.width()-this.width()+this.settings.margin:d.left,y:d.top}),this.is("animating")&&(a.support.transform?this.animate(d.x):this.$stage.stop(),this.invalidate("position")),this.$element.toggleClass(this.options.grabClass,"mousedown"===b.type),this.speed(0),this._drag.time=(new Date).getTime(),this._drag.target=a(b.target),this._drag.stage.start=d,this._drag.stage.current=d,this._drag.pointer=this.pointer(b),a(c).on("mouseup.owl.core touchend.owl.core",a.proxy(this.onDragEnd,this)),a(c).one("mousemove.owl.core touchmove.owl.core",a.proxy(function(b){var d=this.difference(this._drag.pointer,this.pointer(b));a(c).on("mousemove.owl.core touchmove.owl.core",a.proxy(this.onDragMove,this)),Math.abs(d.x)<Math.abs(d.y)&&this.is("valid")||(b.preventDefault(),this.enter("dragging"),this.trigger("drag"))},this)))},e.prototype.onDragMove=function(a){var b=null,c=null,d=null,e=this.difference(this._drag.pointer,this.pointer(a)),f=this.difference(this._drag.stage.start,e);this.is("dragging")&&(a.preventDefault(),this.settings.loop?(b=this.coordinates(this.minimum()),c=this.coordinates(this.maximum()+1)-b,f.x=((f.x-b)%c+c)%c+b):(b=this.settings.rtl?this.coordinates(this.maximum()):this.coordinates(this.minimum()),c=this.settings.rtl?this.coordinates(this.minimum()):this.coordinates(this.maximum()),d=this.settings.pullDrag?-1*e.x/5:0,f.x=Math.max(Math.min(f.x,b+d),c+d)),this._drag.stage.current=f,this.animate(f.x))},e.prototype.onDragEnd=function(b){var d=this.difference(this._drag.pointer,this.pointer(b)),e=this._drag.stage.current,f=d.x>0^this.settings.rtl?"left":"right";a(c).off(".owl.core"),this.$element.removeClass(this.options.grabClass),(0!==d.x&&this.is("dragging")||!this.is("valid"))&&(this.speed(this.settings.dragEndSpeed||this.settings.smartSpeed),this.current(this.closest(e.x,0!==d.x?f:this._drag.direction)),this.invalidate("position"),this.update(),this._drag.direction=f,(Math.abs(d.x)>3||(new Date).getTime()-this._drag.time>300)&&this._drag.target.one("click.owl.core",function(){return!1})),this.is("dragging")&&(this.leave("dragging"),this.trigger("dragged"))},e.prototype.closest=function(b,c){var e=-1,f=30,g=this.width(),h=this.coordinates();return this.settings.freeDrag||a.each(h,a.proxy(function(a,i){return"left"===c&&b>i-f&&b<i+f?e=a:"right"===c&&b>i-g-f&&b<i-g+f?e=a+1:this.op(b,"<",i)&&this.op(b,">",h[a+1]!==d?h[a+1]:i-g)&&(e="left"===c?a+1:a),-1===e},this)),this.settings.loop||(this.op(b,">",h[this.minimum()])?e=b=this.minimum():this.op(b,"<",h[this.maximum()])&&(e=b=this.maximum())),e},e.prototype.animate=function(b){var c=this.speed()>0;this.is("animating")&&this.onTransitionEnd(),c&&(this.enter("animating"),this.trigger("translate")),a.support.transform3d&&a.support.transition?this.$stage.css({transform:"translate3d("+b+"px,0px,0px)",transition:this.speed()/1e3+"s"+(this.settings.slideTransition?" "+this.settings.slideTransition:"")}):c?this.$stage.animate({left:b+"px"},this.speed(),this.settings.fallbackEasing,a.proxy(this.onTransitionEnd,this)):this.$stage.css({left:b+"px"})},e.prototype.is=function(a){return this._states.current[a]&&this._states.current[a]>0},e.prototype.current=function(a){if(a===d)return this._current;if(0===this._items.length)return d;if(a=this.normalize(a),this._current!==a){var b=this.trigger("change",{property:{name:"position",value:a}});b.data!==d&&(a=this.normalize(b.data)),this._current=a,this.invalidate("position"),this.trigger("changed",{property:{name:"position",value:this._current}})}return this._current},e.prototype.invalidate=function(b){return"string"===a.type(b)&&(this._invalidated[b]=!0,this.is("valid")&&this.leave("valid")),a.map(this._invalidated,function(a,b){return b})},e.prototype.reset=function(a){(a=this.normalize(a))!==d&&(this._speed=0,this._current=a,this.suppress(["translate","translated"]),this.animate(this.coordinates(a)),this.release(["translate","translated"]))},e.prototype.normalize=function(a,b){var c=this._items.length,e=b?0:this._clones.length;return!this.isNumeric(a)||c<1?a=d:(a<0||a>=c+e)&&(a=((a-e/2)%c+c)%c+e/2),a},e.prototype.relative=function(a){return a-=this._clones.length/2,this.normalize(a,!0)},e.prototype.maximum=function(a){var b,c,d,e=this.settings,f=this._coordinates.length;if(e.loop)f=this._clones.length/2+this._items.length-1;else if(e.autoWidth||e.merge){if(b=this._items.length)for(c=this._items[--b].width(),d=this.$element.width();b--&&!((c+=this._items[b].width()+this.settings.margin)>d););f=b+1}else f=e.center?this._items.length-1:this._items.length-e.items;return a&&(f-=this._clones.length/2),Math.max(f,0)},e.prototype.minimum=function(a){return a?0:this._clones.length/2},e.prototype.items=function(a){return a===d?this._items.slice():(a=this.normalize(a,!0),this._items[a])},e.prototype.mergers=function(a){return a===d?this._mergers.slice():(a=this.normalize(a,!0),this._mergers[a])},e.prototype.clones=function(b){var c=this._clones.length/2,e=c+this._items.length,f=function(a){return a%2==0?e+a/2:c-(a+1)/2};return b===d?a.map(this._clones,function(a,b){return f(b)}):a.map(this._clones,function(a,c){return a===b?f(c):null})},e.prototype.speed=function(a){return a!==d&&(this._speed=a),this._speed},e.prototype.coordinates=function(b){var c,e=1,f=b-1;return b===d?a.map(this._coordinates,a.proxy(function(a,b){return this.coordinates(b)},this)):(this.settings.center?(this.settings.rtl&&(e=-1,f=b+1),c=this._coordinates[b],c+=(this.width()-c+(this._coordinates[f]||0))/2*e):c=this._coordinates[f]||0,c=Math.ceil(c))},e.prototype.duration=function(a,b,c){return 0===c?0:Math.min(Math.max(Math.abs(b-a),1),6)*Math.abs(c||this.settings.smartSpeed)},e.prototype.to=function(a,b){var c=this.current(),d=null,e=a-this.relative(c),f=(e>0)-(e<0),g=this._items.length,h=this.minimum(),i=this.maximum();this.settings.loop?(!this.settings.rewind&&Math.abs(e)>g/2&&(e+=-1*f*g),a=c+e,(d=((a-h)%g+g)%g+h)!==a&&d-e<=i&&d-e>0&&(c=d-e,a=d,this.reset(c))):this.settings.rewind?(i+=1,a=(a%i+i)%i):a=Math.max(h,Math.min(i,a)),this.speed(this.duration(c,a,b)),this.current(a),this.isVisible()&&this.update()},e.prototype.next=function(a){a=a||!1,this.to(this.relative(this.current())+1,a)},e.prototype.prev=function(a){a=a||!1,this.to(this.relative(this.current())-1,a)},e.prototype.onTransitionEnd=function(a){if(a!==d&&(a.stopPropagation(),(a.target||a.srcElement||a.originalTarget)!==this.$stage.get(0)))return!1;this.leave("animating"),this.trigger("translated")},e.prototype.viewport=function(){var d;return this.options.responsiveBaseElement!==b?d=a(this.options.responsiveBaseElement).width():b.innerWidth?d=b.innerWidth:c.documentElement&&c.documentElement.clientWidth?d=c.documentElement.clientWidth:console.warn("Can not detect viewport width."),d},e.prototype.replace=function(b){this.$stage.empty(),this._items=[],b&&(b=b instanceof jQuery?b:a(b)),this.settings.nestedItemSelector&&(b=b.find("."+this.settings.nestedItemSelector)),b.filter(function(){return 1===this.nodeType}).each(a.proxy(function(a,b){b=this.prepare(b),this.$stage.append(b),this._items.push(b),this._mergers.push(1*b.find("[data-merge]").addBack("[data-merge]").attr("data-merge")||1)},this)),this.reset(this.isNumeric(this.settings.startPosition)?this.settings.startPosition:0),this.invalidate("items")},e.prototype.add=function(b,c){var e=this.relative(this._current);c=c===d?this._items.length:this.normalize(c,!0),b=b instanceof jQuery?b:a(b),this.trigger("add",{content:b,position:c}),b=this.prepare(b),0===this._items.length||c===this._items.length?(0===this._items.length&&this.$stage.append(b),0!==this._items.length&&this._items[c-1].after(b),this._items.push(b),this._mergers.push(1*b.find("[data-merge]").addBack("[data-merge]").attr("data-merge")||1)):(this._items[c].before(b),this._items.splice(c,0,b),this._mergers.splice(c,0,1*b.find("[data-merge]").addBack("[data-merge]").attr("data-merge")||1)),this._items[e]&&this.reset(this._items[e].index()),this.invalidate("items"),this.trigger("added",{content:b,position:c})},e.prototype.remove=function(a){(a=this.normalize(a,!0))!==d&&(this.trigger("remove",{content:this._items[a],position:a}),this._items[a].remove(),this._items.splice(a,1),this._mergers.splice(a,1),this.invalidate("items"),this.trigger("removed",{content:null,position:a}))},e.prototype.preloadAutoWidthImages=function(b){b.each(a.proxy(function(b,c){this.enter("pre-loading"),c=a(c),a(new Image).one("load",a.proxy(function(a){c.attr("src",a.target.src),c.css("opacity",1),this.leave("pre-loading"),!this.is("pre-loading")&&!this.is("initializing")&&this.refresh()},this)).attr("src",c.attr("src")||c.attr("data-src")||c.attr("data-src-retina"))},this))},e.prototype.destroy=function(){this.$element.off(".owl.core"),this.$stage.off(".owl.core"),a(c).off(".owl.core"),!1!==this.settings.responsive&&(b.clearTimeout(this.resizeTimer),this.off(b,"resize",this._handlers.onThrottledResize));for(var d in this._plugins)this._plugins[d].destroy();this.$stage.children(".cloned").remove(),this.$stage.unwrap(),this.$stage.children().contents().unwrap(),this.$stage.children().unwrap(),this.$stage.remove(),this.$element.removeClass(this.options.refreshClass).removeClass(this.options.loadingClass).removeClass(this.options.loadedClass).removeClass(this.options.rtlClass).removeClass(this.options.dragClass).removeClass(this.options.grabClass).attr("class",this.$element.attr("class").replace(new RegExp(this.options.responsiveClass+"-\\S+\\s","g"),"")).removeData("owl.carousel")},e.prototype.op=function(a,b,c){var d=this.settings.rtl;switch(b){case"<":return d?a>c:a<c;case">":return d?a<c:a>c;case">=":return d?a<=c:a>=c;case"<=":return d?a>=c:a<=c}},e.prototype.on=function(a,b,c,d){a.addEventListener?a.addEventListener(b,c,d):a.attachEvent&&a.attachEvent("on"+b,c)},e.prototype.off=function(a,b,c,d){a.removeEventListener?a.removeEventListener(b,c,d):a.detachEvent&&a.detachEvent("on"+b,c)},e.prototype.trigger=function(b,c,d,f,g){var h={item:{count:this._items.length,index:this.current()}},i=a.camelCase(a.grep(["on",b,d],function(a){return a}).join("-").toLowerCase()),j=a.Event([b,"owl",d||"carousel"].join(".").toLowerCase(),a.extend({relatedTarget:this},h,c));return this._supress[b]||(a.each(this._plugins,function(a,b){b.onTrigger&&b.onTrigger(j)}),this.register({type:e.Type.Event,name:b}),this.$element.trigger(j),this.settings&&"function"==typeof this.settings[i]&&this.settings[i].call(this,j)),j},e.prototype.enter=function(b){a.each([b].concat(this._states.tags[b]||[]),a.proxy(function(a,b){this._states.current[b]===d&&(this._states.current[b]=0),this._states.current[b]++},this))},e.prototype.leave=function(b){a.each([b].concat(this._states.tags[b]||[]),a.proxy(function(a,b){this._states.current[b]--},this))},e.prototype.register=function(b){if(b.type===e.Type.Event){if(a.event.special[b.name]||(a.event.special[b.name]={}),!a.event.special[b.name].owl){var c=a.event.special[b.name]._default;a.event.special[b.name]._default=function(a){return!c||!c.apply||a.namespace&&-1!==a.namespace.indexOf("owl")?a.namespace&&a.namespace.indexOf("owl")>-1:c.apply(this,arguments)},a.event.special[b.name].owl=!0}}else b.type===e.Type.State&&(this._states.tags[b.name]?this._states.tags[b.name]=this._states.tags[b.name].concat(b.tags):this._states.tags[b.name]=b.tags,this._states.tags[b.name]=a.grep(this._states.tags[b.name],a.proxy(function(c,d){return a.inArray(c,this._states.tags[b.name])===d},this)))},e.prototype.suppress=function(b){a.each(b,a.proxy(function(a,b){this._supress[b]=!0},this))},e.prototype.release=function(b){a.each(b,a.proxy(function(a,b){delete this._supress[b]},this))},e.prototype.pointer=function(a){var c={x:null,y:null};return a=a.originalEvent||a||b.event,a=a.touches&&a.touches.length?a.touches[0]:a.changedTouches&&a.changedTouches.length?a.changedTouches[0]:a,a.pageX?(c.x=a.pageX,c.y=a.pageY):(c.x=a.clientX,c.y=a.clientY),c},e.prototype.isNumeric=function(a){return!isNaN(parseFloat(a))},e.prototype.difference=function(a,b){return{x:a.x-b.x,y:a.y-b.y}},a.fn.owlCarousel=function(b){var c=Array.prototype.slice.call(arguments,1);return this.each(function(){var d=a(this),f=d.data("owl.carousel");f||(f=new e(this,"object"==typeof b&&b),d.data("owl.carousel",f),a.each(["next","prev","to","destroy","refresh","replace","add","remove"],function(b,c){f.register({type:e.Type.Event,name:c}),f.$element.on(c+".owl.carousel.core",a.proxy(function(a){a.namespace&&a.relatedTarget!==this&&(this.suppress([c]),f[c].apply(this,[].slice.call(arguments,1)),this.release([c]))},f))})),"string"==typeof b&&"_"!==b.charAt(0)&&f[b].apply(f,c)})},a.fn.owlCarousel.Constructor=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(b){this._core=b,this._interval=null,this._visible=null,this._handlers={"initialized.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.autoRefresh&&this.watch()},this)},this._core.options=a.extend({},e.Defaults,this._core.options),this._core.$element.on(this._handlers)};e.Defaults={autoRefresh:!0,autoRefreshInterval:500},e.prototype.watch=function(){this._interval||(this._visible=this._core.isVisible(),this._interval=b.setInterval(a.proxy(this.refresh,this),this._core.settings.autoRefreshInterval))},e.prototype.refresh=function(){this._core.isVisible()!==this._visible&&(this._visible=!this._visible,this._core.$element.toggleClass("owl-hidden",!this._visible),this._visible&&this._core.invalidate("width")&&this._core.refresh())},e.prototype.destroy=function(){var a,c;b.clearInterval(this._interval);for(a in this._handlers)this._core.$element.off(a,this._handlers[a]);for(c in Object.getOwnPropertyNames(this))"function"!=typeof this[c]&&(this[c]=null)},a.fn.owlCarousel.Constructor.Plugins.AutoRefresh=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(b){this._core=b,this._loaded=[],this._handlers={"initialized.owl.carousel change.owl.carousel resized.owl.carousel":a.proxy(function(b){if(b.namespace&&this._core.settings&&this._core.settings.lazyLoad&&(b.property&&"position"==b.property.name||"initialized"==b.type)){var c=this._core.settings,e=c.center&&Math.ceil(c.items/2)||c.items,f=c.center&&-1*e||0,g=(b.property&&b.property.value!==d?b.property.value:this._core.current())+f,h=this._core.clones().length,i=a.proxy(function(a,b){this.load(b)},this);for(c.lazyLoadEager>0&&(e+=c.lazyLoadEager,c.loop&&(g-=c.lazyLoadEager,e++));f++<e;)this.load(h/2+this._core.relative(g)),h&&a.each(this._core.clones(this._core.relative(g)),i),g++}},this)},this._core.options=a.extend({},e.Defaults,this._core.options),this._core.$element.on(this._handlers)};e.Defaults={lazyLoad:!1,lazyLoadEager:0},e.prototype.load=function(c){var d=this._core.$stage.children().eq(c),e=d&&d.find(".owl-lazy");!e||a.inArray(d.get(0),this._loaded)>-1||(e.each(a.proxy(function(c,d){var e,f=a(d),g=b.devicePixelRatio>1&&f.attr("data-src-retina")||f.attr("data-src")||f.attr("data-srcset");this._core.trigger("load",{element:f,url:g},"lazy"),f.is("img")?f.one("load.owl.lazy",a.proxy(function(){f.css("opacity",1),this._core.trigger("loaded",{element:f,url:g},"lazy")},this)).attr("src",g):f.is("source")?f.one("load.owl.lazy",a.proxy(function(){this._core.trigger("loaded",{element:f,url:g},"lazy")},this)).attr("srcset",g):(e=new Image,e.onload=a.proxy(function(){f.css({"background-image":'url("'+g+'")',opacity:"1"}),this._core.trigger("loaded",{element:f,url:g},"lazy")},this),e.src=g)},this)),this._loaded.push(d.get(0)))},e.prototype.destroy=function(){var a,b;for(a in this.handlers)this._core.$element.off(a,this.handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.Lazy=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(c){this._core=c,this._previousHeight=null,this._handlers={"initialized.owl.carousel refreshed.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.autoHeight&&this.update()},this),"changed.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.autoHeight&&"position"===a.property.name&&this.update()},this),"loaded.owl.lazy":a.proxy(function(a){a.namespace&&this._core.settings.autoHeight&&a.element.closest("."+this._core.settings.itemClass).index()===this._core.current()&&this.update()},this)},this._core.options=a.extend({},e.Defaults,this._core.options),this._core.$element.on(this._handlers),this._intervalId=null;var d=this;a(b).on("load",function(){d._core.settings.autoHeight&&d.update()}),a(b).resize(function(){d._core.settings.autoHeight&&(null!=d._intervalId&&clearTimeout(d._intervalId),d._intervalId=setTimeout(function(){d.update()},250))})};e.Defaults={autoHeight:!1,autoHeightClass:"owl-height"},e.prototype.update=function(){var b=this._core._current,c=b+this._core.settings.items,d=this._core.settings.lazyLoad,e=this._core.$stage.children().toArray().slice(b,c),f=[],g=0;a.each(e,function(b,c){f.push(a(c).height())}),g=Math.max.apply(null,f),g<=1&&d&&this._previousHeight&&(g=this._previousHeight),this._previousHeight=g,this._core.$stage.parent().height(g).addClass(this._core.settings.autoHeightClass)},e.prototype.destroy=function(){var a,b;for(a in this._handlers)this._core.$element.off(a,this._handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.AutoHeight=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(b){this._core=b,this._videos={},this._playing=null,this._handlers={"initialized.owl.carousel":a.proxy(function(a){a.namespace&&this._core.register({type:"state",name:"playing",tags:["interacting"]})},this),"resize.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.video&&this.isInFullScreen()&&a.preventDefault()},this),"refreshed.owl.carousel":a.proxy(function(a){a.namespace&&this._core.is("resizing")&&this._core.$stage.find(".cloned .owl-video-frame").remove()},this),"changed.owl.carousel":a.proxy(function(a){a.namespace&&"position"===a.property.name&&this._playing&&this.stop()},this),"prepared.owl.carousel":a.proxy(function(b){if(b.namespace){var c=a(b.content).find(".owl-video");c.length&&(c.css("display","none"),this.fetch(c,a(b.content)))}},this)},this._core.options=a.extend({},e.Defaults,this._core.options),this._core.$element.on(this._handlers),this._core.$element.on("click.owl.video",".owl-video-play-icon",a.proxy(function(a){this.play(a)},this))};e.Defaults={video:!1,videoHeight:!1,videoWidth:!1},e.prototype.fetch=function(a,b){var c=function(){return a.attr("data-vimeo-id")?"vimeo":a.attr("data-vzaar-id")?"vzaar":"youtube"}(),d=a.attr("data-vimeo-id")||a.attr("data-youtube-id")||a.attr("data-vzaar-id"),e=a.attr("data-width")||this._core.settings.videoWidth,f=a.attr("data-height")||this._core.settings.videoHeight,g=a.attr("href");if(!g)throw new Error("Missing video URL.");if(d=g.match(/(http:|https:|)\/\/(player.|www.|app.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com|be\-nocookie\.com)|vzaar\.com)\/(video\/|videos\/|embed\/|channels\/.+\/|groups\/.+\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/),d[3].indexOf("youtu")>-1)c="youtube";else if(d[3].indexOf("vimeo")>-1)c="vimeo";else{if(!(d[3].indexOf("vzaar")>-1))throw new Error("Video URL not supported.");c="vzaar"}d=d[6],this._videos[g]={type:c,id:d,width:e,height:f},b.attr("data-video",g),this.thumbnail(a,this._videos[g])},e.prototype.thumbnail=function(b,c){var d,e,f,g=c.width&&c.height?"width:"+c.width+"px;height:"+c.height+"px;":"",h=b.find("img"),i="src",j="",k=this._core.settings,l=function(c){e='<div class="owl-video-play-icon"></div>',d=k.lazyLoad?a("<div/>",{class:"owl-video-tn "+j,srcType:c}):a("<div/>",{class:"owl-video-tn",style:"opacity:1;background-image:url("+c+")"}),b.after(d),b.after(e)};if(b.wrap(a("<div/>",{class:"owl-video-wrapper",style:g})),this._core.settings.lazyLoad&&(i="data-src",j="owl-lazy"),h.length)return l(h.attr(i)),h.remove(),!1;"youtube"===c.type?(f="//img.youtube.com/vi/"+c.id+"/hqdefault.jpg",l(f)):"vimeo"===c.type?a.ajax({type:"GET",url:"//vimeo.com/api/v2/video/"+c.id+".json",jsonp:"callback",dataType:"jsonp",success:function(a){f=a[0].thumbnail_large,l(f)}}):"vzaar"===c.type&&a.ajax({type:"GET",url:"//vzaar.com/api/videos/"+c.id+".json",jsonp:"callback",dataType:"jsonp",success:function(a){f=a.framegrab_url,l(f)}})},e.prototype.stop=function(){this._core.trigger("stop",null,"video"),this._playing.find(".owl-video-frame").remove(),this._playing.removeClass("owl-video-playing"),this._playing=null,this._core.leave("playing"),this._core.trigger("stopped",null,"video")},e.prototype.play=function(b){var c,d=a(b.target),e=d.closest("."+this._core.settings.itemClass),f=this._videos[e.attr("data-video")],g=f.width||"100%",h=f.height||this._core.$stage.height();this._playing||(this._core.enter("playing"),this._core.trigger("play",null,"video"),e=this._core.items(this._core.relative(e.index())),this._core.reset(e.index()),c=a('<iframe frameborder="0" allowfullscreen mozallowfullscreen webkitAllowFullScreen ></iframe>'),c.attr("height",h),c.attr("width",g),"youtube"===f.type?c.attr("src","//www.youtube.com/embed/"+f.id+"?autoplay=1&rel=0&v="+f.id):"vimeo"===f.type?c.attr("src","//player.vimeo.com/video/"+f.id+"?autoplay=1"):"vzaar"===f.type&&c.attr("src","//view.vzaar.com/"+f.id+"/player?autoplay=true"),a(c).wrap('<div class="owl-video-frame" />').insertAfter(e.find(".owl-video")),this._playing=e.addClass("owl-video-playing"))},e.prototype.isInFullScreen=function(){var b=c.fullscreenElement||c.mozFullScreenElement||c.webkitFullscreenElement;return b&&a(b).parent().hasClass("owl-video-frame")},e.prototype.destroy=function(){var a,b;this._core.$element.off("click.owl.video");for(a in this._handlers)this._core.$element.off(a,this._handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.Video=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(b){this.core=b,this.core.options=a.extend({},e.Defaults,this.core.options),this.swapping=!0,this.previous=d,this.next=d,this.handlers={"change.owl.carousel":a.proxy(function(a){a.namespace&&"position"==a.property.name&&(this.previous=this.core.current(),this.next=a.property.value)},this),"drag.owl.carousel dragged.owl.carousel translated.owl.carousel":a.proxy(function(a){a.namespace&&(this.swapping="translated"==a.type)},this),"translate.owl.carousel":a.proxy(function(a){a.namespace&&this.swapping&&(this.core.options.animateOut||this.core.options.animateIn)&&this.swap()},this)},this.core.$element.on(this.handlers)};e.Defaults={animateOut:!1,
animateIn:!1},e.prototype.swap=function(){if(1===this.core.settings.items&&a.support.animation&&a.support.transition){this.core.speed(0);var b,c=a.proxy(this.clear,this),d=this.core.$stage.children().eq(this.previous),e=this.core.$stage.children().eq(this.next),f=this.core.settings.animateIn,g=this.core.settings.animateOut;this.core.current()!==this.previous&&(g&&(b=this.core.coordinates(this.previous)-this.core.coordinates(this.next),d.one(a.support.animation.end,c).css({left:b+"px"}).addClass("animated owl-animated-out").addClass(g)),f&&e.one(a.support.animation.end,c).addClass("animated owl-animated-in").addClass(f))}},e.prototype.clear=function(b){a(b.target).css({left:""}).removeClass("animated owl-animated-out owl-animated-in").removeClass(this.core.settings.animateIn).removeClass(this.core.settings.animateOut),this.core.onTransitionEnd()},e.prototype.destroy=function(){var a,b;for(a in this.handlers)this.core.$element.off(a,this.handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.Animate=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(b){this._core=b,this._call=null,this._time=0,this._timeout=0,this._paused=!0,this._handlers={"changed.owl.carousel":a.proxy(function(a){a.namespace&&"settings"===a.property.name?this._core.settings.autoplay?this.play():this.stop():a.namespace&&"position"===a.property.name&&this._paused&&(this._time=0)},this),"initialized.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.autoplay&&this.play()},this),"play.owl.autoplay":a.proxy(function(a,b,c){a.namespace&&this.play(b,c)},this),"stop.owl.autoplay":a.proxy(function(a){a.namespace&&this.stop()},this),"mouseover.owl.autoplay":a.proxy(function(){this._core.settings.autoplayHoverPause&&this._core.is("rotating")&&this.pause()},this),"mouseleave.owl.autoplay":a.proxy(function(){this._core.settings.autoplayHoverPause&&this._core.is("rotating")&&this.play()},this),"touchstart.owl.core":a.proxy(function(){this._core.settings.autoplayHoverPause&&this._core.is("rotating")&&this.pause()},this),"touchend.owl.core":a.proxy(function(){this._core.settings.autoplayHoverPause&&this.play()},this)},this._core.$element.on(this._handlers),this._core.options=a.extend({},e.Defaults,this._core.options)};e.Defaults={autoplay:!1,autoplayTimeout:5e3,autoplayHoverPause:!1,autoplaySpeed:!1},e.prototype._next=function(d){this._call=b.setTimeout(a.proxy(this._next,this,d),this._timeout*(Math.round(this.read()/this._timeout)+1)-this.read()),this._core.is("interacting")||c.hidden||this._core.next(d||this._core.settings.autoplaySpeed)},e.prototype.read=function(){return(new Date).getTime()-this._time},e.prototype.play=function(c,d){var e;this._core.is("rotating")||this._core.enter("rotating"),c=c||this._core.settings.autoplayTimeout,e=Math.min(this._time%(this._timeout||c),c),this._paused?(this._time=this.read(),this._paused=!1):b.clearTimeout(this._call),this._time+=this.read()%c-e,this._timeout=c,this._call=b.setTimeout(a.proxy(this._next,this,d),c-e)},e.prototype.stop=function(){this._core.is("rotating")&&(this._time=0,this._paused=!0,b.clearTimeout(this._call),this._core.leave("rotating"))},e.prototype.pause=function(){this._core.is("rotating")&&!this._paused&&(this._time=this.read(),this._paused=!0,b.clearTimeout(this._call))},e.prototype.destroy=function(){var a,b;this.stop();for(a in this._handlers)this._core.$element.off(a,this._handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.autoplay=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){"use strict";var e=function(b){this._core=b,this._initialized=!1,this._pages=[],this._controls={},this._templates=[],this.$element=this._core.$element,this._overrides={next:this._core.next,prev:this._core.prev,to:this._core.to},this._handlers={"prepared.owl.carousel":a.proxy(function(b){b.namespace&&this._core.settings.dotsData&&this._templates.push('<div class="'+this._core.settings.dotClass+'">'+a(b.content).find("[data-dot]").addBack("[data-dot]").attr("data-dot")+"</div>")},this),"added.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.dotsData&&this._templates.splice(a.position,0,this._templates.pop())},this),"remove.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.dotsData&&this._templates.splice(a.position,1)},this),"changed.owl.carousel":a.proxy(function(a){a.namespace&&"position"==a.property.name&&this.draw()},this),"initialized.owl.carousel":a.proxy(function(a){a.namespace&&!this._initialized&&(this._core.trigger("initialize",null,"navigation"),this.initialize(),this.update(),this.draw(),this._initialized=!0,this._core.trigger("initialized",null,"navigation"))},this),"refreshed.owl.carousel":a.proxy(function(a){a.namespace&&this._initialized&&(this._core.trigger("refresh",null,"navigation"),this.update(),this.draw(),this._core.trigger("refreshed",null,"navigation"))},this)},this._core.options=a.extend({},e.Defaults,this._core.options),this.$element.on(this._handlers)};e.Defaults={nav:!1,navText:['<span aria-label="Previous">&#x2039;</span>','<span aria-label="Next">&#x203a;</span>'],navSpeed:!1,navElement:'button type="button" role="presentation"',navContainer:!1,navContainerClass:"owl-nav",navClass:["owl-prev","owl-next"],slideBy:1,dotClass:"owl-dot",dotsClass:"owl-dots",dots:!0,dotsEach:!1,dotsData:!1,dotsSpeed:!1,dotsContainer:!1},e.prototype.initialize=function(){var b,c=this._core.settings;this._controls.$relative=(c.navContainer?a(c.navContainer):a("<div>").addClass(c.navContainerClass).appendTo(this.$element)).addClass("disabled"),this._controls.$previous=a("<"+c.navElement+">").addClass(c.navClass[0]).html(c.navText[0]).prependTo(this._controls.$relative).on("click",a.proxy(function(a){this.prev(c.navSpeed)},this)),this._controls.$next=a("<"+c.navElement+">").addClass(c.navClass[1]).html(c.navText[1]).appendTo(this._controls.$relative).on("click",a.proxy(function(a){this.next(c.navSpeed)},this)),c.dotsData||(this._templates=[a('<button role="button">').addClass(c.dotClass).append(a("<span>")).prop("outerHTML")]),this._controls.$absolute=(c.dotsContainer?a(c.dotsContainer):a("<div>").addClass(c.dotsClass).appendTo(this.$element)).addClass("disabled"),this._controls.$absolute.on("click","button",a.proxy(function(b){var d=a(b.target).parent().is(this._controls.$absolute)?a(b.target).index():a(b.target).parent().index();b.preventDefault(),this.to(d,c.dotsSpeed)},this));for(b in this._overrides)this._core[b]=a.proxy(this[b],this)},e.prototype.destroy=function(){var a,b,c,d,e;e=this._core.settings;for(a in this._handlers)this.$element.off(a,this._handlers[a]);for(b in this._controls)"$relative"===b&&e.navContainer?this._controls[b].html(""):this._controls[b].remove();for(d in this.overides)this._core[d]=this._overrides[d];for(c in Object.getOwnPropertyNames(this))"function"!=typeof this[c]&&(this[c]=null)},e.prototype.update=function(){var a,b,c,d=this._core.clones().length/2,e=d+this._core.items().length,f=this._core.maximum(!0),g=this._core.settings,h=g.center||g.autoWidth||g.dotsData?1:g.dotsEach||g.items;if("page"!==g.slideBy&&(g.slideBy=Math.min(g.slideBy,g.items)),g.dots||"page"==g.slideBy)for(this._pages=[],a=d,b=0,c=0;a<e;a++){if(b>=h||0===b){if(this._pages.push({start:Math.min(f,a-d),end:a-d+h-1}),Math.min(f,a-d)===f)break;b=0,++c}b+=this._core.mergers(this._core.relative(a))}},e.prototype.draw=function(){var b,c=this._core.settings,d=this._core.items().length<=c.items,e=this._core.relative(this._core.current()),f=c.loop||c.rewind;this._controls.$relative.toggleClass("disabled",!c.nav||d),c.nav&&(this._controls.$previous.toggleClass("disabled",!f&&e<=this._core.minimum(!0)),this._controls.$next.toggleClass("disabled",!f&&e>=this._core.maximum(!0))),this._controls.$absolute.toggleClass("disabled",!c.dots||d),c.dots&&(b=this._pages.length-this._controls.$absolute.children().length,c.dotsData&&0!==b?this._controls.$absolute.html(this._templates.join("")):b>0?this._controls.$absolute.append(new Array(b+1).join(this._templates[0])):b<0&&this._controls.$absolute.children().slice(b).remove(),this._controls.$absolute.find(".active").removeClass("active"),this._controls.$absolute.children().eq(a.inArray(this.current(),this._pages)).addClass("active"))},e.prototype.onTrigger=function(b){var c=this._core.settings;b.page={index:a.inArray(this.current(),this._pages),count:this._pages.length,size:c&&(c.center||c.autoWidth||c.dotsData?1:c.dotsEach||c.items)}},e.prototype.current=function(){var b=this._core.relative(this._core.current());return a.grep(this._pages,a.proxy(function(a,c){return a.start<=b&&a.end>=b},this)).pop()},e.prototype.getPosition=function(b){var c,d,e=this._core.settings;return"page"==e.slideBy?(c=a.inArray(this.current(),this._pages),d=this._pages.length,b?++c:--c,c=this._pages[(c%d+d)%d].start):(c=this._core.relative(this._core.current()),d=this._core.items().length,b?c+=e.slideBy:c-=e.slideBy),c},e.prototype.next=function(b){a.proxy(this._overrides.to,this._core)(this.getPosition(!0),b)},e.prototype.prev=function(b){a.proxy(this._overrides.to,this._core)(this.getPosition(!1),b)},e.prototype.to=function(b,c,d){var e;!d&&this._pages.length?(e=this._pages.length,a.proxy(this._overrides.to,this._core)(this._pages[(b%e+e)%e].start,c)):a.proxy(this._overrides.to,this._core)(b,c)},a.fn.owlCarousel.Constructor.Plugins.Navigation=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){"use strict";var e=function(c){this._core=c,this._hashes={},this.$element=this._core.$element,this._handlers={"initialized.owl.carousel":a.proxy(function(c){c.namespace&&"URLHash"===this._core.settings.startPosition&&a(b).trigger("hashchange.owl.navigation")},this),"prepared.owl.carousel":a.proxy(function(b){if(b.namespace){var c=a(b.content).find("[data-hash]").addBack("[data-hash]").attr("data-hash");if(!c)return;this._hashes[c]=b.content}},this),"changed.owl.carousel":a.proxy(function(c){if(c.namespace&&"position"===c.property.name){var d=this._core.items(this._core.relative(this._core.current())),e=a.map(this._hashes,function(a,b){return a===d?b:null}).join();if(!e||b.location.hash.slice(1)===e)return;b.location.hash=e}},this)},this._core.options=a.extend({},e.Defaults,this._core.options),this.$element.on(this._handlers),a(b).on("hashchange.owl.navigation",a.proxy(function(a){var c=b.location.hash.substring(1),e=this._core.$stage.children(),f=this._hashes[c]&&e.index(this._hashes[c]);f!==d&&f!==this._core.current()&&this._core.to(this._core.relative(f),!1,!0)},this))};e.Defaults={URLhashListener:!1},e.prototype.destroy=function(){var c,d;a(b).off("hashchange.owl.navigation");for(c in this._handlers)this._core.$element.off(c,this._handlers[c]);for(d in Object.getOwnPropertyNames(this))"function"!=typeof this[d]&&(this[d]=null)},a.fn.owlCarousel.Constructor.Plugins.Hash=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){function e(b,c){var e=!1,f=b.charAt(0).toUpperCase()+b.slice(1);return a.each((b+" "+h.join(f+" ")+f).split(" "),function(a,b){if(g[b]!==d)return e=!c||b,!1}),e}function f(a){return e(a,!0)}var g=a("<support>").get(0).style,h="Webkit Moz O ms".split(" "),i={transition:{end:{WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd",transition:"transitionend"}},animation:{end:{WebkitAnimation:"webkitAnimationEnd",MozAnimation:"animationend",OAnimation:"oAnimationEnd",animation:"animationend"}}},j={csstransforms:function(){return!!e("transform")},csstransforms3d:function(){return!!e("perspective")},csstransitions:function(){return!!e("transition")},cssanimations:function(){return!!e("animation")}};j.csstransitions()&&(a.support.transition=new String(f("transition")),a.support.transition.end=i.transition.end[a.support.transition]),j.cssanimations()&&(a.support.animation=new String(f("animation")),a.support.animation.end=i.animation.end[a.support.animation]),j.csstransforms()&&(a.support.transform=new String(f("transform")),a.support.transform3d=j.csstransforms3d())}(window.Zepto||window.jQuery,window,document);
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

/*! Copyright (c) 2011 Piotr Rochala (http://rocha.la)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 1.3.8
 *
 */
(function($) {

  $.fn.extend({
    slimScroll: function(options) {

      var defaults = {

        // width in pixels of the visible scroll area
        width : 'auto',

        // height in pixels of the visible scroll area
        height : '250px',

        // width in pixels of the scrollbar and rail
        size : '7px',

        // scrollbar color, accepts any hex/color value
        color: '#000',

        // scrollbar position - left/right
        position : 'right',

        // distance in pixels between the side edge and the scrollbar
        distance : '1px',

        // default scroll position on load - top / bottom / $('selector')
        start : 'top',

        // sets scrollbar opacity
        opacity : .4,

        // enables always-on mode for the scrollbar
        alwaysVisible : false,

        // check if we should hide the scrollbar when user is hovering over
        disableFadeOut : false,

        // sets visibility of the rail
        railVisible : false,

        // sets rail color
        railColor : '#333',

        // sets rail opacity
        railOpacity : .2,

        // whether  we should use jQuery UI Draggable to enable bar dragging
        railDraggable : true,

        // defautlt CSS class of the slimscroll rail
        railClass : 'slimScrollRail',

        // defautlt CSS class of the slimscroll bar
        barClass : 'slimScrollBar',

        // defautlt CSS class of the slimscroll wrapper
        wrapperClass : 'slimScrollDiv',

        // check if mousewheel should scroll the window if we reach top/bottom
        allowPageScroll : false,

        // scroll amount applied to each mouse wheel step
        wheelStep : 20,

        // scroll amount applied when user is using gestures
        touchScrollStep : 200,

        // sets border radius
        borderRadius: '7px',

        // sets border radius of the rail
        railBorderRadius : '7px'
      };

      var o = $.extend(defaults, options);

      // do it for every element that matches selector
      this.each(function(){

      var isOverPanel, isOverBar, isDragg, queueHide, touchDif,
        barHeight, percentScroll, lastScroll,
        divS = '<div></div>',
        minBarHeight = 30,
        releaseScroll = false;

        // used in event handlers and for better minification
        var me = $(this);

        // ensure we are not binding it again
        if (me.parent().hasClass(o.wrapperClass))
        {
            // start from last bar position
            var offset = me.scrollTop();

            // find bar and rail
            bar = me.siblings('.' + o.barClass);
            rail = me.siblings('.' + o.railClass);

            getBarHeight();

            // check if we should scroll existing instance
            if ($.isPlainObject(options))
            {
              // Pass height: auto to an existing slimscroll object to force a resize after contents have changed
              if ( 'height' in options && options.height == 'auto' ) {
                me.parent().css('height', 'auto');
                me.css('height', 'auto');
                var height = me.parent().parent().height();
                me.parent().css('height', height);
                me.css('height', height);
              } else if ('height' in options) {
                var h = options.height;
                me.parent().css('height', h);
                me.css('height', h);
              }

              if ('scrollTo' in options)
              {
                // jump to a static point
                offset = parseInt(o.scrollTo);
              }
              else if ('scrollBy' in options)
              {
                // jump by value pixels
                offset += parseInt(o.scrollBy);
              }
              else if ('destroy' in options)
              {
                // remove slimscroll elements
                bar.remove();
                rail.remove();
                me.unwrap();
                return;
              }

              // scroll content by the given offset
              scrollContent(offset, false, true);
            }

            return;
        }
        else if ($.isPlainObject(options))
        {
            if ('destroy' in options)
            {
            	return;
            }
        }

        // optionally set height to the parent's height
        o.height = (o.height == 'auto') ? me.parent().height() : o.height;

        // wrap content
        var wrapper = $(divS)
          .addClass(o.wrapperClass)
          .css({
            position: 'relative',
            overflow: 'hidden',
            width: o.width,
            height: o.height
          });

        // update style for the div
        me.css({
          overflow: 'hidden',
          width: o.width,
          height: o.height
        });

        // create scrollbar rail
        var rail = $(divS)
          .addClass(o.railClass)
          .css({
            width: o.size,
            height: '100%',
            position: 'absolute',
            top: 0,
            display: (o.alwaysVisible && o.railVisible) ? 'block' : 'none',
            'border-radius': o.railBorderRadius,
            background: o.railColor,
            opacity: o.railOpacity,
            zIndex: 90
          });

        // create scrollbar
        var bar = $(divS)
          .addClass(o.barClass)
          .css({
            background: o.color,
            width: o.size,
            position: 'absolute',
            top: 0,
            opacity: o.opacity,
            display: o.alwaysVisible ? 'block' : 'none',
            'border-radius' : o.borderRadius,
            BorderRadius: o.borderRadius,
            MozBorderRadius: o.borderRadius,
            WebkitBorderRadius: o.borderRadius,
            zIndex: 99
          });

        // set position
        var posCss = (o.position == 'right') ? { right: o.distance } : { left: o.distance };
        rail.css(posCss);
        bar.css(posCss);

        // wrap it
        me.wrap(wrapper);

        // append to parent div
        me.parent().append(bar);
        me.parent().append(rail);

        // make it draggable and no longer dependent on the jqueryUI
        if (o.railDraggable){
          bar.bind("mousedown", function(e) {
            var $doc = $(document);
            isDragg = true;
            t = parseFloat(bar.css('top'));
            pageY = e.pageY;

            $doc.bind("mousemove.slimscroll", function(e){
              currTop = t + e.pageY - pageY;
              bar.css('top', currTop);
              scrollContent(0, bar.position().top, false);// scroll content
            });

            $doc.bind("mouseup.slimscroll", function(e) {
              isDragg = false;hideBar();
              $doc.unbind('.slimscroll');
            });
            return false;
          }).bind("selectstart.slimscroll", function(e){
            e.stopPropagation();
            e.preventDefault();
            return false;
          });
        }

        // on rail over
        rail.hover(function(){
          showBar();
        }, function(){
          hideBar();
        });

        // on bar over
        bar.hover(function(){
          isOverBar = true;
        }, function(){
          isOverBar = false;
        });

        // show on parent mouseover
        me.hover(function(){
          isOverPanel = true;
          showBar();
          hideBar();
        }, function(){
          isOverPanel = false;
          hideBar();
        });

        // support for mobile
        me.bind('touchstart', function(e,b){
          if (e.originalEvent.touches.length)
          {
            // record where touch started
            touchDif = e.originalEvent.touches[0].pageY;
          }
        });

        me.bind('touchmove', function(e){
          // prevent scrolling the page if necessary
          if(!releaseScroll)
          {
  		      e.originalEvent.preventDefault();
		      }
          if (e.originalEvent.touches.length)
          {
            // see how far user swiped
            var diff = (touchDif - e.originalEvent.touches[0].pageY) / o.touchScrollStep;
            // scroll content
            scrollContent(diff, true);
            touchDif = e.originalEvent.touches[0].pageY;
          }
        });

        // set up initial height
        getBarHeight();

        // check start position
        if (o.start === 'bottom')
        {
          // scroll content to bottom
          bar.css({ top: me.outerHeight() - bar.outerHeight() });
          scrollContent(0, true);
        }
        else if (o.start !== 'top')
        {
          // assume jQuery selector
          scrollContent($(o.start).position().top, null, true);

          // make sure bar stays hidden
          if (!o.alwaysVisible) { bar.hide(); }
        }

        // attach scroll events
        attachWheel(this);

        function _onWheel(e)
        {
          // use mouse wheel only when mouse is over
          if (!isOverPanel) { return; }

          var e = e || window.event;

          var delta = 0;
          if (e.wheelDelta) { delta = -e.wheelDelta/120; }
          if (e.detail) { delta = e.detail / 3; }

          var target = e.target || e.srcTarget || e.srcElement;
          if ($(target).closest('.' + o.wrapperClass).is(me.parent())) {
            // scroll content
            scrollContent(delta, true);
          }

          // stop window scroll
          if (e.preventDefault && !releaseScroll) { e.preventDefault(); }
          if (!releaseScroll) { e.returnValue = false; }
        }

        function scrollContent(y, isWheel, isJump)
        {
          releaseScroll = false;
          var delta = y;
          var maxTop = me.outerHeight() - bar.outerHeight();

          if (isWheel)
          {
            // move bar with mouse wheel
            delta = parseInt(bar.css('top')) + y * parseInt(o.wheelStep) / 100 * bar.outerHeight();

            // move bar, make sure it doesn't go out
            delta = Math.min(Math.max(delta, 0), maxTop);

            // if scrolling down, make sure a fractional change to the
            // scroll position isn't rounded away when the scrollbar's CSS is set
            // this flooring of delta would happened automatically when
            // bar.css is set below, but we floor here for clarity
            delta = (y > 0) ? Math.ceil(delta) : Math.floor(delta);

            // scroll the scrollbar
            bar.css({ top: delta + 'px' });
          }

          // calculate actual scroll amount
          //percentScroll = parseInt(bar.css('top')) / (me.outerHeight() - bar.outerHeight());
          percentScroll = parseInt(bar.css('top')) / (parseInt(me.outerHeight()) - parseInt(bar.outerHeight()));

          delta = percentScroll * (me[0].scrollHeight - me.outerHeight());


          if (isJump)
          {
            delta = y;
            var offsetTop = delta / me[0].scrollHeight * me.outerHeight();
            offsetTop = Math.min(Math.max(offsetTop, 0), maxTop);
            bar.css({ top: offsetTop + 'px' });
          }

          // scroll content
          me.scrollTop(delta);

          // fire scrolling event
          me.trigger('slimscrolling', ~~delta);

          // ensure bar is visible
          showBar();

          // trigger hide when scroll is stopped
          hideBar();
        }

        function attachWheel(target)
        {
          if (window.addEventListener)
          {
            target.addEventListener('DOMMouseScroll', _onWheel, false );
            target.addEventListener('mousewheel', _onWheel, false );
          }
          else
          {
            document.attachEvent("onmousewheel", _onWheel)
          }
        }

        function getBarHeight()
        {
          // calculate scrollbar height and make sure it is not too small
          barHeight = Math.max((me.outerHeight() / me[0].scrollHeight) * me.outerHeight(), minBarHeight);
          bar.css({ height: barHeight + 'px' });

          // hide scrollbar if content is not long enough
          var display = barHeight == me.outerHeight() ? 'none' : 'block';
          bar.css({ display: display });
        }

        function showBar()
        {
          // recalculate bar height
          getBarHeight();
          clearTimeout(queueHide);

          // when bar reached top or bottom
          if (percentScroll == ~~percentScroll)
          {
            //release wheel
            releaseScroll = o.allowPageScroll;

            // publish approporiate event
            if (lastScroll != percentScroll)
            {
                var msg = (~~percentScroll == 0) ? 'top' : 'bottom';
                me.trigger('slimscroll', msg);
            }
          }
          else
          {
            releaseScroll = false;
          }
          lastScroll = percentScroll;

          // show only when required
          if(barHeight >= me.outerHeight()) {
            //allow window scroll
            releaseScroll = true;
            return;
          }
          bar.stop(true,true).fadeIn('fast');
          if (o.railVisible) { rail.stop(true,true).fadeIn('fast'); }
        }

        function hideBar()
        {
          // only hide when options allow it
          if (!o.alwaysVisible)
          {
            queueHide = setTimeout(function(){
              if (!(o.disableFadeOut && isOverPanel) && !isOverBar && !isDragg)
              {
                bar.fadeOut('slow');
                rail.fadeOut('slow');
              }
            }, 1000);
          }
        }

      });

      // maintain chainability
      return this;
    }
  });

  $.fn.extend({
    slimscroll: $.fn.slimScroll
  });

})(jQuery);

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

	nsp.fn.VimeoPlayer = (function(){
		function VimeoPlayer(params){
			nsp.EventDispatcher.call(this);
			this.current = 0;
			this.isUserInteracted = false;
			this.nativePlayer;
			this.initialized;
			this.params = {
				container:null,
				list:null,
	   		};
		}
		Object.assign(VimeoPlayer.prototype,nsp.EventDispatcher.prototype);

		VimeoPlayer.prototype.init = function(params){

			if(this.initialized) return;

			nsp.utilis.merge(this.params,params);

			var player = new Vimeo.Player('coa-player-cover');
            player.setVolume(1);
			this.nativePlayer = player;
            player.on('play', () =>{
            	console.log('video courant',this.current)
            });

            player.on('ended', () =>{
            	if(this.current == this.params.list.length){
            		
            	}
            	else{
            		this.current++;

            		this.params.list.each((index,el)=>{
            			if(index == this.current){
            				$(el).trigger('click');
            			}
            		})
            	}
            });

            player.on('loaded', ()=> {

            	if(this.isUserInteracted){
                	this.nativePlayer.play();
            	}
                else{
                    if(this.current == 0){
                        this.params.container.removeClass('d-none');
                    }
                }
            });

            this.params.container.find("button.btn").on({
                click:(e)=>{
                    e.preventDefault();
                    this.params.container
                    .removeClass('fix')
                    .addClass('fix-rem');

                    setTimeout(()=>{
                        this.params.container.removeClass('fix-rem');
                    },500)
                }
            });

            this.params.list.each((index,el)=>{
            	let obj = $(el);
            	obj.on({
	                click:(e)=>{
	                    e.preventDefault();
	                    this.selectListItem(index);
	                    this.isUserInteracted = true;
	                    this.loadVideo(obj.data('id'));
	                }
	            });
            });

            this.initialized = true;
            this.emit(new nsp.Event('initialized'));
		};

		VimeoPlayer.prototype.selectListItem = function(index){
			this.current = index;
			this.params.list.each((i,el)=>{
            	let obj = $(el);
            	if(i == index){
            		obj.addClass('active');
            		return;
            	}
            	else{
            		obj.removeClass('active');
            	}
            });
		}

		VimeoPlayer.prototype.loadVideo = function(id){

			var promise = this.nativePlayer.loadVideo(id).then((id)=> {
                
            }).catch(function(error) {

                switch (error.name) {
                    case 'TypeError':
                        // the id was not a number
                        break;

                    case 'PasswordError':
                        // the video is password-protected and the viewer needs to enter the
                        // password first
                        break;

                    case 'PrivacyError':
                        // the video is password-protected or private
                        break;

                    default:
                        // some other error occurred
                        break;
                }
            });

            return promise;
		}

		return VimeoPlayer;
	})();

	nsp.fn.MovieSingleView = (function(){
		function MovieSingleView(params){
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

		Object.assign(MovieSingleView.prototype, nsp.View.prototype);

		MovieSingleView.prototype.controller = function(){
			let mainMenu = $("#master-menu");
            var sections = $(".block");
            var win = $(window);
            var movieProfil = $("#movie-profil");
            var masterCover = $("#master-cover");
            var otherMovies = $("#other-movies");

            win.on({
                scroll:function(e){

                    let pos = $(this).scrollTop();
                    if(pos >= 460){
                        mainMenu.addClass('active');
                        movieProfil.addClass('active');
                    }
                    else if(pos <= 120){
                        mainMenu.removeClass('active');
                    }
                    else if(pos <= 360){
                        movieProfil.removeClass('active');
                    }

                    if(otherMovies.offset().top <= (pos+500)){
                        movieProfil.addClass('reduced');
                    }
                    else{
                        movieProfil.removeClass('reduced');
                    }

                    sections.each(function(i,el){
                        let obj = $(el);
                        let top = obj.offset().top;
                        let delta = top - pos;
                        if(delta <= 600 && !obj.hasClass('section-animation')){
                            obj.addClass('section-animation');
                            //console.log(`section ${i}: top: ${top}, scrollTop: ${pos}`)
                        }
                    });
                }
            });

            $(".has-carousel").each(function(i,el){
                let obj = $(el);
                let owl = obj.find(".owl-carousel");

                obj.find(".slide-nav.nav-n, .slide-nav.nav-p").on({
                    click:function(e){
                        e.preventDefault();
                        if($(this).hasClass('nav-n')){
                            owl.trigger('next.owl.carousel');
                        }
                        else{
                            owl.trigger('prev.owl.carousel');
                        }
                    }
                });

                let opts = {
                    loop:false,
                    //margin:3,
                };

                if(obj.attr('id') == "block-acteurs"){
                    opts.items = 4;
                    opts.margin = 4;
                    opts.autoplay = true;
                    opts.autoplayTimeout = 2000;
                }
                else if(obj.attr('id') == "other-movies"){
                    opts.items = 6;
                    opts.margin = 10;
                }
                else{
                    opts.items = 3;
                    opts.margin = 15;
                }


                if(owl.hasClass('vp_center_slider')){

                    opts.items = 1;
                    opts.autoplay = true;
                    opts.loop = false;

                }

                owl.owlCarousel(opts);
            });

            if(win.scrollTop() >= 400){
                win.trigger('scroll')
            }

            function onSliderResize(e){
               this.redrawSlider();
               this.reloadSlider()
            }

            $(".prs_vp_left_slider").bxSlider({
                minSlides: 1,
                autoDirection: 'next',
                mode: 'vertical',
                maxSlides: 10,
                slideMargin: 17,
                ticker: true,
                tickerHover: true,
                speed: 15000,
                useCSS: false, 
                infiniteLoop: false,
                onSliderResize:onSliderResize,
            });

            $(".prs_vp_right_slider").bxSlider({
                minSlides: 1,
                mode: 'vertical',
                autoDirection: 'prev',
                maxSlides: 10,
                slideMargin: 17,
                ticker: true,
                tickerHover: true,
                speed: 15000,
                useCSS: false, 
                infiniteLoop: false,
                onSliderResize:onSliderResize,
            });

            /* 09. VENOBOX JS */
            $('.venobox').venobox({
                numeratio: true,
                titleattr: 'data-title',
                titlePosition: 'top',
                spinner: 'wandering-cubes',
                spinColor: '#007bff',
                autoplay:true,
            });

            $('[data-toggle=tooltip]').tooltip();

            /*var votePlug = new nsp.plugins.Voting();
            votePlug.init();*/

            if( Vimeo && $("#movie-player").length ){
                var player = AdminManager.container.get('VimeoPlayer');
                player.init({
                    container:$(".coa-player-thumbnail"),
                    list:$(".coa-player .coa-player-thumbnail div > a")
                });
            }

            var toSlim = $(".coa-player-thumbnail > div:first");

            toSlim.slimscroll({
                color:"#dc3545",
                height:"100%",
            })
            .on('slimscroll', function(e, pos){
                console.log(pos)
            });

			return this;
		}
		return MovieSingleView;
	})();

})(AdminManager);
var AdminManager = AdminManager || {};

(function(nsp){

	nsp.plugins.Voting = (function(){
		function Voting(params){
			nsp.View.call(this,params);
            this.stars = [];

	       this.vars({
                selector:".voting-plug"
            });
		};

		Object.assign(Voting.prototype, nsp.View.prototype);

        Voting.prototype.init = function(params){
            this.vars(params);
            this.controller();
            return this;
        }

		Voting.prototype.controller = function(){
            var stars = $(this.params.selector+' > a > i');
            this.stars = stars;
            stars.each((i,el)=>{
                $(el).on({
                    mouseenter:(e)=>{
                        this.update(i);
                    },
                    mouseleave:(e)=>{
                        this.update(i);
                    }
                });
            });

			return this;
		}

        Voting.prototype.update =  function (pos){
            this.stars.each((i,el)=>{
                let star = $(el);

                if(i <= pos){
                    star.removeClass('far').addClass('fas');
                }
                else{
                    star.removeClass('fas').addClass('far');
                }
            });
        }

		return Voting;
	})();

})(AdminManager);
$(document).ready(function($){
    var nsp = AdminManager;
    var view = AdminManager.container.get('MovieSingleView');
    view.controller();

    view.subscribe(event=>{

        
    });
});