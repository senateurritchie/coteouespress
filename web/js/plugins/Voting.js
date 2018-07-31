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