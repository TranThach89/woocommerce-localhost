<?php
/**
 * Plugin Name: Duplicate Order
 * Plugin URI: http://izwebz.com
 * Description: Add a Clone button to Order detail page
 * Version: 1.0.0
 * Author: IZWEB
 * Author URI: http://izwebz.com

 */

if ( ! defined( 'ABSPATH' ) ) {

    exit; // Exit if accessed directly

}

if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    exit( __( "Please Install and Active plugin Woocommerce to use this plugin "));

}

/*
 * class izweb_duplicate_order
 */
class izweb_duplicate_order{

    function __construct(){

        add_action("woocommerce_order_actions_end", array($this,"add_new_button"), 10, 1);

        add_action("init",array($this,"handle_clone_action"));

        if(is_admin()){
            wp_enqueue_style( 'style', plugins_url('/assets/css/style.css', __FILE__), array(), '3.0.3' );
        }
    }

    /*
     * add Button Duplicate Order
     */
    function add_new_button($post_id){
        ?>
        <li class="wide">
            <div class="woocommerce-order-actions">
                <div class="woocommerce-order-actions">

                    <?php //echo "<a class ='clone' href='".admin_url()."?action=clone_order&post_id=".$post_id."'>Duplicate Order</a>";?>

                    <a class="clone" href="<?php echo admin_url();?>?action=clone_order&post_id=<?php echo $post_id;?>">Duplicate Order</a>

                </div>
            </div>
        </li>
    <?php
    }

    /*
     * clone order
     */
    function handle_clone_action(){
        global $wpdb;
        $post_id = $_REQUEST['post_id'];

        if (is_admin()){

            if(@$_GET['action']=='clone_order') {

                //copy wp_posts     wc-on-hold
                $wpdb->query("

                    INSERT INTO `{$wpdb->prefix}posts`

					SELECT 0,post_author ,post_date ,post_date_gmt ,post_content ,post_title ,post_excerpt ,'wc-on-hold' ,comment_status ,ping_status ,post_password ,post_name ,to_ping ,pinged ,post_modified ,post_modified_gmt ,post_content_filtered ,post_parent ,guid ,menu_order ,post_type ,post_mime_type ,comment_count

					FROM `{$wpdb->prefix}posts`

					WHERE ID = $post_id");

                $new_post_id =  $wpdb->insert_id;

                //copy wp_postmeta
                $wpdb->query("INSERT INTO `{$wpdb->prefix}postmeta` SELECT 0,{$new_post_id},meta_key,meta_value FROM `{$wpdb->prefix}postmeta` WHERE post_id = $post_id");

                //get wc order item
                $items = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}woocommerce_order_items` WHERE order_id = $post_id");

                //copy wc order item & wc order item meta
                foreach ($items as $item) {

                    $wpdb->query("INSERT INTO `{$wpdb->prefix}woocommerce_order_items` SELECT 0, order_item_name, order_item_type, {$new_post_id} FROM `{$wpdb->prefix}woocommerce_order_items` WHERE  order_item_id = {$item->order_item_id}");

                    $new_item_id =  $wpdb->insert_id;

                    $wpdb->query("INSERT INTO `{$wpdb->prefix}woocommerce_order_itemmeta` SELECT 0, {$new_item_id}, meta_key, meta_value FROM `{$wpdb->prefix}woocommerce_order_itemmeta` WHERE  order_item_id = {$item->order_item_id}");
                }

                wp_redirect(admin_url() . 'post.php?post=' . $new_post_id . '&action=edit');
            }

        }
    }

}
new izweb_duplicate_order();