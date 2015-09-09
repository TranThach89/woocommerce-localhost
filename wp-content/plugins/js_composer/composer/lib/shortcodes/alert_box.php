<?php
/**
* WPBakery Visual Composer shortcodes
*
* @package WPBakeryVisualComposer
*
*/
 
$type_arr = array(
	__("Standard", "js_composer") => "alert-standard",
	__("Warning", "js_composer") => "alert-warning",
	__("Error", "js_composer") => "alert-error",
	__("Info", "js_composer") => "alert-info",
	__("Success", "js_composer") => "alert-success"
);

class WPBakeryShortCode_AZ_Alert_Box extends WPBakeryShortCode {

    protected function content($atts, $content = null) {

        $animation_loading = $animation_loading_effects = $animation_delay = $el_class = $type = '';

        extract(shortcode_atts(array(
			'animation_loading' => '',
			'animation_loading_effects' => '',
			'animation_delay' => '',
            'el_class' => '',
			'type' => ''
        ), $atts));

        $output = '';
		
		$animation_loading_class = null;
		if ($animation_loading == "yes") {
			$animation_loading_class = 'animated-content';
		}
		
		$animation_effect_class = null;
		if ($animation_loading == "yes") {
			$animation_effect_class = $animation_loading_effects;
		}

		$animation_delay_class = null;
		if ($animation_loading == "yes" && !empty($animation_delay)) {
			$animation_delay_class = ' data-delay="'.$animation_delay.'"';
		}
	  
        $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,'alert fade in '.$type. ' ' .$el_class, $this->settings['base']);
        $class = setClass(array($css_class, $animation_loading_class, $animation_effect_class));

        $output .= '<div'.$class.''.$animation_delay_class.'><a class="close" href="#" data-dismiss="alert"><i class="font-icon-cross"></i></a>';
        $output .= wpb_js_remove_wpautop($content);
        $output .= '</div>'.$this->endBlockComment('az_alert_box');

        return $output;
    }
	
}
?>