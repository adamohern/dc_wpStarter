// From HTML5 Boilerplate:

// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

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