<?php
/**
 * Created by PhpStorm.
 * User: S
 * Date: 3/20/2015
 * Time: 3:39 AM
 */

class Country_Shipping{

    public $shipping_country;

    function __construct(){

        add_filter( "get_the_terms",  array( $this, "izw_wp_get_the_terms"), 10, 3);

    }

    /*
     * change shipping_class
     */
    function izw_wp_get_the_terms( $terms, $postID, $taxonomy ){

        if( ($taxonomy == 'product_shipping_class') && ( is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART')  ) ){

            $shipping_country = WC()->customer->get_shipping_country();

            $shipping_class = get_post_meta($postID, 'izw_available_shipping');

            if(! empty($shipping_class[0]) && (is_array($shipping_class[0]))):

                $return = -1;

                foreach($shipping_class[0] as $key=>$value){

                    if($key == 'product_shipping_class_' . $shipping_country) $return = $value;

                }

                $terms[0] = get_term($return, 'product_shipping_class');

                return $terms;

            endif;

            return $terms;

        }

        return $terms;
    }

}

new Country_Shipping();