<?php
/**
 * WPBakery Visual Composer shortcodes
 *
 * @package WPBakeryVisualComposer
 *
 */

/* Latest Posts
---------------------------------------------------------- */

class WPBakeryShortCode_AZ_Latest_Posts extends WPBakeryShortCode {
  protected function content($atts, $content = null) {
    $posts_format_display = $posts_layout = $post_columns_count = $animation_loading = $animation_loading_effects = $post_number = $post_categories = $el_class = '';
      extract( shortcode_atts( array(
      	'posts_format_display' => '',
      	'posts_layout' => '',
	  	'post_columns_count' => '',
		'animation_loading' => '',
		'animation_loading_effects' => '',
		'post_number' => 'all',
		'post_categories' => '',
        'el_class' => ''
      ), $atts ) );
      $output = '';
	  $el_class = $this->getExtraClass($el_class);
	  
	  global $post;
	  
	  // Post teasers count
      if ( $post_number != '' && !is_numeric($post_number) ) $post_number = -1;
      if ( $post_number != '' && is_numeric($post_number) ) $post_number = $post_number;
	  
	  if ( $post_columns_count=="2clm") { $post_columns_count = 'col-md-6'; }
	  if ( $post_columns_count=="3clm") { $post_columns_count = 'col-md-4'; }
	  if ( $post_columns_count=="4clm") { $post_columns_count = 'col-md-3'; }
	  
	  $args = array( 
			'showposts' => $post_number,
			'category_name' => $post_categories,
			'ignore_sticky_posts' => 1
		);
		
		$animation_loading_class = null;
		if ($animation_loading == "yes") {
			$animation_loading_class = 'animated-content';
		}
		
		$animation_effect_class = null;
		if ($animation_loading == "yes") {
			$animation_effect_class = $animation_loading_effects;
	 	}

	  // Run query
	  $my_query = new WP_Query($args);
	  
	  $output .= '<div class="row no-sortable '.$posts_layout.' '.$el_class.'">';
	  $output .= '<ul id="latest-posts">';
	  
	  while($my_query->have_posts()) : $my_query->the_post();
	    
		$post_id = $my_query->post->ID;
		
		$thumb = get_post_thumbnail_id();
		$img_thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'latest-post-thumb' );
		$img_masonry = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		
		$num_comments = get_comments_number(); 	
		$comments_output = null;
		
		if ( comments_open() ) {
			if ( $num_comments == 0 ) {
				$comments = __('No Comments', 'js_composer');
			} elseif ( $num_comments > 1 ) {
				$comments = $num_comments . __(' Comments', 'js_composer');
			} else {
				$comments = __('1 Comment', 'js_composer');
			}
			$comments_output .= '<a href="'.get_comments_link().'">'. $comments.'</a>';
		}

		$post_output = null;
		$post_output = '<div class="post-name">
							<h2 class="entry-title">
						 		<a href="'.get_permalink($post_id).'" title="'.get_the_title().'"> '.get_the_title().'</a>
						 	</h2>
						</div>
						<div class="entry-content"><p>'.get_the_excerpt().'</p></div>
						<div class="entry-meta entry-header">
							<span class="published">'.get_the_time(get_option('date_format')).'</span>
							<span class="meta-sep"> / </span>
							<span class="comment-count">'.$comments_output.'</span>
						</div>';

		$output .= '<li class="item-blog '.$post_columns_count.'">
					<div class="item-container-post '.$animation_loading_class.' '.$animation_effect_class.'">';
		
		$output .= '<article class="post">';
		
		// Featured Image Only
		if ($posts_format_display == "img-featured") {
			if(!empty($thumb)) {
				$output .= '<div class="post-thumb">
								<a title="'.get_the_title().'" href="'.get_permalink($post_id).'" class="hover-wrap">';

				if ($posts_layout == "masonry-posts") {
					$output .= '<img src="'.$img_masonry[0].'" width="'.$img_masonry[1].'" height="'.$img_masonry[2].'" alt="'.get_the_title().'" class="img-responsive" />';
				} else {
					$output .= '<img class="img-responsive" src="'.$img_thumb[0].'" width="'.$img_thumb[1].'" height="'.$img_thumb[2].'" alt="'.get_the_title().'" />';
				}

				$output .= '<span class="overlay"><span class="circle"><i class="font-icon-plus-3"></i></span></span>
							 	</a>
							</div>';
			}
			
			$output .= $post_output;
		} 

		// Display Normal Blog Mode
		else {
			// Audio
			if ( has_post_format( 'audio' )) {
				$mp3 = get_post_meta($post->ID, '_az_audio_mp3', true);   
				$output .= '<div class="audio-thumb">';
				$output .= '<div id="audio-'.get_the_ID().'">
    							<audio style="width:100%; height:30px;" class="audio-js" controls preload src="'.$mp3.'"></audio>
       			 			</div>';
				$output .= '</div>';

				$output .= $post_output;
			}
			// Video
			else if ( has_post_format( 'video' )) {
				$webm = get_post_meta($post->ID, '_az_video_webm', true);
        		$mp4 = get_post_meta($post->ID, '_az_video_mp4', true);
    			$ogv = get_post_meta($post->ID, '_az_video_ogv', true);
    			$poster_video = get_post_meta($post->ID, '_az_video_poster_url', true);
    			$video_embed = get_post_meta($post->ID, '_az_video_embed', true);  
				
				$output .= '<div class="video-thumb">';
				if( !empty( $video_embed ) ) {
					$output .= '<div class="video-wrap">
                					<div class="video-embed">
                					'.stripslashes(htmlspecialchars_decode($video_embed)).'
                					</div>
                				</div>';
				} else {
					$output .= '<video id="video-'.get_the_ID().'" class="video-js vjs-default-skin" preload="auto" style="width:100%; height:100%;" poster="'.$poster_video.'">';
					if(!empty($webm)) { $output .= '<source src="'.$webm.'" type="video/webm">'; }
                	if(!empty($mp4)) { $output .= '<source src="'.$mp4.'" type="video/mp4">'; }
    				if(!empty($ogv)) { $output .= '<source src="'.$ogv.'" type="video/ogg">'; }
					$output .= '</video>';
				}
				$output .= '</div>';

				$output .= $post_output;
			}
			// Gallery
			else if ( has_post_format( 'gallery' )) {
				$rev_slider_alias = get_post_meta($post->ID, '_az_gallery', true); 
				$layer_slider_id = get_post_meta($post->ID, '_az_layer_gallery', true);

				$output .= '<div class="post-thumb">';
				if(!empty($rev_slider_alias)) { 
					$output .= do_shortcode('[rev_slider '.$rev_slider_alias.']');
				} else {
					$output .= do_shortcode('[layerslider id="'.$layer_slider_id.'"]');
				}
				$output .= '</div>';

				$output .= $post_output;
			}
			// Image
			else if ( has_post_format( 'image' )) {
				if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) {
					$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				}

				$output .= '<div class="post-thumb">';
				$output .= '<a title="'.get_the_title().'" href="'.$featured_image[0].'" class="hover-wrap fancybox">';
				$output .= '<img class="img-responsive" src="'.$featured_image[0].'" width="'.$featured_image[1].'" height="'.$featured_image[2].'" alt="'.get_the_title().'" />';
				$output .= '<span class="overlay"><span class="circle"><i class="font-icon-search"></i></span></span>
							</a>';
				$output .= '</div>';

				$output .= $post_output;
			}
			// Quote
			else if ( has_post_format( 'quote' )) {
				$quote = get_post_meta($post->ID, '_az_quote', true);

				$output .= '<div class="post-quote">
							<h2 class="entry-title">'.$quote.'</h2>
							<p class="quote-source"><a href="'.get_permalink($post_id).'" title="'.get_the_title().'">'.get_the_title().' #</a></p>
							</div>';
			}
			// Link 
			else if ( has_post_format( 'link' )) {
				$link_url = get_post_meta($post->ID, '_az_link', true);

				$output .= '<div class="post-link">
							<h2 class="entry-title">
								<a href="'.$link_url.'" title="'.get_the_title().'" target="_blank">'.get_the_title().'</a>
							</h2>
							<p class="link-source">
								<a href="'.$link_url.'" target="_blank">'.$link_url.'</a>
								<a href="'.get_permalink($post_id).'" title="'.get_the_title().'">#</a>
							</p>
							</div>';
			} 
			// Standard
			else {
				if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) {
					$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
					
					$output .= '<div class="post-thumb">
								<a title="'.get_the_title().'" href="'.get_permalink($post_id).'" class="hover-wrap">';
					$output .= '<img class="img-responsive" src="'.$featured_image[0].'" width="'.$featured_image[1].'" height="'.$featured_image[2].'" alt="'.get_the_title().'" />';
					$output .= '<span class="overlay"><span class="circle"><i class="font-icon-plus-3"></i></span></span></a>';
					$output .= '</div>';
				}

				$output .= $post_output;
			}			
		}
					
		$output .= '</article>';		

		$output .= '</div>
					</li>';
	
	  endwhile;
	  
	  wp_reset_query();
	  
	  $output .= '</ul>';
	  
	  $output .= '</div>';
	
	  return $output . $this->endBlockComment('az_latest_posts') . "\n";
  }
}

?>