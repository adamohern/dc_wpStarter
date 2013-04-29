// adapted from HTML5 Reset (http://html5reset.org/)

// remap jQuery to $
(function($){})(window.jquery);

/* trigger when page is ready */
$(document).ready(function (){
	
		$(function() {
			$( ".accordion" ).accordion();
		});
		$(function() {
			$( ".tabs" ).tabs();
		});
		$( ".toggle" ).click(
			function(){
				$(this).next().slideToggle();
			}
		);
		$(function() {
			$( ".camera" ).camera({
				thumbnails:true
			});
		});
	
		// Only display the #noscriptMessage if the script isn't running.
		$('#noscriptMessage').hide();
		
		/* #headerWrap animations ---------------------------------*/
		
		// Animate the header (if its enabled, of course!)
		$('#headerWrap.animate').css("height",60);
		$('#headerWrap.animate').hoverIntent(
				function(){ $(this).animate({height:'80px'},{duration:200}); }, 
				function(){ $(this).animate({height:'60px'},{duration:200}); }
		);
		
		// If a header li element contains a link, fade to the '.hover' class.
		$('#headerWrap li').hover( 
				function(){ 
						var url = $(this).children('a:first').attr('href');
						if (url) { $(this).addClass('hover'); }
				},
				function(){ $(this).removeClass('hover'); }
		);
		
		// If a header li element contains a link, make the entire li clickable.
		$('#headerWrap li').click( function(){ 
				var url = $(this).find('a:first').attr('href');
				if (url) { window.location.href = url }
		});

						
	/* .misc ---------------------------------*/
				
		// When the document is fully-loaded (and only then!), we display the page.  
		$('#everything').fadeIn('fast');
		
});



/* optional triggers
$(window).load(function() {

});


$(window).resize(function() {

});*/

/**
* hoverIntent r6 // 2011.02.26 // jQuery 1.5.1+
* <http://cherne.net/brian/resources/jquery.hoverIntent.html>
* 
* @param  f  onMouseOver function || An object with configuration options
* @param  g  onMouseOut function  || Nothing (use configuration options object)
* @author    Brian Cherne brian(at)cherne(dot)net
*/
(function($){$.fn.hoverIntent=function(f,g){var cfg={sensitivity:7,interval:100,timeout:0};cfg=$.extend(cfg,g?{over:f,out:g}:f);var cX,cY,pX,pY;var track=function(ev){cX=ev.pageX;cY=ev.pageY};var compare=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);if((Math.abs(pX-cX)+Math.abs(pY-cY))<cfg.sensitivity){$(ob).unbind("mousemove",track);ob.hoverIntent_s=1;return cfg.over.apply(ob,[ev])}else{pX=cX;pY=cY;ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}};var delay=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);ob.hoverIntent_s=0;return cfg.out.apply(ob,[ev])};var handleHover=function(e){var ev=jQuery.extend({},e);var ob=this;if(ob.hoverIntent_t){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t)}if(e.type=="mouseenter"){pX=ev.pageX;pY=ev.pageY;$(ob).bind("mousemove",track);if(ob.hoverIntent_s!=1){ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}}else{$(ob).unbind("mousemove",track);if(ob.hoverIntent_s==1){ob.hoverIntent_t=setTimeout(function(){delay(ev,ob)},cfg.timeout)}}};return this.bind('mouseenter',handleHover).bind('mouseleave',handleHover)}})(jQuery);