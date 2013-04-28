$(document).ready(function (){
		
	$('#headerContent a[href="http://cadjunkie.com"]').wrap('<div class="hover" />').parent().parent().addClass('cadjunkie background');
	$('#headerContent a[href="http://solidsmack.com"]').wrap('<div class="hover" />').parent().parent().addClass('solidsmack background');
	$('#headerContent a[href="http://engineervsdesigner.com"]').wrap('<div class="hover" />').parent().parent().addClass('evd background');
	$('#headerContent a[href="http://evd1.tv"]').wrap('<div class="hover" />').parent().parent().addClass('evdm background');
	$('#headerContent .hover').hoverIntent(
			function(){ $(this).fadeTo('5',1) }, 
			function(){ $(this).fadeTo('5',0); }
	);
	
	
	
	
	// make sidebar <li>'s clickable ---------------------------------
	// ---------------------------------------------------------------
		function open(link,menu) {
			if ($(menu).length != 0){
				var n = $(menu).queue();
				if (!n.length) {
					
					// admittedly messy: makes the #windowshade div either as tall as the menu 
					// popping up or the document, whichever is bigger.
					// This function is a duplicate of the one found in EvD_HTML5_Reset's functions.js file
					var h=0;
					h = $(document).height();
					if (h < $(menu).height()+400) {h=$(menu).height()+400;}
					$('#windowShade').css('height',h+'px');
					$('#evd-content').fadeOut('fast');
					$('div[id ^= "bigMenu"]').removeClass('active').fadeOut('fast');
					$('a[href ^= "#bigMenu"]').removeClass('active');
					$('#windowShade').fadeIn('fast');
					$(menu).delay(200).fadeIn('fast').addClass('active');
					$(link).addClass('active');
					$('#page-wrap').addClass('active');
				}
			}
		}
	
		function doLink(a,url){
				if (url) { 
						if(url.startsWith('#bigMenu')) { open(a,url); }
						else { window.location.href = url; }
				}
		}
		
		
		// make entire sidebar <li>'s clickable
		$('#sidebar .widget li li, article .evdButton').click( function(){ 
			var a = $(this).find('a:first');
			var url = a.attr('href');
			doLink(a,url);
		});
	
});