<?php
/**
 * Created by PhpStorm.
 * User: S
 * Date: 3/19/2015
 * Time: 9:46 PM
 */

class IZW_Add_Product_Data{
    function __construct(){
        //Restrictions
        add_filter( 'woocommerce_product_data_tabs', array( $this, 'izw_woocommerce_product_data_tabs'), 10,1 );
        add_action( 'woocommerce_product_data_panels', array( $this, 'izw_woocommerce_product_data_panels') );
        //add_action( 'admin_init', array( $this, 'add_available_shipping_locations' ));

        //call for pricing
        add_filter( 'product_type_options', array($this, "izw_product_type_options"), 10,1);
        add_action( 'woocommerce_product_options_shipping', array($this, 'izw_woocommerce_product_options_shipping'));
        add_action( 'woocommerce_product_options_dimensions', array( $this, 'izw_woocommerce_product_options_dimensions') );

        //save
        add_action( 'woocommerce_process_product_meta', array( $this, 'izw_woocommerce_process_product_meta' ),10,2 );
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
     *
     */
    function izw_woocommerce_product_data_panels(){
        global $post;

        $post_id = $post->ID;
        $key_countries = 'izw_countries_woocommerce_' . $post_id;
        $data = array(

            'izw_country_type'                       => '',

            'izw_error'                              => '',

            $key_countries                           => '',

            //'izw_available'                          => '',

        );

        foreach($data as $key=>$values){
            if(empty($values)){
                $data[$key] = get_post_meta( $post_id, $key, true );
            }
        }   ?>

        <div id="custom_product_data" class="panel woocommerce_options_panel">

            <div class="options_group izwsupr">
                <p>
                    <select name="izw_country_type">
                        <option value="none" <?php selected( $data['izw_country_type'],'none'); ?>>&nbsp;</option>
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

        <!--  add Available Shipping Locations
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $html = '<p class="_available">' +

                        '<span>Available Shipping Locations</span><br />' +

                            '<select name="izw_available" class="select short">' +

                                "<option value='united' <?php //selected( $data['izw_available'],'united'); ?> >United States</option>" +

                                "<option value='test' <?php //selected( $data['izw_available'],'test'); ?> >United States Test</option>" +

                            '</select>' +

                        '</p>';

                $( "#shipping_product_data .options_group ._weight_field").before( $html );
            });
        </script>-->
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

        <p class="form-field dimensions_field izw_number">

        <label for="product_shipping_class"><?php _e( 'Phone Number', 'woocommerce' ); ?></label>

        <input type="text" style="width:auto;" name="izw_number" id="izw_number" value="<?php echo $value; ?>" >

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
     *
     */
    function izw_woocommerce_product_options_dimensions(){
        global $post;
        $post_id = $post->ID;

        $key_countries = 'izw_countries_woocommerce_' . $post_id;

        $data = array(

            'izw_country_type'                       => '',

            $key_countries                           => '',

            'izw_available'                          => '',

        );

        foreach($data as $key=>$values){
            if(empty($values)){
                $data[$key] = get_post_meta( $post_id, $key, true );
            }
        }

        $check_error = true;

        $countrys = WC()->countries->countries;     ?>

        <p class="_available">

            <span>Available Shipping Locations</span><br />

            <select name="izw_available" class="select short wc-enhanced-select">

                <?php if(isset($data['izw_country_type']) && !empty($data['izw_country_type'])):

                    switch ($data['izw_country_type']) {
                        case "allow":

                            if( in_array($data['izw_available'],$data[$key_countries])){

                                $check_error = false;

                            }

                            foreach($countrys as $key=>$values){

                                if( in_array($key,$data[$key_countries])){  ?>

                                    <option value='<?php echo $key;?>' <?php selected( $data['izw_available'],$key); ?> ><?php echo $values;?></option>

                                <?php
                                }
                            }
                            break;

                        case "restrict":

                            if( in_array($data['izw_available'],$data[$key_countries])){

                                $check_error = false;

                            }

                            foreach($countrys as $key=>$values){

                                if(! in_array($key,$data[$key_countries])){  ?>

                                    <option value='<?php echo $key;?>' <?php selected( $data['izw_available'],$key); ?> ><?php echo $values;?></option>

                                <?php
                                }
                            }
                            break;

                        default:

                            $check_error = false;

                            foreach($countrys as $key=>$values){    ?>

                                <option value='<?php echo $key;?>' <?php selected( $data['izw_available'],$key); ?> ><?php echo $values;?></option>

                            <?php
                            }
                    }

                endif;?>

            </select>

            <img class="help_tip" data-tip="<?php esc_attr_e( 'Shipping classes are used by certain shipping methods to group similar products.', 'woocommerce' ); ?>" src="<?php echo esc_url( WC()->plugin_url() ); ?>/assets/images/help.png" height="16" width="16" />

            <?php if($check_error){

                echo "<span style='color: red;font-weight: bold;margin-left: 10px;'>Please Select Available Shipping Locations!</span>";

            }?>

        </p>

    <?php
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
            '_izw_unavailable'               => isset( $_POST['izw_unavailable'] ) ? 'yes' : 'no',
            'izw_number'                     => isset( $_POST['izw_number'] ) ? $_POST['izw_number'] : '716-662-0536',
            'izw_country_type'               => isset( $_POST['izw_country_type'] ) ? $_POST['izw_country_type'] : 'none',
            $key_countries                   => !empty( $_POST[$key_countries] ) ? $_POST[$key_countries] : '',
            'izw_error'                      => !empty( $_POST['izw_error'] ) ? $_POST['izw_error'] : 'The item is not availabkle to ship to your location, please call 716-662-0536 for more information and pricing.',
            'izw_available'                  => !empty( $_POST['izw_available'] ) ? $_POST['izw_available'] : '',
        );

        $key_shipping_class = 'izw_available_' . $_POST['izw_available'];//ex: izw_available_VN

        $data['izw_available_shipping'] = array();
        //izw_available_VN = 14
        //product_shipping_class
        foreach($data as $key=>$value):

            update_post_meta( $post_id, $key, $value );

        endforeach;

        /*
         *
         */
//        $datas = get_post_meta($post_id, 'izw_available_shipping');
//
//        if( empty($datas)) {
//
//            $datas['izw_available_' . $_POST['izw_available']] = $_POST['product_shipping_class'];
//
//            add_post_meta($post_id, 'izw_available_shipping', $datas);
//
//        }else{
//
//            $datas['izw_available_' . $_POST['izw_available']] = $_POST['product_shipping_class'];
//
//            update_post_meta( $post_id, 'izw_available_shipping', $datas );
//
//        }

        /*
         * save option
         */
        if(isset($_POST[$key_countries]) && !empty($_POST[$key_countries])){

            update_option( $key_countries, $_POST[$key_countries] );

        }else update_option( $key_countries, "" );
    }

}
new IZW_Add_Product_Data();