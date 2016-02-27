<?php
// Themeoptions
	$themeoptions = velocity_getThemeOptions();
	
// Redirect	
	$redirect_link = empty($themeoptions['velocity_404page']) ? home_url() : get_permalink($themeoptions['velocity_404page']);
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: ".$redirect_link);
	exit();
?>
