<?php 

c('Begin style_header.php',2);
global $evd_options;

?>


<STYLE type="text/css">

#evd-content,.bigMenu{<?php if($evd_options['evd_mainSidebar']=='left'){ ?>float:right;<?php } else { ?>float:left;<?php } ?>}
#sidebar{<?php if($evd_options['evd_mainSidebar']=='left'){ ?>float:left;<?php } else { ?>float:right;<?php } ?>}

a {color: <?php echo $evd_options['evd_primaryColor']; ?>;}
a:hover {color: <?php echo $evd_options['evd_primaryColorFaded']; ?>;}
.primaryColor {color: <?php echo $evd_options['evd_primaryColor']; ?>;}

::-moz-selection{background: <?php echo $evd_options['evd_selectionColor']; ?>;color: <?php echo $evd_options['evd_selectionTextColor']; ?>;}
::selection {background: <?php echo $evd_options['evd_selectionColor']; ?>;color: <?php echo $evd_options['evd_selectionTextColor']; ?>;} 
a:link {-webkit-tap-highlight-color: <?php echo $evd_options['evd_selectionColor']; ?>;}
ins {background-color: <?php echo $evd_options['evd_selectionColor']; ?>;}
mark {background-color: <?php echo $evd_options['evd_selectionColor']; ?>;}

ol.commentlist li.comment ul.children li.depth-2 {border-color:#555;}
ol.commentlist li.comment ul.children li.depth-3 {border-color:#999;}
ol.commentlist li.comment ul.children li.depth-4 {border-color:#bbb;}

<?php echo $evd_options['evd_cssOverrides']; ?>

</STYLE>

<?php c('End style_header.php',3) ?>
