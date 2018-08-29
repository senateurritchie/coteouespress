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
            
			return this;
		}
		return MovieSingleView;
	})();

})(AdminManager);