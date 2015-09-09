<?php
// Portfolio Pagination
function round_num($num, $to_nearest) {
    return floor($num/$to_nearest)*$to_nearest;
}

if (!function_exists('pagenavi')) {
    function pagenavi($query, $before = '', $after = '') {

        wp_reset_query();
        global $wpdb, $paged;

        $pagenavi_options = array();
        //$pagenavi_options['pages_text'] = ('Page %CURRENT_PAGE% of %TOTAL_PAGES%:');
        $pagenavi_options['pages_text'] = ('');
        $pagenavi_options['current_text'] = '%PAGE_NUMBER%';
        $pagenavi_options['page_text'] = '%PAGE_NUMBER%';
        $pagenavi_options['first_text'] = __('First Page', 'js_composer');
        $pagenavi_options['last_text'] = __('Last Page', 'js_composer');
        $pagenavi_options['next_text'] = __("Next", "js_composer");
        $pagenavi_options['prev_text'] = __("Previous", "js_composer");
        $pagenavi_options['dotright_text'] = '...';
        $pagenavi_options['dotleft_text'] = '...';
        $pagenavi_options['num_pages'] = 5; //continuous block of page numbers
        $pagenavi_options['always_show'] = 0;
        $pagenavi_options['num_larger_page_numbers'] = 0;
        $pagenavi_options['larger_page_numbers_multiple'] = 5;

        $output = "";

        //If NOT a single Post is being displayed
        if (!is_single()) {
            $request = $query->request;
            //intval - Get the integer value of a variable
            $posts_per_page = intval(get_query_var('posts_per_page'));
            //Retrieve variable in the WP_Query class.
            if ( get_query_var('paged') ) {
                $paged = get_query_var('paged');
            } elseif ( get_query_var('page') ) {
                $paged = get_query_var('page');
            } else {
                $paged = 1;
            }
            $numposts = $query->found_posts;
            $max_page = $query->max_num_pages;

            //empty - Determine whether a variable is empty
            if(empty($paged) || $paged == 0) {
                $paged = 1;
            }

            $pages_to_show = intval($pagenavi_options['num_pages']);
            $larger_page_to_show = intval($pagenavi_options['num_larger_page_numbers']);
            $larger_page_multiple = intval($pagenavi_options['larger_page_numbers_multiple']);
            $pages_to_show_minus_1 = $pages_to_show - 1;
            $half_page_start = floor($pages_to_show_minus_1/2);
            //ceil - Round fractions up (http://us2.php.net/manual/en/function.ceil.php)
            $half_page_end = ceil($pages_to_show_minus_1/2);
            $start_page = $paged - $half_page_start;

            if($start_page <= 0) {
                $start_page = 1;
            }

            $end_page = $paged + $half_page_end;
            if(($end_page - $start_page) != $pages_to_show_minus_1) {
                $end_page = $start_page + $pages_to_show_minus_1;
            }
            if($end_page > $max_page) {
                $start_page = $max_page - $pages_to_show_minus_1;
                $end_page = $max_page;
            }
            if($start_page <= 0) {
                $start_page = 1;
            }

            $larger_per_page = $larger_page_to_show*$larger_page_multiple;
            //round_num() custom function - Rounds To The Nearest Value.
            $larger_start_page_start = (round_num($start_page, 10) + $larger_page_multiple) - $larger_per_page;
            $larger_start_page_end = round_num($start_page, 10) + $larger_page_multiple;
            $larger_end_page_start = round_num($end_page, 10) + $larger_page_multiple;
            $larger_end_page_end = round_num($end_page, 10) + ($larger_per_page);

            if($larger_start_page_end - $larger_page_multiple == $start_page) {
                $larger_start_page_start = $larger_start_page_start - $larger_page_multiple;
                $larger_start_page_end = $larger_start_page_end - $larger_page_multiple;
            }
            if($larger_start_page_start <= 0) {
                $larger_start_page_start = $larger_page_multiple;
            }
            if($larger_start_page_end > $max_page) {
                $larger_start_page_end = $max_page;
            }
            if($larger_end_page_end > $max_page) {
                $larger_end_page_end = $max_page;
            }
            if($max_page > 1 || intval($pagenavi_options['always_show']) == 1) {
                $pages_text = str_replace("%CURRENT_PAGE%", number_format_i18n($paged), $pagenavi_options['pages_text']);
                $pages_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pages_text);
                $output .= $before.'<ul class="pagenavi">'."\n";

                if(!empty($pages_text)) {
                    $output .= '<li><span class="pages">'.$pages_text.'</span></li>';
                }
                if ($paged > 1) {
                    $output .= '<li class="prev">' . get_previous_posts_link($pagenavi_options['prev_text']) . '</li>';
                }

                if ($start_page >= 2 && $pages_to_show < $max_page) {
                    $first_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['first_text']);
                    $output .= '<li><a href="'.esc_url(get_pagenum_link()).'" class="first" title="'.$first_page_text.'">1</a></li>';
                    if(!empty($pagenavi_options['dotleft_text'])) {
                        $output .= '<li><span class="expand">'.$pagenavi_options['dotleft_text'].'</span></li>';
                    }
                }

                if($larger_page_to_show > 0 && $larger_start_page_start > 0 && $larger_start_page_end <= $max_page) {
                    for($i = $larger_start_page_start; $i < $larger_start_page_end; $i+=$larger_page_multiple) {
                        $page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
                        $output .= '<li><a href="'.esc_url(get_pagenum_link($i)).'" class="single_page" title="'.$page_text.'">'.$page_text.'</a></li>';
                    }
                }

                for($i = $start_page; $i  <= $end_page; $i++) {
                    if($i == $paged) {
                        $current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
                        $output .= '<li><span class="current">'.$current_page_text.'</span></li>';
                    } else {
                        $page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
                        $output .= '<li><a href="'.esc_url(get_pagenum_link($i)).'" class="single_page" title="'.$page_text.'">'.$page_text.'</a></li>';
                    }
                }

                if ($end_page < $max_page) {
                    if(!empty($pagenavi_options['dotright_text'])) {
                        $output .= '<li><span class="expand">'.$pagenavi_options['dotright_text'].'</span></li>';
                    }
                    $last_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['last_text']);
                    $output .= '<li><a href="'.esc_url(get_pagenum_link($max_page)).'" class="last" title="'.$last_page_text.'">'.$max_page.'</a></li>';
                }
                $output .= '<li class="next">' . get_next_posts_link($pagenavi_options['next_text'], $max_page) . '</li>';

                if($larger_page_to_show > 0 && $larger_end_page_start < $max_page) {
                    for($i = $larger_end_page_start; $i <= $larger_end_page_end; $i+=$larger_page_multiple) {
                        $page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
                        $output .= '<li><a href="'.esc_url(get_pagenum_link($i)).'" class="single_page" title="'.$page_text.'">'.$page_text.'</a></li>';
                    }
                }
                $output .= '</ul>'.$after."\n";
            }
        }

        return $output;
    }
}



/**
 * WPBakery Visual Composer shortcodes
 *
 * @package WPBakeryVisualComposer
 *
 */

/* Portfolio Grid
---------------------------------------------------------- */

class WPBakeryShortCode_AZ_Portfolio_Grid extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        $portfolio_layout = $portfolio_wall = $link_portfolio_item = $portfolio_columns_count = $animation_loading = $animation_loading_effects = $portfolio_post_number = $portfolio_post_link = $portfolio_sortable_name = $portfolio_sortable_mode = $portfolio_categories = $portfolio_pagination = $orderby = $order = $el_class = '';
        extract( shortcode_atts( array(
            'portfolio_layout' => '',
            'portfolio_wall' => '',
            'link_portfolio_item' => '',
            'animation_loading' => '',
            'animation_loading_effects' => '',
            'portfolio_columns_count' => '',
            'portfolio_post_number' => 'all',
            'portfolio_post_link' => '',
            'portfolio_sortable_name' => '',
            'portfolio_sortable_mode' => 'yes',
            'portfolio_categories' => '',
            'portfolio_pagination' => '',
            'orderby' => NULL,
            'order' => 'DESC',
            'el_class' => ''
        ), $atts ) );
        $output = '';
        $el_class = $this->getExtraClass($el_class);

        global $post;

        // Narrow by categories
        if($portfolio_categories == 'portfolio')
            $portfolio_categories = '';

        // Post teasers count
        if ( $portfolio_post_number != '' && !is_numeric($portfolio_post_number) ) $portfolio_post_number = -1;
        if ( $portfolio_post_number != '' && is_numeric($portfolio_post_number) ) $portfolio_post_number = $portfolio_post_number;

        // Add Custom Class For Portfolio
        $portfolio_full = null;
        if ($portfolio_wall==true) {
            $portfolio_full = ' portfolio-full-width';

            if ( $portfolio_columns_count=="2clm") { $portfolio_columns_count = 'col-full-6'; }
            if ( $portfolio_columns_count=="3clm") { $portfolio_columns_count = 'col-full-4'; }
            if ( $portfolio_columns_count=="4clm") { $portfolio_columns_count = 'col-full-3'; }
            if ( $portfolio_columns_count=="5clm") { $portfolio_columns_count = 'col-full-2'; }
            if ( $portfolio_columns_count=="6clm") { $portfolio_columns_count = 'col-full-1'; }
        }

        else {
            if ( $portfolio_columns_count=="2clm") { $portfolio_columns_count = 'col-md-6'; }
            if ( $portfolio_columns_count=="3clm") { $portfolio_columns_count = 'col-md-4'; }
            if ( $portfolio_columns_count=="4clm") { $portfolio_columns_count = 'col-md-3'; }
            if ( $portfolio_columns_count=="5clm") { $portfolio_columns_count = 'col-md-3'; }
            if ( $portfolio_columns_count=="6clm") { $portfolio_columns_count = 'col-md-3'; }
        }

        // List Portfolio
        if ($portfolio_layout=='list-portfolio') {
            $portfolio_full = ' portfolio-full-width';
            $portfolio_columns_count = 'col-full-area';
        }

        if ( get_query_var('paged') ) {
            $paged = get_query_var('paged');
        } elseif ( get_query_var('page') ) {
            $paged = get_query_var('page');
        } else {
            $paged = 1;
        }

        $args = array(
            'posts_per_page' => $portfolio_post_number,
            'post_type' => 'portfolio',
            'paged' => $paged,
            'project-category' => $portfolio_categories,
            'orderby' => $orderby,
            'order' => $order
        );

        // Run query
        $my_query = new WP_Query($args);

        if($portfolio_layout == "grid-portfolio" || $portfolio_layout == "masonry-portfolio") {
            if($portfolio_sortable_mode == "yes") {

                // $output .= '<input id="datepicker" type="text"><button id="button">Do stuff </button>';

                $output .= '
					  	<div id="portfolio-filter" class="row'.$portfolio_full.' desktop-filter">
							<div class="col-md-12">
								<div class="portfolio-right">
									<ul class="option-set" data-option-key="filter">
											<li class="has-items"><a class="selected drop-selected" href="#filter" data-option-value="*">'. $portfolio_sortable_name . '</a></li>';
                $list_categories = get_categories("taxonomy=project-category");
                foreach ($list_categories as $list_category) :
                    if(empty($portfolio_categories)){
                        $output .= '<li data-ot="' . $list_category->description . '"><div class="area-desc ' . strtolower(str_replace(" ","-", ($list_category->slug))) . '"></div><a href="#filter" class="' . strtolower(str_replace(" ","-", ($list_category->slug))) . '" data-option-value=".' . strtolower(str_replace(" ","-", ($list_category->slug))) . '">' . $list_category->name . '</a></li>';
                    }
                    else{
                        if(strstr($portfolio_categories, $list_category->slug))
                        {
                            $output .= '<li><a href="#filter" class="' . strtolower(str_replace(" ","-", ($list_category->slug))) . '" data-option-value=".' . strtolower(str_replace(" ","-", ($list_category->slug))) . '">' . $list_category->name . '</a></li>';
                        }
                    }
                endforeach;
                $output .= '			</ul>
								</div>
							</div>
						</div>';

                // Mobile Version
                $output .= '
					  	<div id="portfolio-filter-mobile" class="row'.$portfolio_full.' mobile-filter">
							<div class="col-md-12">
								<div class="dropdown">
									<div class="dropmenu">
										<p class="selected">'.$portfolio_sortable_name.'</p>
										<i class="font-icon-arrow-down-simple-thin-round"></i>
									</div>					
									<div class="dropmenu-active">
										<ul class="option-set" data-option-key="filter">
											<li class="has-items"><a class="selected drop-selected" href="#filter" data-option-value="*">'. $portfolio_sortable_name . '</a></li>';
                $list_categories = get_categories("taxonomy=project-category");
                foreach ($list_categories as $list_category) :
                    if(empty($portfolio_categories)){
                        $output .= '<li><a href="#filter" class="' . strtolower(str_replace(" ","-", ($list_category->slug))) . '" data-option-value=".' . strtolower(str_replace(" ","-", ($list_category->slug))) . '">' . $list_category->name . '</a></li>';
                    }
                    else{
                        if(strstr($portfolio_categories, $list_category->slug))
                        {
                            $output .= '<li><a href="#filter" class="' . strtolower(str_replace(" ","-", ($list_category->slug))) . '" data-option-value=".' . strtolower(str_replace(" ","-", ($list_category->slug))) . '">' . $list_category->name . '</a></li>';
                        }
                    }
                endforeach;
                $output .= '				</ul>
									</div>
								</div>							
							</div>
						</div>';
            }
        }

        $sortable_class = '';
        if ($portfolio_sortable_mode == "no") {
            $sortable_class = ' no-sortable';
        }

        $output .= '<div class="row '.$portfolio_full.' '. $el_class .'">';
        $output .= '<div id="portfolio-projects" class="'.$sortable_class.' '.$portfolio_layout .'">';
        $output .= '<ul id="projects">';



        while($my_query->have_posts()) : $my_query->the_post();

            $terms = get_the_terms($post->id,"project-category");
            $list_categories = NULL;

            if ( !empty($terms) ){
                foreach ( $terms as $term ) {
                    $list_categories .= strtolower($term->slug) . ' ';
                }
            }

            $attrs = get_the_terms( $post->ID, 'project-attribute' );
            $attributes_fields = NULL;

            if ( !empty($attrs) ){
                foreach ( $attrs as $attr ) {
                    $attributes_fields[] = $attr->name;
                }
                $on_attributes = join( " - ", $attributes_fields );
            }

            $post_id = $my_query->post->ID;

            $img_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
            $img_thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'portfolio-thumb' );
            $img_wall_thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'portfolio-wall-thumb' );

            $fancy_video = get_post_meta($post->ID, '_az_fancy_video', true);
            $fancy_gallery = get_post_meta($post->ID, '_az_fancy_gallery', true);
            $fancy_image_popup = get_post_meta($post->ID, '_az_fancy_image_full', true);
            $fancy_image_gallery = get_post_meta($post->ID, '_az_fancy_image_gallery', true);

            $images = explode(',', $fancy_image_gallery);

            $customFancyImg = (!empty($fancy_image_popup)) ? $fancy_image_popup : $img_url[0];

            if(!empty($fancy_gallery)) { $fancy_gallery = 'data-fancybox-group="'. strtolower($fancy_gallery) .'"'; }

            $animation_loading_class = null;
            if ($animation_loading == "yes") {
                $animation_loading_class = 'animated-content';
            }

            $animation_effect_class = null;
            if ($animation_loading == "yes") {
                $animation_effect_class = $animation_loading_effects;
            }

            $datefilter = get_post_meta(get_the_ID(), 'event-date', true);

            $output .= '<li class="item-project '.$portfolio_columns_count. ' ' . $list_categories .' '.$datefilter.'">
					<div class="item-container '. $animation_loading_class .' '. $animation_effect_class .'">';

            //check specific options are checked
            $checked = get_post_meta(get_the_ID(), 'event-area', true);
            if($checked['Club']) {
                $output .= '<div class="area club"></div>';
            }else{
            }

            if($checked['Garden']) {
                $output .= '<div class="area garden"></div>';
            }else{
            }

            if($checked['Live Room']) {
                $output .= '<div class="area live-room"></div>';
            }else{
            }

            if($portfolio_post_link == "link_fancybox") {

                if( !empty($fancy_video)) {
                    $output .= '<div class="hover-wrap">
							<a class="fancybox-media" href="'. $fancy_video .'" title="'. get_the_title() .'" '. $fancy_gallery .'><span class="cont"><i class="font-icon-plus-3"></i></span></a>';

                    if ($portfolio_layout == "list-portfolio") {
                        $output .= '<div class="bg" style="background: url('.$img_url[0].') center center no-repeat; background-size: cover;"></div>';
                    }
                    else {

                        if ($portfolio_layout == "masonry-portfolio") {
                            $output .= '<img src="'.$img_url[0].'" width="'.$img_url[1].'" height="'.$img_url[2].'" alt="'.get_the_title().'" class="img-responsive" />';
                        } else {
                            if ($portfolio_wall==true) {
                                $output .= '<img src="'. $img_wall_thumb[0] .'" width="'.$img_wall_thumb[1].'" height="'.$img_wall_thumb[2].'" alt="'. get_the_title() .'" class="img-responsive" />';
                            } else {
                                $output .= '<img src="'. $img_thumb[0] .'" width="'.$img_thumb[1].'" height="'.$img_thumb[2].'" alt="'. get_the_title() .'" class="img-responsive" />';
                            }
                        }

                    }

                    $output .= '<div class="project-name">';

                    if ($portfolio_layout == "list-portfolio") {
                        $output .= '
								<div class="container">
									<div class="row">
										<div class="col-md-12">';
                    }

                    $output .= '<div class="va">';

                    if ($link_portfolio_item==true) {
                        $output .= '<div class="project-title">
													<h3>'. get_the_title() .'</h3>
													<h4>'. $on_attributes .'</h4>
												</div>';
                    } else {
                        $output .= '<a class="project-title" href="'. get_permalink($post_id) .'" title="'. get_the_title() .'">
													<h3>'. get_the_title() .'</h3>
													<h4>'. $on_attributes .'</h4>
												</a>';
                    }

                    $output .= '</div>';

                    if ($portfolio_layout == "list-portfolio") {
                        $output .= '
										</div>
									</div>
								</div>';
                    }

                    $output .= '</div>
							</div>';

                } else {

                    $output .= '<div class="hover-wrap">
							<a class="fancybox" href="'. $customFancyImg .'" title="'. get_the_title() .'" '. $fancy_gallery .'><span class="cont"><i class="font-icon-plus-3"></i></span></a>';

                    if ($portfolio_layout == "list-portfolio") {
                        $output .= '<div class="bg" style="background: url('.$img_url[0].') center center no-repeat; background-size: cover;"></div>';
                    }
                    else {

                        if ($portfolio_layout == "masonry-portfolio") {
                            $output .= '<img src="'. $img_url[0] .'" width="'.$img_url[1].'" height="'.$img_url[2].'" alt="'. get_the_title() .'" class="img-responsive" />';
                        } else {
                            if ($portfolio_wall==true) {
                                $output .= '<img src="'. $img_wall_thumb[0] .'" width="'.$img_wall_thumb[1].'" height="'.$img_wall_thumb[2].'" alt="'. get_the_title() .'" class="img-responsive" />';
                            } else {
                                $output .= '<img src="'. $img_thumb[0] .'" width="'.$img_thumb[1].'" height="'.$img_thumb[2].'" alt="'. get_the_title() .'" class="img-responsive" />';
                            }
                        }

                    }

                    // FancyBox Gallery
                    if(!empty($fancy_image_gallery)){
                        foreach($images as $image):
                            $src = wp_get_attachment_image_src( $image, 'full' );
                            $alt = ( get_post_meta($image, '_wp_attachment_image_alt', true) ) ? get_post_meta($image, '_wp_attachment_image_alt', true) : '';
                            $output .= '<a class="fancy-wrap fancybox hidden" title="'.$alt.'" href="'.$src[0].'" '.$fancy_gallery.'></a>';
                        endforeach;
                    }

                    $output .= '<div class="project-name">';

                    if ($portfolio_layout == "list-portfolio") {
                        $output .= '
								<div class="container">
									<div class="row">
										<div class="col-md-12">';
                    }

                    $output .= '<div class="va">';

                    if ($link_portfolio_item==true) {
                        $output .= '<div class="project-title">
													<h3>'. get_the_title() .'</h3>
													<h4>'. $on_attributes .'</h4>
												</div>';
                    } else {
                        $output .= '<a class="project-title" href="'. get_permalink($post_id) .'" title="'. get_the_title() .'">
													<h3>'. get_the_title() .'</h3>
													<h4>'. $on_attributes .'</h4>
												</a>';
                    }

                    $output .= '</div>';

                    if ($portfolio_layout == "list-portfolio") {
                        $output .= '
										</div>
									</div>
								</div>';
                    }

                    $output .= '</div>
							</div>';
                }

            } else {

                $output .= '<div class="hover-wrap">';

                if ($portfolio_layout == "list-portfolio") {
                    $output .= '<div class="bg" style="background: url('.$img_url[0].') center center no-repeat; background-size: cover;"></div>';
                }
                else {

                    if ($portfolio_layout == "masonry-portfolio") {
                        $output .= '<img src="'. $img_url[0] .'" width="'.$img_url[1].'" height="'.$img_url[2].'" alt="'. get_the_title() .'" class="img-responsive" />';
                    } else {
                        if ($portfolio_wall==true) {
                            $output .= '<img src="'. $img_wall_thumb[0] .'" width="'.$img_wall_thumb[1].'" height="'.$img_wall_thumb[2].'" alt="'. get_the_title() .'" class="img-responsive" />';
                        } else {
                            $output .= '<img src="'. $img_thumb[0] .'" width="'.$img_thumb[1].'" height="'.$img_thumb[2].'" alt="'. get_the_title() .'" class="img-responsive" />';
                        }
                    }

                }

                $output .= '<div class="project-name">';

                if ($portfolio_layout == "list-portfolio") {
                    $output .= '
								<div class="container">
									<div class="row">
										<div class="col-md-12">';
                }

                $checked = get_post_meta(get_the_ID(), 'event-area', true);
                if($checked['Club']) {
                    $output .= '<div class="area-text">Club</div>';
                    $output .= '<div class="va">';
                    $output .= '<a href="'. get_permalink($post_id) .'" title="'. get_the_title() .'" class="cube-animation fade_in animate">
						                            <div class="cube">
						                                <div class="flippety"><i class="font-icon-info"></i></div>
						                                <div class="flop"><i class="font-icon-info"></i></div>
						                            </div>
						                        </a>';
                    $output .= '</div>';
                }else{
                }

                if($checked['Garden']) {
                    $output .= '<div class="area-text">Garden</div>';
                    $output .= '<div class="va">';
                    $output .= '<a href="'. get_permalink($post_id) .'" title="'. get_the_title() .'" class="cube-animation fade_in animate">
						                            <div class="cube">
						                                <div class="flippety" style=background-color:#007A29;"><i class="font-icon-info"></i></div>
						                                <div class="flop"><i class="font-icon-info"></i></div>
						                            </div>
						                        </a>';
                    $output .= '</div>';
                }else{
                }

                if($checked['Live Room']) {
                    $output .= '<div class="area-text">Live Room</div>';
                    $output .= '<div class="va">';
                    $output .= '<a href="'. get_permalink($post_id) .'" title="'. get_the_title() .'" class="cube-animation fade_in animate">
						                            <div class="cube">
						                                <div class="flippety" style=background-color:#FFFF94;"><i class="font-icon-info"></i></div>
						                                <div class="flop"><i class="font-icon-info"></i></div>
						                            </div>
						                        </a>';
                    $output .= '</div>';
                }else{
                }

                if ($portfolio_layout == "list-portfolio") {
                    $output .= '
										</div>
									</div>
								</div>';
                }

                $output .= '</div>
							</div>';

            }


            $output .= '</div>';

            $output .= '<div class="jamm-info">';

            $output .= '<a href="'. get_permalink($post_id) .'" title="'. get_the_title() .'">';

            $eventdate = date('l j F Y', strtotime(get_post_meta(get_the_ID(), 'event-date', true)));
            if( ! empty( $eventdate ) ) {
                $output .= '<div class="jamm-date">'. $eventdate .'</div>';
            }

            $eventtime = nl2br(get_post_meta(get_the_ID(), 'event-time', true));
            if( ! empty( $eventtime ) ) {
                $output .= '<div class="jamm-date">'. $eventtime .'</div>';
            }

            $headliner = nl2br(get_post_meta(get_the_ID(), 'headliner', true));
            if( ! empty( $headliner ) ) {
                $output .= '<div class="jamm-headliner"><h3>'. $headliner .'</h3></div>';
            }

            $support = nl2br(get_post_meta(get_the_ID(), 'support', true));
            if( ! empty( $support ) ) {
                $output .= '<div class="jamm-support">'. $support .'</div>';
            }

            $output .= '</a>';

            $output .= '<div class="event-icons">';

            $ticket = nl2br(get_post_meta(get_the_ID(), 'ticket-link', true));
            if( ! empty( $ticket ) ) {
                $output .= '<a class="button-main button-mini" target="_blank" href="'. $ticket .'"><i class="font-icon-cart"></i></a>';
            }

            $info = nl2br(get_post_meta(get_the_ID(), 'info-link', true));
            if( ! empty( $info ) ) {
                $output .= '<a class="button-main button-mini" href="'. get_permalink($post_id) .'"><i class="font-icon-info"></i></a>';
            }

            $output .= '</div>
					</div>
					</li>';

        endwhile;

        wp_reset_query();

        $output .= '</ul>';
        $output .= '</div>';
        $output .= '</div>';

        if ($portfolio_pagination == "yes") {
            $output .= '<div class="portfolio-pagination-wrap">';
            $output .= '<div class="row">';
            $output .= '<div class="col-md-12">';
            $output .= pagenavi($my_query);
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
        }
        return $output . $this->endBlockComment('az_portfolio_grid') . "\n";
    }
}

?>