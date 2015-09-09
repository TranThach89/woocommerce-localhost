<?php
/**
 * Plugin Name: Support Woocommerce Products Customizer
 * Plugin URI:
 * Description: support product Restrict shipping based on location per item & set a product as unavailable to order online
 * Author:      S
 * Author URI:
 * Version:     0.1
 * Domain Path: /languages/
 * License:     GPL
 */

define( '__SUWPCPATH__', plugin_dir_path( __FILE__ ) );
define( '__SUWPCURL__', plugin_dir_url( __FILE__ ) );
define( '__SUWPCVERSION', '1.0.0' );

if ( ! class_exists( 'SUWPC' ) ) :
    class SUWPC
    {
        function __construct()
        {
            add_action( 'init', array( $this, 'suwpc_init' ) );
        }

        function suwpc_init()
        {
            remove_action( 'woocommerce_after_add_to_cart_button', 'get_customize_btn');

            add_action( 'woocommerce_after_add_to_cart_button', array( $this , 'get_customize_btn' ));
        }

        function get_customize_btn(){
            $post_id=get_the_ID();
            $product=  get_product($post_id);
            $is_customizable=  get_post_meta($post_id,"customizable-product",true);
            $templates_page = get_post_meta($post_id, 'wpc-templates-page', true);
            $design_from_blank = get_post_meta($post_id, 'wpc-design-from-blank', true);
            $upload_design = get_post_meta($post_id, 'wpc-upload-design', true);
            $templates_page_url="";
            if($templates_page)
                $templates_page_url=  get_permalink($templates_page);
            if($is_customizable)
            {
                if($design_from_blank)
                {
                    $test_dfb = 'PERSONALIZE THIS ITEM';
                    ?><input type="button" value="<?php _e( apply_filters( 'name_design_from_blank', $test_dfb),"wpc");?>" data-id="<?php echo $post_id;?>" data-type="<?php echo $product->product_type;?>" class="mg-top-10 wpc-customize-product"/><?php
                }

                if($templates_page)
                {
                    $test_tp = 'Browse our templates';
                    ?><a href="<?php echo $templates_page_url; ?>" class='btn-choose tpl'> <?php _e( apply_filters( 'name_templates_page', $test_tp ),"wpc");?></a><?php
                }

                if($upload_design)
                {
                    $test_ud = 'Browse our templates';
                    ?><button data-id="<?php echo $post_id;?>" data-type="<?php echo $product->product_type;?>" class="mg-top-10 wpc-upload-product-design"><?php _e( apply_filters( 'name_upload_design', $test_ud ),"wpc");?></button><?php
                }
            }
        }
    }
endif;
new SUWPC();