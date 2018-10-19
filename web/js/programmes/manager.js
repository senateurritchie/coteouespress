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