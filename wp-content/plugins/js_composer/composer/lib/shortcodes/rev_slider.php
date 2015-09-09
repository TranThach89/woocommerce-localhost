<?php
/**
 * WPBakery Visual Composer shortcodes
 *
 * @package WPBakeryVisualComposer
 *
 */

/* Layer Slider
---------------------------------------------------------- */

class WPBakeryShortCode_AZ_Rev_Slider extends WPBakeryShortCode {
  protected function content($atts, $content = null) {
    $alias = $el_class = '';
      	extract( shortcode_atts( array(
	  		   'alias' => '',
        	 'el_class' => ''
      	), $atts ) );
      	$output = '';
      	$el_class = $this->getExtraClass($el_class);
	  
	 	$class = setClass(array('revolution_slider_container', $el_class));
	 	$output .= '<div'.$class.'>';
	 	$output .= do_shortcode('[rev_slider '.$alias.']');
	 	$output .= '</div>';
      
      	return $output.$this->endBlockComment('az_rev_slider')."\n";
  }
}

?>