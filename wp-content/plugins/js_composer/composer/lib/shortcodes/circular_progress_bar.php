<?php
/**
 * WPBakery Visual Composer shortcodes
 *
 * @package WPBakeryVisualComposer
 *
 */

/* Circular Progress Bar
---------------------------------------------------------- */

class WPBakeryShortCode_AZ_Circular_Progress_Bar extends WPBakeryShortCode {
  protected function content($atts, $content = null) {
     $animation_loading = $animation_loading_effects = $animation_delay = $check_circular_type = $checkicon_set = $icon = $icon_line = $icon_steady = $icon_color = $custom_icon_color = $circular_field = $field_color = $custom_field_color = $circular_percentage = $percentage_color = $custom_percentage_color = $circular_bgcolor = $circular_trackcolor = $circular_size = $circular_line = $el_class = '';
      extract( shortcode_atts( array(
	  	'animation_loading' => '',
		'animation_loading_effects' => '',
		'animation_delay' => '',
		'check_circular_type' => '',
		'checkicon_set' => '',
        'icon' => '',
        'icon_line' => '',
        'icon_steady' => '',
        'icon_color' => '',
		'custom_icon_color' => '',
        'circular_field' => '',
        'field_color' => '',
        'custom_field_color' => '',
        'circular_percentage' => '',
        'percentage_color' => '',
        'custom_percentage_color' => '',
        'circular_bgcolor' => '',
        'circular_trackcolor' => '',
		'circular_size' => '',
		'circular_line' => '',
        'el_class' => ''
      ), $atts ) );
		$output = '';
		$el_class = $this->getExtraClass($el_class);

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

		// Control Size and Line Width of Circle Progress Bar
		if( !empty($circular_size)) {
		  $size_output = $circular_size;
		} else {
		  $size_output = 170;
		}

		if( !empty($circular_line)) {
		  $line_output = $circular_line;
		} else {
		  $line_output = 6;
		}

	  	// Output
		$circular_output = null;
		$color_icon = null;
		$color_field = null;
		$color_percentage = null;
		
		// Check Colors
		if ($icon_color=="custom") { 
			$color_icon = ' style="color: '.$custom_icon_color.';"';  
		}
		if ($field_color=="custom") { 
			$color_field = ' style="color: '.$custom_field_color.';"';  
		}
		if ($percentage_color=="custom") { 
			$color_percentage = ' style="color: '.$custom_percentage_color.';"';  
		}

	    if ($check_circular_type=="field_mode") { 
			$circular_output = '<span class="field-text"'.$color_field.'>'.$circular_field.'</span>';  
	    }

	    if ($check_circular_type=="ani_percentage") { 
			$circular_output = '<span class="percentage no-field"'.$color_percentage.'>'.$circular_percentage.'</span>';  
	    }

	    // Output Icon Set
		if ($check_circular_type=="icon_mode" && $checkicon_set=="entypo") { 
			$circular_output = '<span class="field-icon"'.$color_icon.'><i class="'.$icon.'"></i></span>';
		}
		if ($check_circular_type=="icon_mode" && $checkicon_set=="lineicons") {
			$circular_output = '<span class="field-icon"'.$color_icon.'><i class="'.$icon_line.'"></i></span>';
		}
		if ($check_circular_type=="icon_mode" && $checkicon_set=="steadyicons") {
			$circular_output = '<span class="field-icon"'.$color_icon.'><i class="'.$icon_steady.'"></i></span>';
		}
	 
		$class = setClass(array('progress-circle', $el_class, $animation_loading_class, $animation_effect_class));
		  	
		$output .= '<div'.$class.''.$animation_delay_class.'>';
		$output .= '<div class="chart" data-bgcolor="'.$circular_bgcolor.'" data-trackcolor ="'.$circular_trackcolor.'" data-size="'.$size_output.'" data-line="'.$line_output.'" data-percent="'.$circular_percentage.'" style="line-height: '.$size_output.'px;">'.$circular_output.'</div>';
		$output .= '</div>';
      
      	return $output . $this->endBlockComment('az_circular_progress_bar') . "\n";
  }
}

?>