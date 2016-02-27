<?php
add_action( 'wp_dashboard_setup', 'velocity_dashboard_setup_function' );
function velocity_dashboard_setup_function() {
    add_meta_box( 'docu_dashboard_widget', 'Velocity Help', 'velocity_docu_dashboard_widget_function', 'dashboard', 'side', 'high' );
}
function velocity_docu_dashboard_widget_function() {
    // widget content goes here
    $velocity_docu_url = "http://"."goodwebtheme.com/velodoc";
    ?>
    <strong>Need help?</strong> Find it in our Online Ressources:
<ul>
	<li><a title="Install" href="<?php echo $velocity_docu_url; ?>/" target="_blank">Install Velocity</a></li>
	<li><a href="<?php echo $velocity_docu_url; ?>/install-plugins/" target="_blank">Include Plugins</a></li>
	<li><a href="<?php echo $velocity_docu_url; ?>/demo-contentslider/" target="_blank">Import Demo Content</a></li>
	<li><a href="<?php echo $velocity_docu_url; ?>/settings-menu/" target="_blank">Set the Menu</a></li>
	<li><a href="<?php echo $velocity_docu_url; ?>/front-page-blog/" target="_blank">Set the Front Page / Blog Page</a></li>
	<li><a href="<?php echo $velocity_docu_url; ?>/theme-options/" target="_blank">Set the Theme Options</a></li>
	<li><a href="<?php echo $velocity_docu_url; ?>/pages/" target="_blank">Pages Overview</a></li>
	<li><a href="<?php echo $velocity_docu_url; ?>/blog/" target="_blank">How to build a Blog</a></li>
	<li><a href="<?php echo $velocity_docu_url; ?>/portfolio-plugin/" target="_blank">How to install a Portfolio</a></li>
	<li><a href="<?php echo $velocity_docu_url; ?>/portfolio-page-shortcode/" target="_blank">How to build a Portfolio Page</a></li>
	<li><a href="<?php echo $velocity_docu_url; ?>/visual-composer/" target="_blank">Visual Composer Overview</a></li>
	<li><a href="<?php echo "http://";?>kb.wpbakery.com/index.php?title=Visual_Composer" target="_blank">Visual Composer Detailed Documentation</a></li>
	<li><a href="<?php echo $velocity_docu_url; ?>/localization-wordinglanguage/" target="_blank">How to Localize the Theme</a></li>
	<li><a href="<?php echo $velocity_docu_url; ?>/widgets" target="_blank">How to create the Contact Widgets in Subheader and Footer?</a></li>
	<li><a href="<?php echo $velocity_docu_url; ?>/localization-wordinglanguage/" target="_blank">How to change the wording</a></li>
</ul>
If that is not helping please contact our Support at <a href="http://<?php echo "damojo"; ?>.ticksy.com" target="_blank">http://<?php echo "damojo"; ?>.ticksy.com</a>.
<?php
}
?>