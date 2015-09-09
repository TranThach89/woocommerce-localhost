<?php
/**
 * Plugin Name: Pickup & Delivery Support
 * Created by PhpStorm.
 * User: S
 * Date: 2/2/2015
 * Time: 10:41 AM
 */

class thach_test {
    function __construct(){
        add_action('admin_menu', array($this,'izweb_admin_menu'),50);

    }

    function izweb_admin_menu(){

        add_menu_page( 'Order Delivery Date','Order Delivery Date','administrator', 'order_delivery_date',array($this,'izweb_menu_pickup_delivery'));

    }

    function izweb_menu_pickup_delivery(){
        $prefix = is_network_admin() ? 'network_admin_' : 'hi';

        echo $prefix;
    }
}
new thach_test();