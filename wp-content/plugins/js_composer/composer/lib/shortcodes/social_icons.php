<?php
/**
 * WPBakery Visual Composer shortcodes
 *
 * @package WPBakeryVisualComposer
 *
 */

/* Social Icons
---------------------------------------------------------- */

class WPBakeryShortCode_AZ_Social_Profile extends WPBakeryShortCode {
  protected function content($atts, $content = null) {
    $el_class = '';
      extract( shortcode_atts( array(
        'el_class' => ''
      ), $atts ) );
      $output = '';
      $el_class = $this->getExtraClass($el_class);
	  
	  $output .= '<ul class="social-icons '.$el_class.'">';
	  
	  if(isset($atts['px']))
	  $output .= '<li><a href="' . $atts['px'] . '" target="_blank"><i class="font-icon-social-500px"></i></a></li>';
	  
	  if(isset($atts['addthis']))
	  $output .= '<li><a href="' . $atts['addthis'] . '" target="_blank"><i class="font-icon-social-addthis"></i></a></li>';
	  
	  if(isset($atts['behance']))
	  $output .= '<li><a href="' . $atts['behance'] . '" target="_blank"><i class="font-icon-social-behance"></i></a></li>';
	  
	  if(isset($atts['bebo']))
	  $output .= '<li><a href="' . $atts['bebo'] . '" target="_blank"><i class="font-icon-social-bebo"></i></a></li>';
	  
	  if(isset($atts['blogger']))
	  $output .= '<li><a href="' . $atts['blogger'] . '" target="_blank"><i class="font-icon-social-blogger"></i></a></li>';
	  
	  if(isset($atts['deviantart']))
	  $output .= '<li><a href="' . $atts['deviantart'] . '" target="_blank"><i class="font-icon-social-deviant-art"></i></a></li>';
	  
	  if(isset($atts['digg']))
	  $output .= '<li><a href="' . $atts['digg'] . '" target="_blank"><i class="font-icon-social-digg"></i></a></li>';
	  
	  if(isset($atts['dribbble']))
	  $output .= '<li><a href="' . $atts['dribbble'] . '" target="_blank"><i class="font-icon-social-dribbble"></i></a></li>';
	  
	  if(isset($atts['email']))
	  $output .= '<li><a href="' . $atts['email'] . '" target="_blank"><i class="font-icon-social-email"></i></a></li>';
	  
	  if(isset($atts['envato']))
	  $output .= '<li><a href="' . $atts['envato'] . '" target="_blank"><i class="font-icon-social-envato"></i></a></li>';
	  
	  if(isset($atts['evernote']))
	  $output .= '<li><a href="' . $atts['evernote'] . '" target="_blank"><i class="font-icon-social-evernote"></i></a></li>';
	  
	  if(isset($atts['facebook']))
	  $output .= '<li><a href="' . $atts['facebook'] . '" target="_blank"><i class="font-icon-social-facebook"></i></a></li>';
	  
	  if(isset($atts['flickr']))
	  $output .= '<li><a href="' . $atts['flickr'] . '" target="_blank"><i class="font-icon-social-flickr"></i></a></li>';
	  
	  if(isset($atts['forrst']))
	  $output .= '<li><a href="' . $atts['forrst'] . '" target="_blank"><i class="font-icon-social-forrst"></i></a></li>';
	  
	  if(isset($atts['github']))
	  $output .= '<li><a href="' . $atts['github'] . '" target="_blank"><i class="font-icon-social-github"></i></a></li>';
	  
	  if(isset($atts['googleplus']))
	  $output .= '<li><a href="' . $atts['googleplus'] . '" target="_blank"><i class="font-icon-social-google-plus"></i></a></li>';
	  
	  if(isset($atts['grooveshark']))
	  $output .= '<li><a href="' . $atts['grooveshark'] . '" target="_blank"><i class="font-icon-social-grooveshark"></i></a></li>';
	  
	  if(isset($atts['instagram']))
	  $output .= '<li><a href="' . $atts['instagram'] . '" target="_blank"><i class="font-icon-social-instagram"></i></a></li>';
	  
	  if(isset($atts['lastfm']))
	  $output .= '<li><a href="' . $atts['lastfm'] . '" target="_blank"><i class="font-icon-social-last-fm"></i></a></li>';
	  
	  if(isset($atts['linkedin']))
	  $output .= '<li><a href="' . $atts['linkedin'] . '" target="_blank"><i class="font-icon-social-linkedin"></i></a></li>';
	  
	  if(isset($atts['myspace']))
	  $output .= '<li><a href="' . $atts['myspace'] . '" target="_blank"><i class="font-icon-social-myspace"></i></a></li>';
	  
	  if(isset($atts['paypal']))
	  $output .= '<li><a href="' . $atts['paypal'] . '" target="_blank"><i class="font-icon-social-paypal"></i></a></li>';
	  
	  if(isset($atts['photobucket']))
	  $output .= '<li><a href="' . $atts['photobucket'] . '" target="_blank"><i class="font-icon-social-photobucket"></i></a></li>';
	  
	  if(isset($atts['pinterest']))
	  $output .= '<li><a href="' . $atts['pinterest'] . '" target="_blank"><i class="font-icon-social-pinterest"></i></a></li>';
	  
	  if(isset($atts['quora']))
	  $output .= '<li><a href="' . $atts['quora'] . '" target="_blank"><i class="font-icon-social-quora"></i></a></li>';
	  
	  if(isset($atts['sharethis']))
	  $output .= '<li><a href="' . $atts['sharethis'] . '" target="_blank"><i class="font-icon-social-share-this"></i></a></li>';
	  
	  if(isset($atts['skype']))
	  $output .= '<li><a href="' . $atts['skype'] . '" target="_blank"><i class="font-icon-social-skype"></i></a></li>';
	  
	  if(isset($atts['soundcloud']))
	  $output .= '<li><a href="' . $atts['soundcloud'] . '" target="_blank"><i class="font-icon-social-soundcloud"></i></a></li>';
	  
	  if(isset($atts['stumbleupon']))
	  $output .= '<li><a href="' . $atts['stumbleupon'] . '" target="_blank"><i class="font-icon-social-stumbleupon"></i></a></li>';
	  
	  if(isset($atts['tumblr']))
	  $output .= '<li><a href="' . $atts['tumblr'] . '" target="_blank"><i class="font-icon-social-tumblr"></i></a></li>';
	  
	  if(isset($atts['twitter']))
	  $output .= '<li><a href="' . $atts['twitter'] . '" target="_blank"><i class="font-icon-social-twitter"></i></a></li>';
	  
	  if(isset($atts['viddler']))
	  $output .= '<li><a href="' . $atts['viddler'] . '" target="_blank"><i class="font-icon-social-viddler"></i></a></li>';
	  
	  if(isset($atts['vimeo']))
	  $output .= '<li><a href="' . $atts['vimeo'] . '" target="_blank"><i class="font-icon-social-vimeo"></i></a></li>';
	  
	  if(isset($atts['virb']))
	  $output .= '<li><a href="' . $atts['virb'] . '" target="_blank"><i class="font-icon-social-virb"></i></a></li>';
	  
	  if(isset($atts['wordpress']))
	  $output .= '<li><a href="' . $atts['wordpress'] . '" target="_blank"><i class="font-icon-social-wordpress"></i></a></li>';
	  
	  if(isset($atts['yahoo']))
	  $output .= '<li><a href="' . $atts['yahoo'] . '" target="_blank"><i class="font-icon-social-yahoo"></i></a></li>';
	  
	  if(isset($atts['yelp']))
	  $output .= '<li><a href="' . $atts['yelp'] . '" target="_blank"><i class="font-icon-social-yelp"></i></a></li>';
	  
	  if(isset($atts['youtube']))
	  $output .= '<li><a href="' . $atts['youtube'] . '" target="_blank"><i class="font-icon-social-youtube"></i></a></li>';
	  
	  if(isset($atts['zerply']))
	  $output .= '<li><a href="' . $atts['zerply'] . '" target="_blank"><i class="font-icon-social-zerply"></i></a></li>';
      
	  $output .= '</ul>';
	  
      return $output . $this->endBlockComment('az_social_profile') . "\n";
  }
}

?>