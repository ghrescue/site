<?php
/* ------------------------------------- */
/* THEME LOCALIZATION */
/* ------------------------------------- */

$velocity_themepath = get_template_directory();

load_theme_textdomain( 'velocity', $velocity_themepath.'/lang' );
$velocity_locale = get_locale();
$velocity_locale_file = $velocity_themepath."/lang/$velocity_locale.php";
if ( is_readable($velocity_locale_file) ) 
require_once($velocity_locale_file);
?>