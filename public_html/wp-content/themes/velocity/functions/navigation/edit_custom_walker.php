<?php
/**
 *  /!\ This is a copy of Walker_Nav_Menu_Edit class in core
 * 
 * Create HTML list of nav menu input items.
 *
 * @package WordPress
 * @since 3.0.0
 * @uses Walker_Nav_Menu
 */
class Walker_Nav_Menu_Edit_Custom extends Walker_Nav_Menu  {
	/**
	 * @see Walker_Nav_Menu::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ){	
	}
	
	/**
	 * @see Walker_Nav_Menu::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
	}
	
	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param object $args
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
	    global $_wp_nav_menu_max_depth;
	   
	    $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;
	
	    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
	
	    ob_start();
	    $item_id = esc_attr( $item->ID );
	    $removed_args = array(
	        'action',
	        'customlink-tab',
	        'edit-menu-item',
	        'menu-item',
	        'page-tab',
	        '_wpnonce',
	    );
	
	    $original_title = '';
	    if ( 'taxonomy' == $item->type ) {
	        $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
	        if ( is_wp_error( $original_title ) )
	            $original_title = false;
	    } elseif ( 'post_type' == $item->type ) {
	        $original_object = get_post( $item->object_id );
	        $original_title = $original_object->post_title;
	    }
	
	    $classes = array(
	        'menu-item menu-item-depth-' . $depth,
	        'menu-item-' . esc_attr( $item->object ),
	        'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
	    );
	
	    $title = $item->title;
	
	    if ( ! empty( $item->_invalid ) ) {
	        $classes[] = 'menu-item-invalid';
	        /* translators: %s: title of menu item which is invalid */
	        $title = sprintf( __( '%s (Invalid)','velocity' ), $item->title );
	    } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
	        $classes[] = 'pending';
	        /* translators: %s: title of menu item in draft status */
	        $title = sprintf( __('%s (Pending)','velocity'), $item->title );
	    }
	
	    $title = empty( $item->label ) ? $title : $item->label;
	
	    ?>
	    <li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
	        <dl class="menu-item-bar">
	            <dt class="menu-item-handle">
	                <span class="item-title"><?php echo esc_html( $title ); ?></span>
	                <span class="item-controls">
	                    <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
	                    <span class="item-order hide-if-js">
	                        <a href="<?php
	                            echo wp_nonce_url(
	                                add_query_arg(
	                                    array(
	                                        'action' => 'move-up-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                                ),
	                                'move-menu_item'
	                            );
	                        ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up'); ?>">&#8593;</abbr></a>
	                        |
	                        <a href="<?php
	                            echo wp_nonce_url(
	                                add_query_arg(
	                                    array(
	                                        'action' => 'move-down-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                                ),
	                                'move-menu_item'
	                            );
	                        ?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down'); ?>">&#8595;</abbr></a>
	                    </span>
	                    <a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e('Edit Menu Item'); ?>" href="<?php
	                        echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
	                    ?>"><?php _e( 'Edit Menu Item','velocity' ); ?></a>
	                </span>
	            </dt>
	        </dl>
	
	        <div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
	            <?php if( 'custom' == $item->type ) : ?>
	                <p class="field-url description description-wide">
	                    <label for="edit-menu-item-url-<?php echo $item_id; ?>">
	                        <?php _e( 'URL','velocity' ); ?><br />
	                        <input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
	                    </label>
	                </p>
	            <?php endif; ?>
	            <p class="description description-thin">
	                <label for="edit-menu-item-title-<?php echo $item_id; ?>">
	                    <?php _e( 'Navigation Label','velocity' ); ?><br />
	                    <input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
	                </label>
	            </p>
	            <p class="field-link-target description">
	                <label for="edit-menu-item-target-<?php echo $item_id; ?>">
	                    <input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
	                    <?php _e( 'Open link in a new window/tab','velocity' ); ?>
	                </label>
	            </p>
	            <p class="field-css-classes description description-thin">
	                <label for="edit-menu-item-classes-<?php echo $item_id; ?>">
	                    <?php _e( 'CSS Classes (optional)','velocity' ); ?><br />
	                    <input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
	                </label>
	            </p>
	            <p class="field-xfn description description-thin">
	                <label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
	                    <?php _e( 'Link Relationship (XFN)','velocity' ); ?><br />
	                    <input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
	                </label>
	            </p>
	            <p class="field-description description description-wide">
	                <label for="edit-menu-item-description-<?php echo $item_id; ?>">
	                    <?php _e( 'Description','velocity' ); ?><br />
	                    <textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
	                    <span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.','velocity'); ?></span>
	                </label>
	            </p>        
	            
	    <?php /* New fields insertion starts here */  ?>  

	            <!-- !Icon Menu -->
	            <p class="field-custom description">
	                <label for="edit-menu-item-icon-<?php echo $item_id; ?>"><span class="description">Optional Icon</span> <br><i id="menu-item-icon-<?php echo $item_id; ?>" onclick="writeIcons(this.id)" class="demoicon <?php echo $item->icon; ?>"></i> <a href="javascript:writeIcons('menu-item-icon-<?php echo $item_id;?>');" class="velocity_add_menu_icon" style="<?php if(empty($item->icon)) {echo '';} else {echo 'display:none';} ?>">Add</a><a class="velocity_change_menu_icon"  href="javascript:writeIcons('menu-item-icon-<?php echo $item_id;?>');" style="<?php if(empty($item->icon)) {echo 'display:none';} else {echo '';} ?>">Change</a> <span class="velocity_change_menu_icon" style="<?php if(empty($item->icon)) {echo 'display:none';} else {echo '';} ?>">|</span> <a href="javascript:deleteIcon('menu-item-icon-<?php echo $item_id;?>');" style="color:red;<?php if(empty($item->icon)) {echo 'display:none';} else {echo '';} ?>" class="velocity_del_menu_icon" >Delete</a>
<input type="text" style="display:none" id="edit-menu-item-icon-<?php echo $item_id; ?>" class="widefat code edit-menu-item-custom" name="menu-item-icon[<?php echo $item_id; ?>]" value="<?php echo $item->icon?>" />
	                </label>
	            </p>
				
				<!-- !Mega Menu -->
	            <p class="field-custom megamenu" style="clear:both;padding-top:5px;">
	                <label for="edit-menu-item-megamenu-<?php echo $item_id; ?>" style="whitespace:no-wrap;">
<input type="checkbox"  id="edit-menu-item-megamenu-<?php echo $item_id; ?>" class=" code edit-menu-item-custom" name="menu-item-megamenu[<?php echo $item_id; ?>]" <?php if(isset($item->megamenu) && $item->megamenu=="on") echo "checked"; ?> value="on" /><?php _e( 'Is MegaMenu','velocity' );?>
	                </label>
	            </p>
	            
	            <?php $display_widgetareashow = $item->widgetareashow!="on" ? '':'display:none;'; ?>
	            
	            <!-- !Column Mega Menu -->
	            <p class="field-custom columnmegamenu" style="clear:both;padding-top:5px;<?php echo $display_widgetareashow; ?>">
	                <label for="edit-menu-item-columnmegamenu-<?php echo $item_id; ?>" style="whitespace:no-wrap;">
<input type="checkbox"  id="edit-menu-item-columnmegamenu-<?php echo $item_id; ?>" class=" code edit-menu-item-custom" name="menu-item-columnmegamenu[<?php echo $item_id; ?>]" <?php if(isset($item->columnmegamenu) && $item->columnmegamenu=="on") echo "checked"; ?> value="on" /><?php _e( 'New Column Start if MegaMenu','velocity' );?>
	                </label>
	            </p>
	            
	            <!-- !Title Column Mega Menu -->
	            <p class="field-custom columntitlemegamenu" style="clear:both;padding-top:5px;<?php echo $display_widgetareashow; ?>">
	                <label for="edit-menu-item-columntitlemegamenu-<?php echo $item_id; ?>" style="whitespace:no-wrap;">
<input type="checkbox"  id="edit-menu-item-columntitlemegamenu-<?php echo $item_id; ?>" class=" code edit-menu-item-custom" name="menu-item-columntitlemegamenu[<?php echo $item_id; ?>]" <?php if(isset($item->columntitlemegamenu) && $item->columntitlemegamenu=="on") echo "checked"; ?> value="on" /><?php _e( 'Display Navigation Label as Column Title','velocity' );?>
	                </label>
	            </p>
	            
	            <p class="field-custom widgetareashowactivate" style="clear:both;padding-top:5px;">
	                <label for="edit-menu-item-widgetareashow-<?php echo $item_id; ?>" style="whitespace:no-wrap;">
<input type="checkbox"  id="edit-menu-item-widgetareashow-<?php echo $item_id; ?>" class=" code edit-menu-item-custom" name="menu-item-widgetareashow[<?php echo $item_id; ?>]" <?php if(isset($item->widgetareashow) && $item->widgetareashow=="on") echo "checked"; ?> value="on" /><?php _e( 'Display Widget Area','moose' );?>
	                </label>
	            </p>
	            
	            <?php $display_widgetarea = $item->widgetareashow=="on" ? '':'display:none;'; ?>
	            <p class="field-widgetarea widgetareashowp" style="clear:both;<?php echo $display_widgetarea; ?>">
	                <label for="edit-menu-item-widgetarea-<?php echo $item_id; ?>" style="whitespace:no-wrap;"> 
					<?php
							global $wp_registered_sidebars;
						    if( empty( $wp_registered_sidebars ) )
						        return;
						    $name = 'menu-item-widgetarea['.$item_id.']';
						    $meta = $item->widgetarea;
						    $current = ( $meta ) ? esc_attr( $meta ) : false;     
						    $selected = '';
						    echo "<select name='$name' id='$name'>";
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
							?>
	                </label>
	            </p>
	            
	            <?php $display_columnmegamenu = $item->columnmegamenu=="on" || $item->widgetareashow=="on" ? '':'display:none;'; ?>
	            
	            <p class="field-columnmegamenuwidth columnmegamenuwidth" style="<?php echo $display_columnmegamenu;?>">
	                <label for="edit-menu-item-columnmegamenuwidth-<?php echo $item_id; ?>">
	                    <?php _e( 'Column Width','velocity' ); ?><br />
	                    <input type="text" id="edit-menu-item-columnmegamenuwidth-<?php echo $item_id; ?>" class=" code edit-menu-item-columnmegamenuwidth" name="menu-item-columnmegamenuwidth[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->columnmegamenuwidth ); ?>" /><br><small>(e.g. "200" for 200px width, "full" for fullwidth, leave blank for default)</small>
	                </label>
	            </p>
	            
	     <?php /* New fields insertion ends here */ ?>
	            
	            <div class="menu-item-actions description-wide submitbox">
	                <?php if( 'custom' != $item->type && $original_title !== false ) : ?>
	                    <p class="link-to-original">
	                        <?php printf( __('Original: %s','velocity'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
	                    </p>
	                <?php endif; ?>
	                <a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
	                echo wp_nonce_url(
	                    add_query_arg(
	                        array(
	                            'action' => 'delete-menu-item',
	                            'menu-item' => $item_id,
	                        ),
	                        remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                    ),
	                    'delete-menu_item_' . $item_id
	                ); ?>"><?php _e('Remove','velocity'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, admin_url( 'nav-menus.php','velocity' ) ) ) );
	                    ?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel','velocity'); ?></a>
	            </div>
	
	            <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
	            <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
	            <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
	            <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
	            <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
	            <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
	        </div><!-- .menu-item-settings-->
	        <ul class="menu-item-transport"></ul>
	    <?php
	    
	    $output .= ob_get_clean();

	    }
}
