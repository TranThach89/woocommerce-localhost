<?php
/**
 * Plugin Name: TEG Extended Shipping Restrictions for WooCommerce
 * Plugin URI:
 * Description: Adds restrictions for shipping based on location per item & adds the ability to make a product "Call for Pricing" only.
 * Author:      S
 * Author URI:
 * Version:     1.0.0
 * Domain Path: /languages/
 * License:     GPL
 */

define( '__IZSPDOMAIN__', 'izwpromolizers' );
define( '__IZSPPATH__', plugin_dir_path( __FILE__ ) );
define( '__IZSPURL__', plugin_dir_url( __FILE__ ) );
define( '__IZSPVERSION', '1.0.0' );

if ( ! defined( 'ABSPATH' ) ) {

    exit; // Exit if accessed directly

}

if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    
    exit( __( "Please Install and Active plugin Woocommerce to use this plugin "));
    
}


class IZ_SUPR{
    function __construct(){
        add_action( 'init', array( $this, 'init') );
        add_action( 'admin_enqueue_scripts', array( $this, 'izw_admin_enqueue_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'izw_wp_enqueue_scripts' ) );

        //product online
        add_action( 'woocommerce_simple_add_to_cart', array( $this,'izw_woocommerce_simple_add_to_cart'), 10);
        add_filter( "woocommerce_loop_add_to_cart_link",  array( $this, "izw_woocommerce_loop_add_to_cart_link"),10,2);

        //product by country
        add_action( 'woocommerce_after_checkout_validation', array( $this,'izw_woocommerce_after_checkout_validation'), 10, 1);
        add_filter( "woocommerce_checkout_cart_item_quantity",  array( $this, "izw_woocommerce_checkout_cart_item_quantity"), 10, 3);
    }

    /**
     * Includes File
     */
    function init(){
        //include_once (__IZSPPATH__."/admin/product/custom-product-data.php");
        include_once (__IZSPPATH__."/admin/add-product-data.php");
        include_once (__IZSPPATH__."/admin/includes/class_country.php");
        include_once (__IZSPPATH__."/admin/includes/class_functions.php");
        include_once (__IZSPPATH__."/admin/includes/class_country_shipping.php");
        remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
    }

    /**
     * Register Script For Front-End
     */
    function izw_wp_enqueue_scripts(){

    }

    /**
     * Register Script On Back-End
     */
    function izw_admin_enqueue_scripts(){
        wp_enqueue_script( 'jquery' );
        wp_register_style( 'style-supr', __IZSPURL__ . 'admin/css/style.css' );
        wp_enqueue_style( 'style-supr' );
    }


    /**
     * Deactivation Action
     */
    function deactivation(){

    }

    /*
     * check product is online
     */
    function izw_woocommerce_simple_add_to_cart(){
        global $post;
        $data = array(

            '_izw_unavailable'          => '',

            'izw_number'                => '',

        );

        foreach($data as $key=>$values){
            if(empty($values)){
                $data[$key] = get_post_meta( $post->ID, $key, true );
            }
        }

        if(isset($data['_izw_unavailable']) && ($data['_izw_unavailable'] == "yes")){  ?>
            <?php
//            add_filter("woocommerce_get_price",function($price){
//                $price = "N/A";
//                return $price;
//            });
//            WC_Product::set_price( "N/A" );
//            $product = new WC_Product( get_the_ID() );
//            $price = $product->price;
//            echo $price;
            ?>
            <form class="cart">

                <button type='button' id='call_pricing' class='single_add_to_cart_button button alt call_for_pricing'>CALL FOR PRICING</button>

            </form>

        <script type="text/javascript">
            jQuery(document).ready(function($){
                $("#call_pricing").click(function(){

                    $("#call_pricing").text("<?php echo $data['izw_number'];?>");

                });
            });
        </script>

        <?php

        }else{

            wc_get_template( 'single-product/add-to-cart/simple.php' );

        }
    }

    /*
     * check product online on shop
     */
    function izw_woocommerce_loop_add_to_cart_link($text,$product){

        $data = array(

            '_izw_unavailable'          => '',

            'izw_number'                => '',

        );

        foreach($data as $key=>$values){
            if(empty($values)){
                $data[$key] = get_post_meta( $product->id, $key, true );
            }
        }

        if(isset($data['_izw_unavailable']) && ($data['_izw_unavailable'] == "yes")){

            $text = "<button type='button' id='call_pricing_" . $product->id . "' class='button add_to_cart_button product_type_simple call_for_pricing'>CALL FOR PRICING</button>

            <script type='text/javascript'>
                jQuery(document).ready(function($){
                    $('#call_pricing_" . $product->id . "').click(function(){

                        $('#call_pricing_" . $product->id ."').text('" . $data['izw_number'] . "');

                    });
                });
            </script>";

            return $text;

        }else{

            return $text;

        }

    }


    /*
     * add infomation after quantity in review-order
     */
    function izw_woocommerce_checkout_cart_item_quantity($text, $cart_item, $cart_item_key){

        if(defined('WOOCOMMERCE_CHECKOUT')):

            //get country of page checkout(country of shipping)
            $country = $_POST['s_country'];

            //get product id
            $product_id = $cart_item['product_id'];

            //key option
            $key_countries = 'izw_countries_woocommerce_' . $product_id;

            //get country of product
            $country_product = get_post_meta($product_id, $key_countries, true);

            //get type country
            $izw_country_type = get_post_meta($product_id, 'izw_country_type', true);

            if(isset($izw_country_type) && !empty($izw_country_type)):

                $izw_error = get_post_meta($product_id, 'izw_error', true);

                switch ($izw_country_type){
                    case "none":
                        break;

                    case "allow":

                        if( empty($country_product) ){
                            foreach(WC()->countries->countries as $key=>$val)
                                $country_product[] = $key;
                        }

                        //var_dump($country_product);
                        if(! in_array($country,$country_product)){

                            $return = $text . "<br />";

                            $return .= '<div style="color:red">

                            <a class="remove" href=' . esc_url( WC()->cart->get_remove_url( $cart_item_key ) ) . '></a>

                            <div class="text_remove" style="width:85%;float:right;"><span style="font-style:italic;font-size:13px;">' . $izw_error . '</span><br />

                            <a style="color:red;text-decoration:underline" href=' . esc_url( WC()->cart->get_remove_url( $cart_item_key ) ) . '>CLICK HERE TO REMOVE THIS ITEM</a>

                            </div>

                        </div>';

                            return $return;
                        }

                        break;

                    case "restrict":

                        if( ! empty($country_product) && in_array($country,$country_product)){

                            $return = $text . "<br />";

                            $return .= '<div style="color:red">

                            <a class="remove" href=' . esc_url( WC()->cart->get_remove_url( $cart_item_key ) ) . '></a>

                            <div class="text_remove" style="width:85%;float:right;"><span style="font-style:italic;font-size:13px;">' . $izw_error . '</span><br />

                            <a style="color:red;text-decoration:underline" href=' . esc_url( WC()->cart->get_remove_url( $cart_item_key ) ) . '>CLICK HERE TO REMOVE THIS ITEM</a>

                            </div>

                        </div>';

                            return $return;
                        }

                        break;

                    default:
                }

            endif;

            return $text;

        endif;
    }

    /*
     * check product country
     */
    function izw_woocommerce_after_checkout_validation($posted){
        //get country in checkout
        //$country = WC()->customer->get_shipping_country();
        if(isset($_POST['ship_to_different_address']) && $_POST['ship_to_different_address'] == 1){

            $country = $_POST['shipping_country'];

        }else{

            $country = $_POST['billing_country'];

        }

        if ( (sizeof( WC()->cart->get_cart() ) > 0) && !empty($country) ) {

            foreach (WC()->cart->get_cart() as $cart_key => $cart_item) {

                //get product id
                $product_id = $cart_item['product_id'];

                //key option
                $key_countries = 'izw_countries_woocommerce_' . $product_id;

                //get country of product
                $country_product = get_post_meta($product_id, $key_countries, true);

                //get type country
                $izw_country_type = get_post_meta($product_id, 'izw_country_type', true);

                if(isset($izw_country_type) && !empty($izw_country_type)):

                    switch ($izw_country_type){
                        case "none":
                            break;

                        case "allow":

                            //change type array save
                            if( empty($country_product) ){
                                foreach(WC()->countries->countries as $key=>$val)
                                    $country_product[] = $key;
                            }

                            if(! in_array($country,$country_product)){

                                throw new Exception("Please Remove Product \"" . get_the_title($product_id) . "\" Or Change Shipping Country.");

                            }

                            break;

                        case "restrict":

                            if( ! empty($country_product) && in_array($country,$country_product)){

                                throw new Exception("Please Remove Product \"" . get_the_title($product_id) . "\" Or Change Shipping Country.");

                            }

                            break;

                        default:
                    }

                endif;//END defined('WOOCOMMERCE_CHECKOUT')

            }
        }
    }

}
$GLOBALS['izwpromolizers'] = new IZ_SUPR();