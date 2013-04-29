<?php 

c('Begin style_header.php',2);
global $dc_options;

?>


<STYLE type="text/css">

#dc-content,.bigMenu{<?php if($dc_options['mainSidebar']=='left'){ ?>float:right;<?php } else { ?>float:left;<?php } ?>}
#sidebar{<?php if($dc_options['mainSidebar']=='left'){ ?>float:left;<?php } else { ?>float:right;<?php } ?>}

a {color: <?php echo $dc_options['primaryColor']; ?>;}
a:hover {color: <?php echo $dc_options['primaryColorFaded']; ?>;}
.primaryColor {color: <?php echo $dc_options['primaryColor']; ?>;}

::-moz-selection{background: <?php echo $dc_options['selectionColor']; ?>;color: <?php echo $dc_options['selectionTextColor']; ?>;}
::selection {background: <?php echo $dc_options['selectionColor']; ?>;color: <?php echo $dc_options['selectionTextColor']; ?>;} 
a:link {-webkit-tap-highlight-color: <?php echo $dc_options['selectionColor']; ?>;}
ins {background-color: <?php echo $dc_options['selectionColor']; ?>;}
mark {background-color: <?php echo $dc_options['selectionColor']; ?>;}

ol.commentlist li.comment ul.children li.depth-2 {border-color:#555;}
ol.commentlist li.comment ul.children li.depth-3 {border-color:#999;}
ol.commentlist li.comment ul.children li.depth-4 {border-color:#bbb;}

<?php echo $dc_options['cssOverrides']; ?>

</STYLE>

<?php c('End style_header.php',3) ?>
