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