<?php
/**
 * WPBakery Visual Composer shortcodes
 *
 * @package WPBakeryVisualComposer
 *
 */

/* Progress Bar
---------------------------------------------------------- */

class WPBakeryShortCode_AZ_Progress_Bar extends WPBakeryShortCode {
  protected function content($atts, $content = null) {
    $animated_bar = $animation_loading = $animation_loading_effects = $animation_delay = $field = $percentage = $bgcolor = $checkicon = $checkicon_set = $icon = $icon_line = $icon_steady = $custombarcolor = $el_class = '';
      extract( shortcode_atts( array(
      	'animated_bar' => '',
	  	'animation_loading' => '',
		'animation_loading_effects' => '',
		'animation_delay' => '',
        'field' => '',
        'percentage' => '',
        'bgcolor' => '',
		'checkicon' => '',
		'checkicon_set' => '',
		'icon' => '',
		'icon_line' => '',
		'icon_steady' => '',
        'custombarcolor' => '',
        'el_class' => ''
      ), $atts ) );
      	$output = '';
		$el_class = $this->getExtraClass($el_class);

		if ($bgcolor=="custom") { $bgcolor = ' background-color: '.$custombarcolor.';'; }
		
		// Output Icon Set
		$icon_output = null;
		if ($checkicon=="custom_icon" && $checkicon_set=="entypo") { 
			$icon_output = '<i class="'.$icon.'"></i>';
		}
		else if ($checkicon=="custom_icon" && $checkicon_set=="lineicons") {
			$icon_output = '<i class="'.$icon_line.'"></i>';
		}
		else if ($checkicon=="custom_icon" && $checkicon_set=="steadyicons") {
			$icon_output = '<i class="'.$icon_steady.'"></i>';
		} else {
			$icon_output = "";
		}

		// Output Animation
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

		// Output Bar
		$bar_output = null;
		if ($animated_bar==true) {
			$bar_output .= '<div class="bar animable" data-percent="'.$percentage.'" style="'.$bgcolor.'"></div>';
		} else {
			$bar_output .= '<div class="bar" style="width: '.$percentage.'%; '.$bgcolor.'"></div>';
		}
	  

		$class = setClass(array('progress-bar', $el_class, $animation_loading_class, $animation_effect_class));

		$output .= '<div'.$class.''.$animation_delay_class.'>';
		$output .= '<div class="progress">';
		$output .= '<span class="field">'.$icon_output.$field.'</span>';
		$output .= $bar_output;
		$output .= '</div></div>';

		return $output . $this->endBlockComment('az_progress_bar') . "\n";
  }
}

?>