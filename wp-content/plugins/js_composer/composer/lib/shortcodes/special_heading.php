<?php
/**
 * WPBakery Visual Composer shortcodes
 *
 * @package WPBakeryVisualComposer
 *
 */

/* Special Heading
---------------------------------------------------------- */

class WPBakeryShortCode_AZ_Special_Heading extends WPBakeryShortCode {
  protected function content($atts, $content = null) {
    $content_heading = $animation_loading = $animation_loading_effects = $animation_delay = $heading_type = $heading_style = $heading_align = $heading_color = $custom_heading_color = $heading_size = $custom_heading_size = $custom_heading_line = $padding_bottom_heading = $decorative_heading = $el_class = '';
      extract( shortcode_atts( array(
	  	'animation_loading' => '',
		'animation_loading_effects' => '',
		'animation_delay' => '',
		'heading_type' => '',
		'heading_style' => '',
		'heading_align' => '',
		'heading_color' => '',
		'custom_heading_color' => '',
		'heading_size' => '',
		'custom_heading_size' => '',
		'custom_heading_line' => '',
		'padding_bottom_heading' => '',
		'content_heading' => '',
		'decorative_heading' => '',
        'el_class' => ''
      ), $atts ) );
      $output = '';
	  $el_class = $this->getExtraClass($el_class);

	  $heading_setup = null;
	  $decorative_heading_class = null;
	  $decorative_color = null;

	  if(!empty($custom_heading_line)) { $custom_heading_line = ' line-height:'.$custom_heading_line.'px;'; }

	  if ($heading_color=="custom" && $heading_size=="custom") { $heading_setup = ' style="color: '.$custom_heading_color.'; font-size: '.$custom_heading_size.'px;'.$custom_heading_line.'"'; $decorative_color = ' style="background-color: '.$custom_heading_color.';"'; }
	  else if ($heading_size=="custom") { $heading_setup = ' style="font-size: '.$custom_heading_size.'px;'.$custom_heading_line.'"'; }
	  else if ($heading_color=="custom") { $heading_setup = ' style="color: '.$custom_heading_color.';"'; $decorative_color = ' style="background-color: '.$custom_heading_color.';"'; }

	  if ( $decorative_heading == true ) {
	  	$decorative_heading_class = '<span class="decorative"'.$decorative_color.'></span>';
	  }

	  $padding_bottom_heading = ' style="padding-bottom: '.$padding_bottom_heading.'px;"';
	  
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
	  
	  $class = setClass(array('special-heading', $heading_align, $heading_style, $el_class, $animation_loading_class, $animation_effect_class));

	  $content_heading =  rawurldecode(base64_decode(strip_tags($content_heading)));
	  $output .= '<div'.$class.''.$animation_delay_class.''.$padding_bottom_heading.'>';
	  $output .= '<h'. $heading_type . $heading_setup .' class="'.$heading_style.'">'.$content_heading.'</h'. $heading_type. '>'.$decorative_heading_class.'';
	  $output .= '</div>';
	
	  return $output.$this->endBlockComment('az_special_heading')."\n";
  }
}

?>