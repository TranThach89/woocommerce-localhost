<?php
/**
 * WPBakery Visual Composer shortcodes
 *
 * @package WPBakeryVisualComposer
 *
 */

/* Box Icon
---------------------------------------------------------- */

class WPBakeryShortCode_AZ_Icon extends WPBakeryShortCode {
  protected function content($atts, $content = null) {
    $animation_loading = $animation_loading_effects = $animation_delay = $icon_style = $icon_wrap_link = $icon_wrap_link_url = $target = $checkicon_set = $icon = $icon_line = $icon_steady = $icon_align = $icon_color = $custom_icon_color = $icon_size = $el_class = '';
      extract( shortcode_atts( array(
	  	'animation_loading' => '',
		'animation_loading_effects' => '',
		'animation_delay' => '',
		'icon_align' => '',
		'icon_color' => '',
		'custom_icon_color' => '',
		'icon_style' => '',
		'icon_size' => '',
	  	'checkicon_set' => '',
        'icon' => '',
        'icon_line' => '',
        'icon_steady' => '',
        'icon_wrap_link' => '',
        'icon_wrap_link_url' => '',
        'target' => '',
        'el_class' => ''
      ), $atts ) );
      $output = '';
      $el_class = $this->getExtraClass($el_class);

      	if ( $target == 'same' || $target == '_self' ) { $target = ''; }
      	if ( $target != '' ) { $target = 'target="'.$target.'"'; }
	  
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

		$icon_custom_value = null;
		$icon_style_color = null;
		if ($icon_color=="custom" && !empty($icon_size)) { $icon_custom_value = ' style="color:'.$custom_icon_color.'; font-size:'.$icon_size.'px;"'; $icon_style_color = 'style="border-color:'.$custom_icon_color.';"'; }
		else if (!empty($icon_size)) { $icon_custom_value = ' style="font-size:'.$icon_size.'px;"'; }

	  	// Output Icon Set
	  	$icon_output = null;
		if ($checkicon_set=="entypo") { 
			$icon_output = $icon;
		}
		if ($checkicon_set=="lineicons") {
			$icon_output = $icon_line;
		}
		if ($checkicon_set=="steadyicons") {
			$icon_output = $icon_steady;
		}
	  
		$class = setClass(array($icon_output, $icon_align, $el_class, $animation_loading_class, $animation_effect_class));

		if ($icon_wrap_link==true) {
			$output .= '<a href="'.$icon_wrap_link_url.'" '.$target.'>';
			$output .= '<span class="'.$icon_style.'" '.$icon_style_color.'><i'.$class.''.$animation_delay_class.$icon_custom_value.'></i></span>';
			$output .= '</a>';
		} else {
			$output .= '<span class="'.$icon_style.'" '.$icon_style_color.'><i'.$class.''.$animation_delay_class.$icon_custom_value.'></i></span>';
		}

		return $output.$this->endBlockComment('az_icon')."\n";
  }
}

?>