<?php
/* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */ 


/* ------------------------------------------------------------------------ *
 * Checkbox
 * ------------------------------------------------------------------------ */  
	function damojoPortfolio_checkbox_callback($args) {
		// Extract $args
		$name = $args[0];
		$section = $args[1];
		$desc = $args[2];
		
		// First, we read the options collection
		$options = get_option($section);
		
		// Next, we update the name attribute to access this element's ID in the context of the display options array
		// We also access the show_header element of the options collection in the call to the checked() helper function
		$html = '<input type="checkbox" id="' . $name . '" name="' . $section . '[' . $name . ']" value="1" ' . checked( 1, isset( $options[$name] ) ? $options[$name] : 0, false ) . '/>'; 
		
		// Here, we'll take the first argument of the array and add it to a label next to the checkbox
		$html .= '<label for="' . $name .'">&nbsp;'  . $desc . '</label>'; 
		echo $html;
		
	} // end damojoPortfolio_checkbox_callback

/* ------------------------------------------------------------------------ *
 * Input URL
 * ------------------------------------------------------------------------ */ 
	function damojoPortfolio_input_url_callback($args) {
		
		// First, extract $args
		$name = $args[0];
		$section = $args[1];
		$desc = $args[2];
		$size = isset($args[3]) ? $args[3] : 300;
		
		// Then, we read the options collection
		$options = get_option( $section );
		
		// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
		$url = '';
		if( isset( $options[$name] ) ) {
			$url = esc_url( $options[$name] );
		} // end if
		
		// Render the output
		echo '<input type="text" id="'. $name .'" name="' . $section . '[' . $name . ']" value="' . $url . '" style="width:' . $size . 'px"/>';
		echo '<br><span class="description">' . $desc . '</span>';
	} // end damojoPortfolio_input_url_callback


/* ------------------------------------------------------------------------ *
 * Input
 * ------------------------------------------------------------------------ */ 
	function damojoPortfolio_input_callback($args) {
		
		// First, extract $args
		$name = $args[0];
		$section = $args[1];
		$desc = $args[2];
		$size = isset($args[3]) ? $args[3] : 300;
		
		$options = get_option( $section );
		
		// Render the output
		echo '<input type="text" id="' . $name . '" name="' . $section . '[' . $name . ']" value="' . $options[$name] . '" style="width:' . $size . 'px"/>';
		echo '<br><span class="description">' . $desc . '</span>';
	} // end damojoPortfolio_input_element_callback


/* ------------------------------------------------------------------------ *
 * Textarea
 * ------------------------------------------------------------------------ */ 
	function damojoPortfolio_textarea_callback($args) {
		
		// First, extract $args
		$name = $args[0];
		$section = $args[1];
		$desc = $args[2];
		
		$options = get_option( $section );
		
		// Render the output
		echo '<textarea id="' . $name . '" name="' . $section . '[' . $name . ']" rows="5" cols="50">' . $options[$name] . '</textarea>';
		echo '<br><span class="description">' . $desc . '</span>';
		
	} // end damojoPortfolio_textarea_callback


/* ------------------------------------------------------------------------ *
 * Radio
 * ------------------------------------------------------------------------ */ 
	function damojoPortfolio_radio_callback($args) {
		// First, extract $args
		$name = $args[0];
		$section = $args[1];
		$desc = $args[2];
		$boxes_array = $args[3];
		
		$options = get_option( $section );
		$boxcount=0;
		foreach($boxes_array as $value => $text){
			echo '<input type="radio" id="' . $name . $boxcount . '" name="' . $section . '[' . $name . ']" value="' . $value . '"' . checked( $value, $options[$name], false ) . '/>';
			echo '<label for="' . $name . '">&nbsp;&nbsp;' . $text . '</label><br>';
		}	
		echo '<br><span class="description">' . $desc . '</span>';
	} // end damojoPortfolio_radio_callback

/* ------------------------------------------------------------------------ *
 * Select
 * ------------------------------------------------------------------------ */ 
	 function damojoPortfolio_select_callback($args) {
	
		 // First, extract $args
		$name = $args[0];
		$section = $args[1];
		$desc = $args[2];
		$boxes_array = $args[3];
		
	
		$options = get_option(  $section  );
		
		$html = '<select id="' . $name . '" name="' . $section . '[' . $name . ']">';
			foreach($boxes_array as $option){
				$html .= '<option value="'.$option[0].'"' . selected( $options[$name], $option[0], false) . '>'.$option[1].'</option>';
			}
		$html .= '</select>';
		
		echo $html;
		echo '<br><span class="description">' . $desc . '</span>';

	} // end damojoPortfolio_radio_element_callback

/* ------------------------------------------------------------------------ *
 * ColorSelect
 * ------------------------------------------------------------------------ */ 
	 function damojoPortfolio_selectColor_callback($args) {
	
		 // First, extract $args
		$name = $args[0];
		$section = $args[1];
		$desc = $args[2];
		
	
		$options = get_option(  $section  );
		
		$html = '<select id="' . $name . '" name="' . $section . '[' . $name . ']">';
		$html .= '<option value="white"' . selected( $options[$name], "white", false) . '>white</option>';
		$html .= '<option value="blue"' . selected( $options[$name], "blue", false) . '>blue</option>';
		$html .= '<option value="green"' . selected( $options[$name], "green", false) . '>green</option>';
		$html .= '<option value="black"' . selected( $options[$name], "black", false) . '>black</option>';
		$html .= '<option value="grey"' . selected( $options[$name], "grey", false) . '>grey</option>';
		$html .= '</select>';
		
		echo $html;
		echo '<br><span class="description">' . $desc . '</span>';

	} // end damojoPortfolio_radio_element_callback

/* ------------------------------------------------------------------------ *
 * Select Page
 * ------------------------------------------------------------------------ */ 
	 function damojoPortfolio_selectpage_callback($args) {
	
		 // First, extract $args
		$name = $args[0];
		$section = $args[1];
		$desc = $args[2];
		
	
		$options = get_option(  $section  );
		
		
		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => 'default'
		));
		$content = '<select id="' . $name . '" name="' . $section . '[' . $name . ']">';
		foreach($pages as $page){
			$selected = $page->ID == $options[$name] ? "selected" : "";
			$content .= "<option value='".$page->ID."' ".$selected.">";
	        $content .=  $page->post_title;
	    	$content .=  "</option>";
		}
		echo $content."</select>";
		echo '<br><span class="description">' . $desc . '</span>';
	} // end damojoPortfolio_radio_element_callback

/* ------------------------------------------------------------------------ *
 * Color
 * ------------------------------------------------------------------------ */ 
	function damojoPortfolio_color_callback($args) {
		
		// First, extract $args
		$name = $args[0];
		$section = $args[1];
		$desc = $args[2];
		
		$options = get_option( $section );
		
		// Render the output
		echo '<input type="text" id="' . $name . '" class="color" name="' . $section . '[' . $name . ']" value="' . $options[$name] . '" style="width:70px"/>';
		echo '<br><span class="description">' . $desc . '</span>';
	} // end damojoPortfolio_input_element_callback

/* ------------------------------------------------------------------------ *
 * Sidebar Builder
 * ------------------------------------------------------------------------ */ 
	 function damojoPortfolio_sidebar_build_callback($args) {
		 // First, extract $args
		$name = $args[0];
		$section = $args[1];
		$desc = $args[2];
		
		
		$options = get_option( $section );
		
		echo "
		<script>
			jQuery(document).ready(function(){
				jQuery('.repeatable-add').click(function() {
					field = jQuery(this).closest('div').find('.custom_repeatable li:last').clone(true);
					fieldLocation = jQuery(this).closest('div').find('.custom_repeatable li:last');
					jQuery('input,select', field).val('').attr('name', function(index, name) {
						return name.replace(/(\d+)/, function(fullMatch, n) {
							return Number(n) + 1;
						});
					})
					field.insertBefore(jQuery(this).closest('div').find('.inserthere'))
					jQuery('.slug', field).val('sidebar_'+Math.round(new Date().getTime() / 1000));
					return false;
				});
				jQuery('.repeatable-remove').click(function(){
					jQuery(this).parent().remove();
					return false;
				});
			});
		</script>";
		echo '<div><a class="repeatable-add button" href="#">Add Sidebar</a> 
            <ul id="'.$args[0].'-repeatable" class="custom_repeatable">';  
	    $i = 0;
	    $j = 1; 
	    
	    echo '<strong><div style="width:110px;float:left;">&nbsp;Sidebar Name</div></strong><div style="clear:both;"></div>';
	    if (is_array($options) && !empty($options)) {  
	        foreach($options as $row) {
	        	if($j%2==0){
	        		echo '<input type="hidden" id="' . $name . 'Slug" class="slug" name="' . $section . '[' . $name . "_slug-" . $i . ']" value="'.$row.'" style="width:150px;float:left;" />&nbsp;<a class="repeatable-remove" href="#"><small>Remove Sidebar</small></a></li>';
			    	$i++;
	                $j = 0;
		        }
		        else{
			        echo '<li><input type="text" id="' . $name . 'Name" name="' . $section . '[' . $name . "_name-" . $i . ']" value="'.$row.'" style="width:110px;float:left;"/>';
			    }
			    $j++;
	        }
	    } else {  
	        echo '<li><input type="text" id="' . $name . '" name="' . $section . '[' . $name . "_name-" . $i . ']" value="" style="width:110px"/> 
	                    <input type="hidden" id="' . $name . 'Slug" class="slug" name="' . $section . '[' . $name . "_slug-" . $i . ']" value="'.uniqid("portfolio_").'" style="width:150px"/></li>';
	    }  
	    echo '<span class="inserthere"></span></ul> 
	        </div><div style="clear:both"></div>'; 		
	} // end damojoPortfolio_sidebar_callback
	
/* ------------------------------------------------------------------------ *
 * Sidebar Chooser
 * ------------------------------------------------------------------------ */ 
	 function damojoPortfolio_sidebar_choose_callback($args) {
		 // First, extract $args
		$name = $args[0];
		$section = $args[1];
		$desc = $args[2];
		
		
		$options = get_option( $section );
		
		global $wp_registered_sidebars;
	    if( empty( $wp_registered_sidebars ) )
	        return;
	    $current = ( !empty($options[$name]) ) ? $options[$name] : false;     
	    $selected = '';
	    echo '<select id="' . $name . '" name="' . $section . '[' . $name . ']"><option value="nosidebar">No Sidebar</option>';
	    foreach( $wp_registered_sidebars as $sidebar ) : 
	        if( $current ) 
	            if($sidebar['name'] == $current)
	            	$selected = "selected";
	            else 
	            	$selected = "";
	        echo "<option value='".$sidebar['name']."' $selected>";
	        echo $sidebar['name'];
	    	echo "</option>";
	    endforeach;
	    echo "</select><br>";	
	} // end damojoPortfolio_sidebar_callback


	function damojoPortfolio_html_callback($args){
		 // First, extract $args
		$name = $args[0];
		$section = $args[1];
		$desc = $args[2];
		
		echo $desc;	
	}

/* ------------------------------------------------------------------------ *
 * Sidebar Mandotory Chooser
 * ------------------------------------------------------------------------ */ 
	 function damojoPortfolio_sidebar_mandotory_choose_callback($args) {
		 // First, extract $args
		$name = $args[0];
		$section = $args[1];
		$desc = $args[2];
		
		
		$options = get_option( $section );
		
		global $wp_registered_sidebars;
	    if( empty( $wp_registered_sidebars ) )
	        return;
	    $current = ( !empty($options[$name]) ) ? $options[$name] : false;     
	    $selected = '';
	    echo '<select id="' . $name . '" name="' . $section . '[' . $name . ']">';
	    foreach( $wp_registered_sidebars as $sidebar ) : 
	        if( $current ) 
	            if($sidebar['name'] == $current)
	            	$selected = "selected";
	            else 
	            	$selected = "";
	        echo "<option value='".$sidebar['name']."' $selected>";
	        echo $sidebar['name'];
	    	echo "</option>";
	    endforeach;
	    echo "</select><br>";	
	} // end damojoPortfolio_sidebar_callback



/* ------------------------------------------------------------------------ *
 * Image
 * ------------------------------------------------------------------------ */ 
	function damojoPortfolio_image_callback($args) {
		
		// First, extract $args
		$name = $args[0];
		$section = $args[1];
		$desc = $args[2];
		
		$options = get_option( $section );
		
		// Render the output
		echo "
			<script>
				jQuery(document).ready(function(){
					  var _custom_media = true, orig_send_attachment = wp.media.editor.send.attachment;
					  jQuery('#".$name."_button').click(function() {
					  	var send_attachment_bkp = wp.media.editor.send.attachment;
					    var button = jQuery(this);
					    var id = button.attr('id').replace('_button', '');
					    _custom_media = true;
					    wp.media.editor.send.attachment = function(props, attachment){
					      if ( _custom_media ) {
					        jQuery('#'+id).val(attachment.url);
					        jQuery('#'+id+'_id').val(attachment.id);
					        jQuery('#'+id+'_image').attr('src',attachment.url);
					      } else {
					        return _orig_send_attachment.apply( this, [props, attachment] );
					      };
					    }
					    wp.media.editor.open(button);
					    return false;
					  });
					  
					  jQuery('.add_media').on('click', function(){
					    _custom_media = false;
					  });
					  
					  jQuery('#". $name ."_clear').click(function(){
						var button = jQuery(this);
					    var id = button.attr('id').replace('_button', '');
						var defaultImage = jQuery('#".$name."_defimage').text();
						jQuery('#".$name."').val('');
						jQuery('#".$name."_image').attr('src', defaultImage);
						return false;
					});	  
				});
			</script>
		";
		
		$image = get_template_directory_uri().'/img/tiles/more.png';
								
		$display = empty($options[$name]) ? $image : $options[$name];			
								
	   echo '   <div class="uploader">
	   			  <span id="'.$name.'_defimage" style="display:none">'.$image.'</span>
	   			  <img style="max-width:300px;" id="'.$name.'_image" src="'.$display.'" /><br>
				  <input type="hidden" name="' . $section . '[' . $name . ']" id="'.$name.'" value="'.$options[$name].'" />
				  <input type="hidden" name="' . $section . '[' . $name . '_id]" id="'.$name.'_id" value="'.$options[$name.'_id'].'" />
				  <input class="button" style="width:105px" name="'.$name.'_button" id="'.$name.'_button" value="Choose Image" />
				  <small>&nbsp;<a href="#" id="'.$name.'_clear">Remove Image</a></small>
				</div>';
	   echo '<br><span class="description">' . $desc . '</span><br>';

}

/* ------------------------------------------------------------------------ *
 * Portfolio Builder
 * ------------------------------------------------------------------------ */ 
	 function damojoPortfolio_portfolio_build_callback($args) {
		 // First, extract $args
		$name = $args[0];
		$section = $args[1];
		$desc = $args[2];
		
		$options = get_option( $section );
		
		//print_r($options);
		
		echo "
		<script>
			jQuery(document).ready(function(){
				jQuery('.repeatable-add').click(function() {
					field = jQuery(this).closest('div').find('.custom_repeatable li:last').clone(true);
					fieldLocation = jQuery(this).closest('div').find('.custom_repeatable li:last');
					jQuery('input,select', field).val('').attr('name', function(index, name) {
						return name.replace(/(\d+)/, function(fullMatch, n) {
							return Number(n) + 1;
						});
					})
					field.insertBefore(jQuery(this).closest('div').find('.inserthere'))
					jQuery('.slug', field).val('portfolio_'+Math.round(new Date().getTime() / 1000));
					return false;
				});
				jQuery('.repeatable-remove').click(function(){
					jQuery(this).parent().remove();
					return false;
				});
			});
		</script>";
		echo '<div><a class="repeatable-add button" href="#">Add Portfolio</a> 
            <ul id="'.$args[0].'-repeatable" class="custom_repeatable">';  
	    $i = 0;
	    $j = 1; 
	    
	    //print_r($options);
	    echo '<strong><div style="width:310px;float:left;">&nbsp;Portfolio Name</div><div style="width:350px;float:left;">&nbsp;&nbsp;Slug (no spaces & no special chars)</div></strong><div style="clear:both;"></div>';
	    if (is_array($options) && !empty($options)) {  
	        foreach($options as $row) {
	        	if(($j%2)==0){
		            echo '<input type="text" id="' . $name . 'Slug" class="slug" name="' . $section . '[' . $name . "_slug-" . $i . ']" value="'.$row.'" style="width:150px" />';
		            echo '&nbsp;<a class="repeatable-remove" href="#"><small>Remove Portfolio</small></a></li>';
	                $i++;
	                $j = 0;
	            }
	            else{
		            echo '<li><input type="text" id="' . $name . 'Name" name="' . $section . '[' . $name . "_name-" . $i . ']" value="'.$row.'" style="width:310px"/>';
	            }
	        	$j++;
	        }  
	    } else {  
	        echo '<li><input type="text" id="' . $name . '" name="' . $section . '[' . $name . "_name-" . $i . ']" value="" style="width:310px"/> 
	                    <input type="text" id="' . $name . 'Slug" class="slug" name="' . $section . '[' . $name . "_slug-" . $i . ']" value="portfolio_'.substr(rand()*8,0,10).'" style="width:150px"/><a class="repeatable-remove button" href="#">-</a></li>';
	    }  
	    echo '<span class="inserthere"></span></ul> 
	        </div>'; 		
	} // end damojoPortfolio_radio_element_callback

?>