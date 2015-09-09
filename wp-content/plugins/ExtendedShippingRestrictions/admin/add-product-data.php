<?php
/**
 * Created by PhpStorm.
 * User: S
 * Date: 3/14/2015
 * Time: 3:12 PM
 */

class IZW_Add_Product_Data{
    function __construct(){
        //tab Restrictions
        add_filter( 'woocommerce_product_data_tabs', array( $this, 'izw_woocommerce_product_data_tabs'), 10,1 );
        add_action( 'woocommerce_product_data_panels', array( $this, 'izw_woocommerce_product_data_panels') );
        //add_action( 'admin_init', array( $this, 'add_available_shipping_locations' ));

        //tab Shipping
        //dowload
        add_filter( 'product_type_options', array($this, "izw_product_type_options"), 10,1);
        add_action( 'woocommerce_product_options_shipping', array($this, 'izw_woocommerce_product_options_shipping'));
        add_action( 'woocommerce_product_options_dimensions', array( $this, 'izw_woocommerce_product_options_dimensions') );

        //save
        add_action( 'woocommerce_process_product_meta', array( $this, 'izw_woocommerce_process_product_meta' ),10,2 );
        add_filter( 'woocommerce_get_price_html', array( $this, "izw_woocommerce_get_price_html"),10 ,2);
    }

    /*
     * add Restrictions Tab
     */
    function izw_woocommerce_product_data_tabs($tabData){
        $tabData['izw_custom'] = array(
            'label'  => __( 'Restrictions', 'woocommerce' ),
            'target' => 'custom_product_data',
            'class'  => array('show_if_simple'),
        );
        return $tabData;
    }

    /*
     * tab Restrictions
     */
    function izw_woocommerce_product_data_panels(){
        global $post;

        $post_id = $post->ID;

        $key_countries = 'izw_countries_woocommerce_' . $post_id;

        $data = IZW_Functions::get_value_post_meta();

        if( empty($data['izw_country_type']) ) $data['izw_country_type'] = "restrict";  ?>

        <div id="custom_product_data" class="panel woocommerce_options_panel">

            <div class="options_group izwsupr">
                <p>
                <select name="izw_country_type">
<!--                    <option value="none" --><?php //selected( $data['izw_country_type'],'none'); ?><!-->&nbsp;</option>-->
                    <option value="allow" <?php selected( $data['izw_country_type'],'allow'); ?>>Allow Only</option>
                    <option value="restrict" <?php selected( $data['izw_country_type'],'restrict'); ?>>Restrict only</option>
                </select>
                <span style="padding-left:10px;font-weight:900;font-size: 14px;">the following locations from purchasing this product:</span>
                </p>

                <p class="country">
                    <?php
                    $settings = array(
                        array(
                            //'title'   => __( 'Specific Countries', 'woocommerce' ),
                            'desc'    => '',
                            'id'      => $key_countries,
                            'css'     => '',
                            'default' => '',
                            'type'    => 'multi_select_countries',
                        )
                    );

                    WC_Admin_Settings::output_fields($settings);
                    ?>

                </p>

                <p class="form-field">
                    <label>Message if Restricted</label>
                    <input style="width:100%" type="text" placeholder="" value="<?php echo $data['izw_error'];?>" name="izw_error"/>
                </p>

            </div>

        </div>

    <?php
    }


    /*
     * add Call for Pricing
     */
    function izw_product_type_options($array){
        $array['izw_unavailable'] = array(
            'id'            => 'izw_unavailable',
            'wrapper_class' => 'show_if_simple',
            'label'         => __( 'Call for Pricing', 'woocommerce' ),
            'description'   => __( 'Call for Pricing products cannot be added to the cart and will provide a phone number to order with. This will override the Virtual or Downloadable options.', 'woocommerce' ),
            'default'       => 'no'
        );

        return $array;
    }

    /*
     * add Phone Number on tab shipping
     */
    function izw_woocommerce_product_options_shipping(){

        global $post;

        $value = get_post_meta($post->ID, 'izw_number', true);

        if(empty($value)) $value = "";      ?>

        <p class="form-field field izw_number">

            <label for="product_shipping_class"><?php _e( 'Phone Number', 'woocommerce' ); ?></label>

            <input type="text" style="width:auto;" name="izw_number" id="izw_number" value="<?php echo $value; ?>" >

            <!-- jQuery     -->
            <script type="text/javascript">

                jQuery(document).ready(function ($){

                    if(! $( "#izw_unavailable" ).is(':checked')){
                        $(".izw_number").css("display","none");
                    }

                    $("#izw_unavailable").click(function(){

                        if( $( this ).is(':checked')){

                            $(".izw_number").css("display","block");

                        }else $(".izw_number").css("display","none");

                    });

                });

            </script>

    <?php
    }

    /*
     * tab shipping
     */
    function izw_woocommerce_product_options_dimensions(){
        global $post;
        $post_id = $post->ID;

        $key_countries = 'izw_countries_woocommerce_' . $post_id;

        $data = IZW_Functions::get_value_post_meta();

        $check_error = true;

        //get country
        $array_country = IZW_Country::get_array_country();?>

        <?php if(empty($array_country)){    ?>

            <script type="text/javascript">
                jQuery(document).ready(function ($) {

                    $("#shipping_product_data .form-field.disnone").css("display", "none");

                });
            </script>

            <?php

        }else{ ?>

        <p class="_available">

            <span>Available Shipping Locations</span><br/>

            <select id="izw_available" name="izw_available" class="select short wc-enhanced-select">   <?php

                foreach ($array_country as $key => $values) {

                    if ($data['izw_available'] == $key) {

                        $check_error = false;

                    } ?>

                    <option
                        value='<?php echo $key;?>' <?php selected($data['izw_available'], $key); ?> ><?php echo $values;?></option>

                <?php
                }
                ?>

            </select>

            <?php IZW_Functions::add_tooltip('Shipping classes are used by certain shipping methods to group similar products.'); ?>

            <?php //IZW_Functions::check_choise_country($check_error); ?>

        </p>
        <?php
        foreach ($array_country as $key => $values) {

            if (!empty($data['izw_available_shipping'])) {
                foreach ($data['izw_available_shipping'] as $ke => $val) {
                    if ($ke == 'product_shipping_class_' . $key)
                        $current_shipping_class = $val;
                }
            } else $current_shipping_class = -1;

            $args = array(
                'taxonomy' => 'product_shipping_class',
                'hide_empty' => 0,
                'show_option_none' => __('No shipping class', 'woocommerce'),
                'name' => 'product_shipping_class_' . $key,
                'id' => 'product_shipping_class_' . $key,
                'selected' => $current_shipping_class,
                'class' => 'select short'
            );
            ?>

            <p class="form-field disnone <?php echo $key;?>">

                <label for="product_shipping_class"><?php _e('Shipping class', 'woocommerce'); ?></label>

                <?php wp_dropdown_categories($args); ?>

                <?php IZW_Functions::add_tooltip('Shipping classes are used by certain shipping methods to group similar products.');?>
            </p>

        <?php
        }
        ?>

        <script type="text/javascript">
            jQuery(document).ready(function ($) {

                $("#shipping_product_data .options_group:nth-child(2) p.form-field.dimensions_field").css("display", "none");

                $("#shipping_product_data .form-field.disnone").css("display", "none");

                $value = $("#shipping_product_data #izw_available").val();

                $("#shipping_product_data .disnone.form-field." + $value).css("display", "block");

                $("#shipping_product_data #izw_available").change(function () {

                    $value = $(this).val();

                    $(".form-field.disnone").css("display", "none");

                    $("#shipping_product_data .disnone.form-field." + $value).css("display", "block");
                });
            });
        </script>
        <?php
        }
    }

    /**
     * Save data
     *
     * @param $post_id
     * @param $post
     */
    function izw_woocommerce_process_product_meta( $post_id, $post ){

        $key_countries = 'izw_countries_woocommerce_' . $post_id;

        $data = array(
            '_izw_unavailable'               => !empty( $_POST['izw_unavailable'] ) ? 'yes' : 'no',
            'izw_number'                     => !empty( $_POST['izw_number'] ) ? $_POST['izw_number'] : '716-662-0536',
            'izw_country_type'               => !empty( $_POST['izw_country_type'] ) ? $_POST['izw_country_type'] : 'restrict',
            $key_countries                   => !empty( $_POST[$key_countries] ) ? $_POST[$key_countries] : '',
            'izw_error'                      => !empty( $_POST['izw_error'] ) ? $_POST['izw_error'] : 'The item is not availabkle to ship to your location, please call 716-662-0536 for more information and pricing.',
            'izw_available'                  => !empty( $_POST['izw_available'] ) ? $_POST['izw_available'] : '',
        );

        foreach($data as $key=>$value):

            update_post_meta( $post_id, $key, $value );

        endforeach;

        /*
         * save option
         */
        if(isset($_POST[$key_countries]) && !empty($_POST[$key_countries])){

            update_option( $key_countries, $_POST[$key_countries] );

        }else update_option( $key_countries, "" );

        /*
         * save shipping class by country
         */
        $array_country = IZW_Country::get_array_country();

        if(! empty($array_country)):

            foreach($array_country as $key=>$value){

                $data_country['product_shipping_class_' . $key] = $_POST['product_shipping_class_' . $key];

            }

        endif;

        update_post_meta( $post_id, 'izw_available_shipping', $data_country);


    }

    /*
     * change price product online
     */
    function izw_woocommerce_get_price_html($price, $that){

        $data = get_post_meta($that->id, '_izw_unavailable');

        if($data[0] == "yes"){

            $price = "N/A";

        }

        return $price;
    }
}
new IZW_Add_Product_Data();