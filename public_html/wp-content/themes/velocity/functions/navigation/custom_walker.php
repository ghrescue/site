<?php
/**
 * Custom Walker
 *
 * @access      public
 * @since       1.0 
 * @return      void
*/
class rc_scm_walker extends Walker_Nav_Menu
{
  
  //Include Subtitle
      function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 )
      {
           global $wp_query;
           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

           $class_names = $value = '';

           $classes = empty( $item->classes ) ? array() : (array) $item->classes;

		   $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
           if (!empty($item->columntitlemegamenu) && empty($item->widgetareashow)){
	           $class_names .= ' megamenutitle ';
           }
           
           if(!empty( $item->widgetareashow )){
           	   $velocity_themeoptions = velocity_getThemeOptions();
           	   $velocity_header_style = isset($velocity_themeoptions["velocity_header_style"]) && $velocity_themeoptions["velocity_header_style"]=="lightheader"  ? 'sidebar' : 'footer';
           	   $widgetarea_container = 'megamenuwidget_container';
	           $class_names .= ' megamenuwidgets ';
           }
           else {
	           $widgetarea_container = $velocity_header_style = '';
           }
           
           $widgetarea_width = empty($item->columnmegamenuwidth) || !is_numeric($item->columnmegamenuwidth) ? '' : 'style="width:'.str_replace("px","",$item->columnmegamenuwidth).'px"';
           
           $widgetarea_fullwidth = !empty($item->columnmegamenuwidth) && $item->columnmegamenuwidth == "full" ? ' megamenuwidgetsfullwidth ' : '';
           
           $class_names = ' class="'. esc_attr( $class_names ) . '"';

           if (!empty($item->columnmegamenu) && empty($item->widgetareashow)){
	           $indent .= ' </ul><ul '.$widgetarea_width.' class="sub-menu megamenu-item '.$widgetarea_container.' '.$velocity_header_style.' '.$widgetarea_fullwidth.'">';
           } else {
	           if(!empty($item->widgetareashow)) $indent .= ' </ul><ul '.$widgetarea_width.' class="sub-menu megamenu-item '.$widgetarea_container.' '.$velocity_header_style.' '.$widgetarea_fullwidth.'">';
           } 
            
           $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
           
           
           $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
           $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
           $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
	       $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url ) .'"' : '';	
			
		   $prepend = '';
           $append = '';
           $icon  = ! empty( $item->icon ) ? '<i class="demoicon '.esc_attr( $item->icon ).'"></i>' : '';

           /*if($depth != 0)
           {
	           $icon = $append = $prepend = "";
           }*/
		   	
		   	$item_menu_link = "menu-link";
		   	
		   	if(!empty($item->columntitlemegamenu) && (empty($item->url) || $item->url == "#")) $item_menu_link = "" ;
		   	
            $item_output = $args->before;
            $item_output .= '<a class="menu-link " '. $attributes .'>'.$icon;
            $item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
            $item_output .= $args->link_after;
            $item_output .= '</a>';
            $item_output .= '<div class="clear"></div>';
            
            if(! empty( $item->widgetareashow )){ 
              ob_start();
              	dynamic_sidebar($item->widgetarea);
			  	$widgetarea = ob_get_contents();
              ob_end_clean();
			  $toreplace = array("last span3","span3","first  widget");
			  $withreplace = array("","","widget");
			  $widgetarea = str_replace($toreplace,$withreplace,$widgetarea);
			  $item_output = $widgetarea;
		    }
		    
		    if(!empty($item->megamenu)) {
	            $item_output .= '<div class="megamenu">';
            }
            
            $item_output .= $args->after; 
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
            
	   }
            
   //Include Special CSS for indicating Submenus
      function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
	        $id_field = $this->db_fields['id'];
	        if ($depth && !empty( $children_elements[ $element->$id_field ] ) ) {
	            $element->classes[] = 'hassubmenu';
	        }
	        /*if ($depth ==0  ) {
	            $element->classes[] = 'toplevel';
	        }*/
	        Walker_Nav_Menu::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	    }
}


?>