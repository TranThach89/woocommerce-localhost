<?php
/**
 * Plugin Name: redirect to checkout
 * Plugin URI: http://izweb.biz
 * Description: This plugin customized to changes url of button (add to card) on single product to checkout page
 * Version: 1.0.0
 * Author: Izweb Co.Ltd
 * Author URL: http://izweb.biz
 */
if(!defined('ABSPATH')) {
    die('You are not allowed to call this page directly.');
}

if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    exit( __( "Please Install and Active plugin Woocommerce to use this plugin "));

}

class izweb_redirect_to_checkout
{
    /*
     * construct
     */
    function __construct()
    {

        add_filter( 'add_to_cart_redirect', array($this, 'iz_redirect_to_checkout'));
        add_filter( 'woocommerce_get_cart_page_id', array($this, 'iz_woocommerce_get_cart_page_id'));
        add_filter( 'woocommerce_get_cart_page_permalink', array($this, 'iz_woocommerce_get_cart_page_permalink'));
        add_action( 'woocommerce_before_checkout_form', array($this, 'add_cart_to_checkout'), 50);
        add_filter( 'wp_redirect', array($this, 'iz_wp_redirect'), 10, 2);

    }


    /*
     * return to shop when checkout null
     */
    function iz_wp_redirect($location)
    {
        $cart_num_products = WC()->cart->cart_contents_count;
        if (is_checkout()) {
            if ($cart_num_products == 0) { //&& $location == home_url()."/cart/");

                $location = get_permalink( woocommerce_get_page_id( 'shop' ) );

            }
        }
        return $location;
    }

    /*
     * redirect link  of view cart on shop to checkout
     */
    function iz_woocommerce_get_cart_page_id(){

        if(is_shop()){
            return get_option('woocommerce_checkout_page_id' );
        }

    }

    /*
     * redirect link  of view cart on shop to checkout
     */
    function iz_woocommerce_get_cart_page_permalink()
    {
        if(is_shop()){
            return get_permalink( woocommerce_get_page_id( 'checkout' ) );
        }

    }

    /*
     * redirect link  of add_to_carton single product to checkout
     */
    function iz_redirect_to_checkout()
    {
        global $woocommerce;
        $checkout_url = $woocommerce->cart->get_checkout_url();
        return $checkout_url;
    }

    /*
     * add cart form to checkout page
     */
    function add_cart_to_checkout()
    {
        global $cart_item;
        global $woocommerce;
        ?>
        <?php do_action('woocommerce_before_cart'); ?>

        <form action="<?php echo esc_url(WC()->cart->get_checkout_url()); ?>" method="post">

            <?php do_action('woocommerce_before_cart_table'); ?>
            <div class="cart-wrapper">
                <table class="shop_table cart responsive" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="product-name" colspan="3"><?php _e('Product', 'woocommerce'); ?></th>
                        <th class="product-price"><?php _e('Price', 'woocommerce'); ?></th>
                        <th class="product-quantity"><?php _e('Quantity', 'woocommerce'); ?></th>
                        <th class="product-subtotal"><?php _e('Total', 'woocommerce'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php do_action('woocommerce_before_cart_contents'); ?>


                    <?php
                    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                        if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                            ?>
                            <tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">

                                <td class="remove-product">
                                    <?php
                                    echo apply_filters('woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s"><span class="icon-close"></span>&times;</a>', esc_url($woocommerce->cart->get_remove_url($cart_item_key)), __('Remove this item', 'woocommerce')), $cart_item_key);
                                    ?>
                                </td>

                                <td class="product-thumbnail">
                                    <?php
                                    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', str_replace(array('http:', 'https:'), '', $_product->get_image()), $cart_item, $cart_item_key);

                                    if (!$_product->is_visible())
                                        echo $thumbnail;
                                    else
                                        printf('<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail);
                                    ?>
                                </td>

                                <td class="product-name">
                                    <?php
                                    if (!$_product->is_visible())
                                        echo apply_filters('woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key);
                                    else
                                        echo apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title()), $cart_item, $cart_item_key);

                                    // Meta data
                                    echo WC()->cart->get_item_data($cart_item);

                                    // Backorder notification
                                    if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity']))
                                        echo '<p class="backorder_notification">' . __('Available on backorder', 'woocommerce') . '</p>';
                                    ?>
                                </td>

                                <td class="product-price">
                                    <?php
                                    echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                                    ?>
                                </td>

                                <td class="product-quantity">
                                    <?php
                                    if ($_product->is_sold_individually()) {
                                        $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                                    } else {
                                        $product_quantity = woocommerce_quantity_input(array(
                                            'input_name' => "cart[{$cart_item_key}][qty]",
                                            'input_value' => $cart_item['quantity'],
                                            'max_value' => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                                        ), $_product, false);
                                    }

                                    echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key);
                                    ?>
                                </td>

                                <td class="product-subtotal">
                                    <?php
                                    echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                                    ?>
                                </td>
                            </tr>
                        <?php
                        }
                    }

                    //do_action('woocommerce_cart_contents');
                    ?>
                    <tr>
                        <td colspan="6" class="actions">
                            <?php do_action('woocommerce_after_cart_contents'); ?>
                    </tbody>
                </table>
                <input type="submit" class="button expand" name="proceed"
                       value="<?php _e('Update Cart', 'woocommerce'); ?>"/>

                <?php wp_nonce_field('woocommerce-cart'); ?>

                <?php do_action('woocommerce_cart_collaterals'); ?>

            </div>

            </td>
            </tr>


            </tbody>
            </table>

            <?php do_action('woocommerce_after_cart_table'); ?>

        </form>

        <?php do_action('woocommerce_after_cart'); ?>

    <?php
    }
}
new izweb_redirect_to_checkout();