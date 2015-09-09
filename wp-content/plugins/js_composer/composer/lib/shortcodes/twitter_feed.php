<?php
/**
 * WPBakery Visual Composer shortcodes
 *
 * @package WPBakeryVisualComposer
 *
 */

/* Big Twitter Feed
---------------------------------------------------------- */

class WPBakeryShortCode_AZ_Big_Tweet_Feed extends WPBakeryShortCode {
  protected function content($atts, $content = null) {
    $animation_loading = $animation_loading_effects = $twitter_mode = $twitter_username = $num_tweet = $el_class = '';
      extract( shortcode_atts( array(
	  	'animation_loading' => '',
		'animation_loading_effects' => '',
		'twitter_mode' => '',
        'twitter_username' => '',
		'num_tweet' => '',
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

	  $twitter_slides_class = null;
	  if ($twitter_mode == "more_tweet") {
		$twitter_slides_class = 'slides';
	  }

	  $tweet_id = null;
	  if ($twitter_mode == "one_tweet") {
	  	$tweet_id = 'twitter-feed';
	  } else {
	  	$tweet_id = 'twitter-feed-slide';
	  }
	  
	  $class = setClass(array('tweet_list', $el_class, $animation_loading_class, $animation_effect_class, $twitter_slides_class));
	  $output .= '<div id="'.$tweet_id.'">
	  				<ul'.$class.'>';

	  if ($twitter_mode == "one_tweet") {
	  	$num_tweet = 1;
	  	$tweets = getTweets($num_tweet, $twitter_username);
	  	foreach($tweets as $tweet) {
		
		$output .= '
	  		<li>
				<span class="tweet_text">' . TwitterFilter($tweet['text']) . '</span>
				<span class="tweet_time"><a href="https://twitter.com/' . $twitter_username . '/status/' . $tweet['id_str'] . '">' . date('j F o \a\t g:i A', strtotime($tweet['created_at'])) . '</a></span>
			</li>';		
		}

	  } else {

	  	$tweets = getTweets(intval($num_tweet), $twitter_username);
	  	foreach($tweets as $tweet) {
		
		$output .= '
	  		<li>
				<span class="tweet_text">' . TwitterFilter($tweet['text']) . '</span>
				<span class="tweet_time"><a href="https://twitter.com/' . $twitter_username . '/status/' . $tweet['id_str'] . '">' . date('j F o \a\t g:i A', strtotime($tweet['created_at'])) . '</a></span>
			</li>';		
		}

	  }
	  
	  $output .= '</ul>
	  			</div>';
      
      return $output . $this->endBlockComment('az_big_tweet_feed') . "\n";
  }
}

?>