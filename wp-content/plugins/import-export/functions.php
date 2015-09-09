<?php
/**
 * Created by PhpStorm.
 * User: S
 * Date: 1/23/2015
 * Time: 11:32 AM
 */

/*
 * check $_POST
 */
if(! function_exists('check_info_post')):
    function check_info_post($key, $value){
        if(isset($_POST[$key]) && $_POST[$key] = $value){
            return true;
        }else{
            return false;
        }
    }
endif;

/*
 * gets info of product
 */
if(! function_exists("get_info_forduct")):
    function get_info_forduct($product_ID, $key){
        $product = new WC_Product($product_ID);
        switch ($key){

            case "sku":$return = $product->get_sku();break;

            case "stock_quantity":$return = $product->get_stock_quantity();break;

        }

    return $return;

    }
endif;

/*
 * get value of string
 * return value
 * ex: "Stock";28148;288;10;
 */
function get_value_position($value,$position){
    $return = explode(";", $value);
    return $return[$position - 1];
}

//add weekly
add_filter( 'cron_schedules', 'cron_add_weekly' );

function cron_add_weekly( $schedules ) {
    // Adds once weekly to the existing schedules.
    $schedules['weekly'] = array(
        'interval'      => 300,
        'display'       => __( 'Weekly' ),
    );
    return $schedules;
}


//hook cron job
add_action( 'cron_job_import','cron_job_import_func' );
add_action( 'cron_job_export','cron_job_export_func' );

function cron_job_import_func() {

    $update_order = new update_order();

    $update_order->update_order_import_export_fun();

    $update_product = new update_product();

    $update_product->update_product_import_export_fun();

}

function cron_job_export_func() {

    $export_order = new export_order();

    $export_order->export_order_import_export_fun();

    $export_product = new export_product();

    $export_product->export_product_import_export_fun();

}