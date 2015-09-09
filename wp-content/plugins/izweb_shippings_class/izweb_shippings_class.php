<?php
/**
 * Plugin Name: Izweb Shippings Class
 * Plugin URI:
 * Description: support product Restrict shipping based on location per item & set a product as unavailable to order online
 * Author:      S
 * Author URI:
 * Version:     0.1
 * Domain Path: /languages/
 * License:     GPL
 */
define( '__IZSHCLDOMAIN__', 'izwpromolizers' );
define( '__IZSHCLPATH__', plugin_dir_path( __FILE__ ) );
define( '__IZSHCLURL__', plugin_dir_url( __FILE__ ) );
define( '__IZSHCLVERSION', '1.0.0' );

if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    exit( __( "Please Install and Active plugin Woocommerce to use this plugin "));

}

if ( ! class_exists( 'IZ_SHCL' ) ) :
    class IZ_SHCL{

        /**
         * Instance of this class.
         *
         * @since    0.1.0
         * @var      object
         */
        protected static $instance = null;

        function __construct(){

            //require_once( WP_PRICEFILES_PLUGIN_PATH . 'includes/product.php' );

            add_action("woocommerce_after_add_to_cart_form", array( $this, "izw_woocommerce_after_add_to_cart_form"),10);

            add_action( 'wp_footer', array( $this, 'izw_add_query_ajax'),10);

            add_action( 'wp_ajax_change_quantity_single', array( $this, 'izw_change_quantity_single') );

            add_action( 'wp_ajax_nopriv_change_quantity_single', array( $this, 'izw_change_quantity_single') );

        }

        public static function get_instance()
        {
            // If the single instance hasn't been set, set it now.
            if (null == self::$instance)
            {
                self::$instance = new self;
            }

            return self::$instance;
        }

        /*
         * ajax load
         */
        function izw_add_query_ajax(){
            global $product;
            echo $product->product_type;//composite     composite_data

            if( is_product() && ($product->product_type != "composite") ) {
                ?>

                <script type="text/javascript">

                    jQuery(document).ready(function () {
                        jQuery(".single-product .input-text.qty").change(get_shipping_cost);
                        get_shipping_cost();
                    });

                    function get_shipping_cost() {
                        var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
                        $quantity = jQuery(".single-product .input-text.qty").val();

                        var data = {
                            'action': 'change_quantity_single',
                            'quantity': $quantity,
                            'product_id': <?php echo $product->id;?>
                        };

                        jQuery(".freigt-price").html("<img src='<?php echo __IZSHCLURL__ . 'assets/images/ajax-loader.gif'?>' />");

                        jQuery.post(ajaxurl, data, function (response) {

                            jQuery(".freigt-price").html(response);

                        });
                    }

                </script> <?php
            }else{  ?>
                <script type="text/javascript">

                    jQuery(document).ready(function () {
                        jQuery(".single-product .composite_button .input-text.qty").change(get_shipping_cost_com);
                        get_shipping_cost_com();
                    });

                    function get_shipping_cost_com() {
                        var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
                        $quantity = jQuery(".single-product .composite_button .input-text.qty").val();

                        var data = {
                            'action': 'change_quantity_single',
                            'quantity': $quantity,
                            'product_id': <?php echo $product->id;?>
                        };

                        jQuery(".freigt-price").html("<img src='<?php echo __IZSHCLURL__ . 'assets/images/ajax-loader.gif'?>' />");

                        jQuery.post(ajaxurl, data, function (response) {

                            jQuery(".freigt-price").html(response);

                        });
                    }

                </script>
            <?php
            }
        }

        /*
         * function change shipping page single_product
         */
        function izw_change_quantity_single(){

            $quantity = intval( $_POST['quantity'] );
            $product_id = intval( $_POST['product_id'] );

            //require_once( ABSPATH . "wp-content/plugins/woocommerce-pricefiles/includes/product.php" );

            //$productfiles = new WC_Pricefiles_Product($product_id);

            echo "+ " . $this->get_shipping_cost($quantity, $product_id) . " " . get_woocommerce_currency_symbol();

            die();
        }

        /*
         * add class test
         */
        function izw_woocommerce_after_add_to_cart_form(){

            echo "<div class='freigt-price'></div>";

        }

        /*
         *
         */
        public function get_shipping_cost($quantity, $product_id)
        {
            // Packages array for storing package/cart object
            $packages = array();
            $product = get_product( $product_id );

            $price = $product->get_price_excluding_tax($quantity);
            $price_tax = $product->get_price_including_tax($quantity) - $price;

            // Build up a fake package object
            $cart = array(
                'product_id' => $product->id,
                'variation_id' => '',
                'variation' => '',
                'quantity' => $quantity,
                'data' => $product,
                'line_total' => $price,
                'line_tax' => $price_tax,
                'line_subtotal' => $price,
                'line_subtotal_tax' => $price_tax,
            );

            // Items in the package
            $packages[0]['contents'][md5('wc_pricefiles_' . $product->id . $price)] = $cart;
            // Cost of items in the package, set below
            $packages[0]['contents_cost'] = $price;
            // Applied coupons - some, like free shipping, affect costs
            $packages[0]['applied_coupons'] = '';
            // Fake destination address. Needed for calculation the shipping
            $packages[0]['destination'] = WC_Pricefiles()->get_shipping_destination();

            // Apply filters to mimic normal behaviour
            $packages = apply_filters('woocommerce_cart_shipping_packages', $packages);


            $package = $packages[0];

            // Calculate the shipping using our fake package object
            $shipping_method_rates = WC()->shipping->calculate_shipping_for_package($package);

            //$shipping_methods = WC_Pricefiles()->get_shipping_methods();

            //$options = get_option(WC_PRICEFILES_PLUGIN_SLUG . '_options', array());

            //$shipping_methods = $options['shipping_methods'];

            $shipping_methods = array('table_rate');

            $lowest_shipping_cost = 0;

            if (!empty($shipping_methods))
            {
                //$shipping_methods = array_intersect_key($shipping_method_rates['rates'], $this->shipping_methods);

                foreach ($shipping_method_rates['rates'] AS $rate)
                {

                    if (in_array($rate->method_id, $shipping_methods))
                    {
                        $total_tax = 0;

                        if( WC_Pricefiles()->get_price_type() == 'incl' )
                        {
                            //Sum the taxes
                            foreach($rate->taxes AS $tax)
                            {
                                $total_tax += $tax;
                            }

                            //Calc shipping cost including tax
                            $total_cost = $rate->cost + $total_tax;
                        }
                        else
                        {
                            $total_cost = $rate->cost;
                        }

                        if (empty($lowest_shipping_cost) || $total_cost < $lowest_shipping_cost)
                        {
                            $lowest_shipping_cost = $total_cost;
                        }
                    }
                }
            }

            return $lowest_shipping_cost;
        }

    }
endif;//END Class IZ_SHCL

add_action( 'plugins_loaded', 'IZ_SHCL' );
function IZ_SHCL()
{
    return IZ_SHCL::get_instance();
}