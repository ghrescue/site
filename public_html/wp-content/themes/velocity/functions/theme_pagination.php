<?php
/*******************************************************************
* @Author: Boutros AbiChedid 
* @Date:   March 20, 2011
* @Websites: http://bacsoftwareconsulting.com/
* http://blueoliveonline.com/
* @Description: Numbered Page Navigation (Pagination) Code.
* @Tested: Up to WordPress version 3.1.2 (also works on WP 3.3.1)
********************************************************************/

function velocity_round_num($num, $to_nearest) {return floor($num/$to_nearest)*$to_nearest;}

 
/* Function that performs a Boxed Style Numbered Pagination (also called Page Navigation). Function is largely based on Version 2.4 of the WP-PageNavi plugin */  
function velocity_spec_pagination($velocity_query,$before = '', $after = '') {  
    global $wpdb;
    $pagenavi_options = array();
    $pagenavi_options['pages_text'] = __('Page %CURRENT_PAGE% of %TOTAL_PAGES%','velocity');
    $pagenavi_options['current_text'] = '%PAGE_NUMBER%';
    $pagenavi_options['page_text'] = '%PAGE_NUMBER%';
    $pagenavi_options['first_text'] = __('First Page','velocity');
    $pagenavi_options['last_text'] = __('Last Page','velocity');
    $pagenavi_options['next_text'] = __('Next','velocity');
    $pagenavi_options['prev_text'] = __('Previous','velocity');
    $pagenavi_options['dotright_text'] = '...'; 
    $pagenavi_options['dotleft_text'] = '...';
    $pagenavi_options['num_pages'] = 5; //continuous block of page numbers
    $pagenavi_options['always_show'] = 0;
    $pagenavi_options['num_larger_page_numbers'] = 0;
    $pagenavi_options['larger_page_numbers_multiple'] = 5;
     
    //If NOT a single Post is being displayed 
    /*http://codex.wordpress.org/Function_Reference/is_single)*/
    if (!is_single()) {
    	$request = $velocity_query->request;
        //intval ? Get the integer value of a variable
        /*http://php.net/manual/en/function.intval.php*/
        $posts_per_page = intval(get_query_var('posts_per_page'));
        //Retrieve variable in the WP_Query class. 
        /*http://codex.wordpress.org/Function_Reference/get_query_var*/
        $paged = intval(get_query_var('paged'));
        if(empty($paged)) $paged = intval(get_query_var('page'));
        $numposts = $velocity_query->found_posts;
        $max_page = $velocity_query->max_num_pages;
         
        //empty ? Determine whether a variable is empty
        /*http://php.net/manual/en/function.empty.php*/
        if(empty($paged) || $paged == 0) {
            $paged = 1;
        }
         
        $pages_to_show = intval($pagenavi_options['num_pages']);
        $larger_page_to_show = intval($pagenavi_options['num_larger_page_numbers']);
        $larger_page_multiple = intval($pagenavi_options['larger_page_numbers_multiple']);
        $pages_to_show_minus_1 = $pages_to_show - 1;
        $half_page_start = floor($pages_to_show_minus_1/2);
        //ceil ? Round fractions up (http://us2.php.net/manual/en/function.ceil.php)
        $half_page_end = ceil($pages_to_show_minus_1/2);
        $start_page = $paged - $half_page_start;
         
        if($start_page <= 0) {
            $start_page = 1;
        }
         
        $end_page = $paged + $half_page_end;
        if(($end_page - $start_page) != $pages_to_show_minus_1) {
            $end_page = $start_page + $pages_to_show_minus_1;
        }
        if($end_page > $max_page) {
            $start_page = $max_page - $pages_to_show_minus_1;
            $end_page = $max_page;
        }
        if($start_page <= 0) {
            $start_page = 1;
        }
         
        $larger_per_page = $larger_page_to_show*$larger_page_multiple;
        //velocity_round_num() custom function - Rounds To The Nearest Value.
        $larger_start_page_start = (velocity_round_num($start_page, 10) + $larger_page_multiple) - $larger_per_page;
        $larger_start_page_end = velocity_round_num($start_page, 10) + $larger_page_multiple;
        $larger_end_page_start = velocity_round_num($end_page, 10) + $larger_page_multiple;
        $larger_end_page_end = velocity_round_num($end_page, 10) + ($larger_per_page);
         
        if($larger_start_page_end - $larger_page_multiple == $start_page) {
            $larger_start_page_start = $larger_start_page_start - $larger_page_multiple;
            $larger_start_page_end = $larger_start_page_end - $larger_page_multiple;
        }   
        if($larger_start_page_start <= 0) {
            $larger_start_page_start = $larger_page_multiple;
        }
        if($larger_start_page_end > $max_page) {
            $larger_start_page_end = $max_page;
        }
        if($larger_end_page_end > $max_page) {
            $larger_end_page_end = $max_page;
        }
        if($max_page > 1 || intval($pagenavi_options['always_show']) == 1) {
            /*http://php.net/manual/en/function.str-replace.php */
            /*number_format_i18n(): Converts integer number to format based on locale (wp-includes/functions.php*/
            $pages_text = str_replace("%CURRENT_PAGE%", number_format_i18n($paged), $pagenavi_options['pages_text']);
            $pages_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pages_text);
            echo $before.'<div class="pagenavi">'."\n";
             
            if(!empty($pages_text)) {
                echo '<span class="pages">'.$pages_text.'</span>';
            }
            //Displays a link to the previous post which exists in chronological order from the current post. 
            /*http://codex.wordpress.org/Function_Reference/previous_post_link*/
            echo '<div class="pagination"><ul>';
             
            echo '<li>';
            previous_posts_link($pagenavi_options['prev_text']);
            echo '</li>';
             
            if ($start_page >= 2 && $pages_to_show < $max_page) {
                $first_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['first_text']);
                //esc_url(): Encodes < > & " ' (less than, greater than, ampersand, double quote, single quote). 
                /*http://codex.wordpress.org/Data_Validation*/
                //get_pagenum_link():(wp-includes/link-template.php)-Retrieve get links for page numbers.
                echo '<li><a href="'.esc_url(get_pagenum_link()).'" class="first" title="'.$first_page_text.'">1</a></li>';
                if(!empty($pagenavi_options['dotleft_text'])) {
                    echo '<li><span class="expand">'.$pagenavi_options['dotleft_text'].'</span></li>';
                }
            }
             
            if($larger_page_to_show > 0 && $larger_start_page_start > 0 && $larger_start_page_end <= $max_page) {
                for($i = $larger_start_page_start; $i < $larger_start_page_end; $i+=$larger_page_multiple) {
                    $page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
                    echo '<li><a href="'.esc_url(get_pagenum_link($i)).'" class="single_page" title="'.$page_text.'">'.$page_text.'</a></li>';
                }
            }
            
            for($i = $start_page; $i  <= $end_page; $i++) {                      
                if($i == $paged) {
                    $current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
                    echo '<li class="active"><span class="current">'.$current_page_text.'</span></li>';
                } else {
                    $page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
                    echo '<li><a href="'.esc_url(get_pagenum_link($i)).'" class="single_page" title="'.$page_text.'">'.$page_text.'</a></li>';
                }
            }
             
            if ($end_page < $max_page) {
                if(!empty($pagenavi_options['dotright_text'])) {
                    echo '<li><span class="expand">'.$pagenavi_options['dotright_text'].'</span></li>';
                }
                $last_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['last_text']);
                echo '<li><a href="'.esc_url(get_pagenum_link($max_page)).'" class="last" title="'.$last_page_text.'">'.$max_page.'</a></li>';
            }

            echo "<li>";
            next_posts_link($pagenavi_options['next_text'], $max_page);
            echo "</li>"; 

            if($larger_page_to_show > 0 && $larger_end_page_start < $max_page) {
                for($i = $larger_end_page_start; $i <= $larger_end_page_end; $i+=$larger_page_multiple) {
                    $page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
                    echo '<a href="'.esc_url(get_pagenum_link($i)).'" class="single_page" title="'.$page_text.'">'.$page_text.'</a>';
                }
            }
            echo '</div>'.$after."\n";
        }
        echo '</ul></div>';
    }
}
?>
<?php
/* ------------------------------------- */
/* BLOG PAGINATION by Patrick http://www.techspread.de/200/pagination-in-wordpress-theme-einbauen */
/* ------------------------------------- */

function velocity_pagination($velocity_start_end_links = 4, $velocity_middle_links = 4)
{
	global $wp_query;		
	// Keine Pagination auf Einzelseiten
	if(!is_single())	
	{			
		$velocity_current = get_query_var('paged') == 0 ? 1 : get_query_var('paged');	// Derzeitige Seite auslesen
		$velocity_total = $wp_query->max_num_pages;										// Gesamtanzahl Seiten
		$velocity_links_left = floor(($velocity_middle_links - 1) / 2);							// Anzahl Links am Anfang
		$velocity_links_right = ceil(($velocity_middle_links - 1) / 2);							// Anzahl Links am Ende
		// Pagination nur ausgeben, wenn mehr als eine Index-Seite besteht
		if($velocity_total > 1)	
		{				
			// Pagination-Anfang
			echo '<div class="pagination"><ul>';
			// alle "Seiten" durchgehen
			for($i=1; $i<=$velocity_total; $i++)	
			{
				// Link auf die derzeitige Seite
				if($i == $velocity_current)
				{
					echo '<li class="active"><a href="#">'.($velocity_current).'</a></li>';
				}
				// alle anderen Seiten-Links
				elseif($i >= ($velocity_current - $velocity_links_left) && $i <= ($velocity_current + $velocity_links_right) || $i <= $velocity_start_end_links || $i > ($velocity_total - $velocity_start_end_links))
				{
					echo '<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
				}
				// auszulassene Seiten
				elseif($i == ($velocity_start_end_links + 1) && $i < ($velocity_current - $velocity_links_left + 1) || $i == ($velocity_total - $velocity_start_end_links) && $i > ($velocity_current + $velocity_links_right))
				{
					echo '<li class="disabled">...</li>';
				}
			}
			// Pagination-Ende
			echo '</ul></div><div class="pagenumbers">Page '.($velocity_current).' of '.($velocity_total).'</div><div class="clear"></div>';
		}
	}
}

/*
function velocity_spec_pagination($velocity_query,$velocity_start_end_links = 4, $velocity_middle_links = 4)
{
	//global $wp_query;		
	// Keine Pagination auf Einzelseiten
	if(!is_single())	
	{			
		$velocity_current = get_query_var('paged') == 0 ? 1 : get_query_var('paged');	// Derzeitige Seite auslesen
		$velocity_total = $velocity_query->max_num_pages;										// Gesamtanzahl Seiten
		$velocity_links_left = floor(($velocity_middle_links - 1) / 2);							// Anzahl Links am Anfang
		$velocity_links_right = ceil(($velocity_middle_links - 1) / 2);							// Anzahl Links am Ende
		// Pagination nur ausgeben, wenn mehr als eine Index-Seite besteht
		if($velocity_total > 1)	
		{				
			// Pagination-Anfang
			echo '<div class="pagination"><ul>';
			// alle "Seiten" durchgehen
			for($i=1; $i<=$velocity_total; $i++)	
			{
				// Link auf die derzeitige Seite
				if($i == $velocity_current)
				{
					echo '<li class="active"><a href="#">'.($velocity_current).'</a></li>';
				}
				// alle anderen Seiten-Links
				elseif($i >= ($velocity_current - $velocity_links_left) && $i <= ($velocity_current + $velocity_links_right) || $i <= $velocity_start_end_links || $i > ($velocity_total - $velocity_start_end_links))
				{
					echo '<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
				}
				// auszulassene Seiten
				elseif($i == ($velocity_start_end_links + 1) && $i < ($velocity_current - $velocity_links_left + 1) || $i == ($velocity_total - $velocity_start_end_links) && $i > ($velocity_current + $velocity_links_right))
				{
					echo '<li class="disabled">...</li>';
				}
			}
			// Pagination-Ende
			echo '</ul></div><div class="pagenumbers">Page '.($velocity_current).' of '.($velocity_total).'</div><div class="clear"></div>';
		}
	}
}*/


?>
