<?php
/**
 * WPBakery Visual Composer shortcodes
 *
 * @package WPBakeryVisualComposer
 *
 */

/* Layer Slider
---------------------------------------------------------- */

class WPBakeryShortCode_AZ_Layer_Slider extends WPBakeryShortCode {
  protected function content($atts, $content = null) {
    $id = $el_class = '';
      	extract( shortcode_atts( array(
	  		'id' => '',
        	'el_class' => ''
      	), $atts ) );
      	$output = '';
      	$el_class = $this->getExtraClass($el_class);
	  
	 	$class = setClass(array('layer_slider_container', $el_class));
	 	$output .= '<div'.$class.'>';
	 	$output .= do_shortcode('[layerslider id="'.$id.'"]');
	 	$output .= '</div>';
      
      	return $output.$this->endBlockComment('az_layer_slider')."\n";
  }
}

?>