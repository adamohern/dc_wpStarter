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

/* trigger when page is ready */
jQuery(document).ready(function (){

		jQuery(function() {
			jQuery( ".accordion" ).accordion();
		});
		jQuery(function() {
			jQuery( ".tabs" ).tabs();
		});
		jQuery( ".toggle" ).click(
			function(){
				jQuery(this).next().slideToggle();
			}
		);


	/* .misc ---------------------------------*/

		// When the document is fully-loaded (and only then!), we display the page.
		jQuery('#everything').fadeIn('fast');

});


/* optional triggers
jQuery(window).load(function() {

});


jQuery(window).resize(function() {

});*/
