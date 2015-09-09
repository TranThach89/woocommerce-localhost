<?php
/**
 * Created by PhpStorm.
 * User: nhiha60591
 * Date: 24/09/2014
 * Time: 15:27
 */

if ( ! class_exists( 'IZW_Promo_Shipping' ) ) {
    class IZW_Promo_Shipping extends WC_Shipping_Method {
        /**
         * Constructor for promolizers shipping class
         *
         * @access public
         * @return void
         */
        public function __construct() {
            $this->id                 = 'izw_promo_shipping';
            $this->method_title       = __( 'Promo Shipping', 'woocommerce' );
            $this->title 		      = $this->get_option( 'title' ) ? $this->get_option( 'title' ) : 'Promo Shipping';
            $this->enabled            = "yes"; // This can be added as an setting but for this example its forced enabled
            $this->init();
        }

        /**
         * Init promo settings
         *
         * @access public
         * @return void
         */
        function init() {
            // Load the settings API
            $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
            $this->init_settings(); // This is part of the settings API. Loads settings you previously init.

            // Save settings in admin if you have any defined
            add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
        }
        function init_form_fields() {

            $this->form_fields = array(
                'enabled' => array(
                    'title' 		=> __( 'Enable/Disable', 'woocommerce' ),
                    'type' 			=> 'checkbox',
                    'label' 		=> __( 'Enable Promo Shipping', 'woocommerce' ),
                    'default' 		=> 'yes'
                ),
                'title' => array(
                    'title' 		=> __( 'Method Title', 'woocommerce' ),
                    'type' 			=> 'text',
                    'description' 	=> __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
                    'default'		=> __( 'Promo Shipping', 'woocommerce' ),
                    'desc_tip'		=> true,
                ),
            );
        }

        /**
         * calculate_shipping function.
         *
         * @access public
         * @param mixed $package
         * @return void
         */
        public function calculate_shipping( $package ) {
            $shipping = 0;
            foreach(WC()->cart->get_cart() as $rows){
                $shipping += (float)$rows['izw_product_shipping'];
            }
            if( $shipping > 0 ){

                $rate = array(
                    'id'       => $this->id,
                    'label'    => "Promo Shipping",
                    'cost'     => $shipping,
                    'calc_tax' => 'per_item'
                );

                $this->add_rate( $rate );
            }
        }
    }
}