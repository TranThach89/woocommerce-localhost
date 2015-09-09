<?php
/**
 * Created by PhpStorm.
 * User: S
 * Date: 3/19/2015
 * Time: 10:25 PM
 */

class IZW_Functions{

    /*
     * add $test tooltip
     */
    public static function add_tooltip($test){    ?>

        <img class="help_tip" data-tip="<?php esc_attr_e( $test, 'woocommerce' ); ?>" src="<?php echo esc_url( WC()->plugin_url() ); ?>/assets/images/help.png" height="16" width="16" />

    <?php
    }

    /*
     * check error country
     */
    public static function check_choise_country($check_error){

        if($check_error){

            echo "<span style='color: red;font-weight: bold;margin-left: 10px;'>Please select from available shipping locations.</span>";

        }

    }

    /*
     * get value post meta
     */
    public static function get_value_post_meta(){

        global $post;

        $post_id = $post->ID;

        $key_countries = 'izw_countries_woocommerce_' . $post_id;

        $data = array(

            'izw_country_type'                       => '',

            'izw_error'                              => '',

            $key_countries                           => '',

            'izw_available'                          => '',

            'izw_available_shipping'                 => '',

        );

        foreach($data as $key=>$values){

            if(empty($values)){

                $data[$key] = get_post_meta( $post_id, $key, true );

            }
        }

        return $data;
    }
}