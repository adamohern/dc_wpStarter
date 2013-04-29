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

						
	/* .misc ---------------------------------*/
				
		// When the document is fully-loaded (and only then!), we display the page.  
		$('#everything').fadeIn('fast');
		
});


/* optional triggers
$(window).load(function() {

});


$(window).resize(function() {

});*/