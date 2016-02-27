<?php
/**
 * @package WordPress
 * @subpackage velocity_Theme
 */
?>
<?php $velocity_searchfieldtext = __('Search the site...', 'velocity'); ?>
<div><form class="searchform" method="get" action="<?php echo home_url(); ?>/"><input name="s" type="text" placeholder="<?php echo $velocity_searchfieldtext ?>" /></form></div>