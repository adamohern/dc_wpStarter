<?php c('Begin searchform.php',2); ?>
<form action="<?php bloginfo('siteurl'); ?>" id="searchform" method="get">
<div>
<label id="sLabel" for="s" class="screen-reader-text">Search:</label>
<input type="search" id="s" name="s" value="Search..." onfocus="if (this.value == 'Search...') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search...';}"/>
<input type="submit" value="Go" id="searchsubmit" />
</div>
</form>
<?php c('End searchform.php',2); ?>
