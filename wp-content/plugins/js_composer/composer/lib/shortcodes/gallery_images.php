<?php
/**
 * WPBakery Visual Composer shortcodes
 *
 * @package WPBakeryVisualComposer
 *
 */

/* Portfolio Grid
---------------------------------------------------------- */

class WPBakeryShortCode_AZ_Gallery_Images extends WPBakeryShortCode {
  	protected function content($atts, $content = null) {
    	$animation_loading = $animation_loading_effects = $animation_delay = $el_class = $gallery_layout = $gallery_columns_count = $gallery_wall = $images_gallery = '';
        extract(shortcode_atts(array(
			'animation_loading' => '',
			'animation_loading_effects' => '',
			'gallery_layout' => '',
			'gallery_columns_count' => '',
			'gallery_wall' => '',
			'images_gallery' => '',
            'el_class' => ''
        ), $atts));
        $output = '';
		$images = explode(',', $images_gallery);
        $el_class = $this->getExtraClass($el_class);
		
		$animation_loading_class = null;
		if ($animation_loading == "yes") {
			$animation_loading_class = 'animated-content';
		}
		
		$animation_effect_class = null;
		if ($animation_loading == "yes") {
			$animation_effect_class = $animation_loading_effects;
		}
		
		/*
        $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,'gallery gallery-images'.$el_class, $this->settings['base']);
        $class = setClass(array($css_class, $animation_loading_class, $animation_effect_class));*/

        // Add Custom Class For Portfolio
		$gallery_full = null;
		if ($gallery_wall==true) {
			$gallery_full = ' gallery-full-width';

        if ( $gallery_columns_count=="2clm") { $gallery_columns_count = 'col-full-6'; }
			if ( $gallery_columns_count=="3clm") { $gallery_columns_count = 'col-full-4'; }
			if ( $gallery_columns_count=="4clm") { $gallery_columns_count = 'col-full-3'; }
			if ( $gallery_columns_count=="5clm") { $gallery_columns_count = 'col-full-2'; }
			if ( $gallery_columns_count=="6clm") { $gallery_columns_count = 'col-full-1'; }
		} else {
			if ( $gallery_columns_count=="2clm") { $gallery_columns_count = 'col-md-6'; }
			if ( $gallery_columns_count=="3clm") { $gallery_columns_count = 'col-md-4'; }
			if ( $gallery_columns_count=="4clm") { $gallery_columns_count = 'col-md-3'; }
			if ( $gallery_columns_count=="5clm") { $gallery_columns_count = 'col-md-3'; }
			if ( $gallery_columns_count=="6clm") { $gallery_columns_count = 'col-md-3'; }
		}

		// Custom ID for fancygallery
		$gallery_fancy = null;
		$gallery_cont_id = null;
		$gallery_fancy = 'gallery_fancy_'.uniqid().'';
		$gallery_cont_id = 'gallery-image-'.uniqid().'';

		$output .= '<div class="az-gallery row '.$gallery_full.' '. $el_class .'">';
		$output .= '<div id="'.$gallery_cont_id.'" class="az-gallery-image '.$gallery_layout.'">';
		$output .= '<ul id="gallery_image_'.uniqid().'">';

		if(!empty($images_gallery)){
			foreach($images as $image):
				$src = wp_get_attachment_image_src( $image, 'full' );
				$src_thumb = wp_get_attachment_image_src( $image, 'gallery-thumb' );
				$src_thumb_wall = wp_get_attachment_image_src( $image, 'gallery-wall-thumb' );

				$alt = ( get_post_meta($image, '_wp_attachment_image_alt', true) ) ? get_post_meta($image, '_wp_attachment_image_alt', true) : 'Insert Alt Text';

				$output .= '<li class="item-gallery '.$gallery_columns_count.'">';
				$output .= '<div class="item-gallery-container '.$animation_loading_class.' '.$animation_effect_class.'">';

				$output .= '<a class="fancy-wrap-gal fancybox" title="'.$alt.'" href="'.$src[0].'" data-fancybox-group="'.$gallery_fancy.'">';
				if ($gallery_layout == "masonry-gallery") {
					$output .= '<img src="'.$src[0].'" width="'.$src[1].'" height="'.$src[2].'" alt="'.$alt.'" class="img-responsive" />';
				} else {
					if ($gallery_wall==true) {
						$output .= '<img src="'.$src_thumb_wall[0].'" width="'.$src_thumb_wall[1].'" height="'.$src_thumb_wall[2].'" alt="'.$alt.'" class="img-responsive" />';
					} else {
						$output .= '<img src="'.$src_thumb[0].'" width="'.$src_thumb[1].'" height="'.$src_thumb[2].'" alt="'.$alt.'" class="img-responsive" />';
					}
				}
				$output .= '<span class="overlay"><span class="circle"><i class="font-icon-search"></i></span></span>';
				$output .= '</a>';

				$output .= '</div>';
				$output .= '</li>';
			endforeach;
		}

		$output .= '</ul>';
	  	$output .= '</div>';
	  	$output .= '</div>';

	  	$output .= '<script type="text/javascript">
	  				jQuery(document).ready(function(){
		  				var container = jQuery("#'.$gallery_cont_id.'");

		  				container.imagesLoaded(function() {
						    container.isotope({
						      // options
						      animationEngine: "best-available",
							  layoutMode: "sloppyMasonry",
						      itemSelector : ".item-gallery"
						    });
						});
					    
						jQuery(window).smartresize(function() {
							container.isotope("reLayout");
						});
					});
	  				</script>

	  	';

        /*
		$output .= '<div'.$class.'>';
		$output .= '<a class="fancy-wrap fancybox" title="' . $title . '" href="' . $image_string .'">';
		$output .= '<img class="img-responsive" alt="'.$title.'" src="' . $image_string .'" />';
		$output .= '<span class="overlay"><span class="circle"><i class="font-icon-plus-3"></i></span></span>';
		$output .= '</a>';

		
		if(!empty($images_gallery)){
			foreach($images as $image):
				$src = wp_get_attachment_image_src( $image, 'full' );
				$alt = ( get_post_meta($image, '_wp_attachment_image_alt', true) ) ? get_post_meta($image, '_wp_attachment_image_alt', true) : '';
				$output .= '<a class="fancy-wrap fancybox hidden" title="'.$alt.'" href="'.$src[0].'" '.$fancy_gallery.'></a>';
			endforeach;
		}
		*/

		$output.$this->endBlockComment('az_gallery_images')."\n";

        return $output;
    }
}

?>