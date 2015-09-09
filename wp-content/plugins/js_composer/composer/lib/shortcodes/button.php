<?php
/**
 * WPBakery Visual Composer shortcodes
 *
 * @package WPBakeryVisualComposer
 *
 */

/* Button
---------------------------------------------------------- */

class WPBakeryShortCode_AZ_Buttons extends WPBakeryShortCode {
  protected function content($atts, $content = null) {
    $animation_loading = $animation_loading_effects = $animation_delay = $buttonlabel = $buttonlink = $target = $buttonsize = $inverted = $checkicon = $checkicon_set = $icon = $icon_line = $icon_steady = $el_class = '';
      extract( shortcode_atts( array(
	  	'animation_loading' => '',
		'animation_loading_effects' => '',
		'animation_delay' => '',
	  	'buttonlabel' => '',
        'buttonlink' => '',
        'target' => '',
        'buttonsize' => '',
        'buttoncolor' => '',
        'custombuttoncolor' => '',
		'checkicon' => '',
		'checkicon_set' => '',
		'icon' => '',
		'icon_line' => '',
		'icon_steady' => '',
		'inverted' => false,
        'el_class' => ''
      ), $atts ) );
		$output = '';
		$el_class = $this->getExtraClass($el_class);

		if ( $target == 'same' || $target == '_self' ) { $target = ''; }
		if ( $target != '' ) { $target = ' target="'.$target.'"'; }

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

		$inverted_to = '';
		$buttonclass = null;
		if ($inverted==true) {
			$inverted_to = ' inverted';
			if($buttoncolor=="custom") {
				$buttoncolor = ' style="background-color: '.$custombuttoncolor.'; border-color: '.$custombuttoncolor.'; color: '.$custombuttoncolor.';"';
				$buttonclass = ' custom-button-color'; 
			}
		} else {
			if($buttoncolor=="custom") {
				$buttoncolor = ' style="background-color: '.$custombuttoncolor.'; border-color: '.$custombuttoncolor.';"';
				$buttonclass = ' custom-button-color'; 
			}
		}

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

		$class = setClass(array('button-main', $el_class, $buttonsize, $buttonclass, $inverted_to, $animation_loading_class, $animation_effect_class));

		$output .= '<a'.$class.$buttoncolor.' href="'.$buttonlink.'"'.$target.''.$animation_delay_class.'>'.$icon_output.$buttonlabel.'</a>';

		return $output.$this->endBlockComment('az_buttons')."\n";
  }
}

?>