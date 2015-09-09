<?php
/**
 * Created by PhpStorm.
 * User: S
 * Date: 3/11/2015
 * Time: 9:53 AM
 */

class IZW_Custom_Product_Data{
    function __construct(){
        add_filter( 'woocommerce_product_data_tabs', array( $this, 'custom_product_data'), 10,1 );
        add_action( 'woocommerce_product_data_panels', array( $this, 'custom_product_data_panel') );
        add_action( 'woocommerce_process_product_meta', array( $this, 'save' ),999,2 );
    }

    /**
     * Add Custom Tab In Product Of Woocommerce
     * @param $tabData
     * @return mixed
     */
    function custom_product_data($tabData){
        global $post;
        $tabData['izw_custom'] = array(
            'label'  => __( 'Custom', 'woocommerce' ),
            'target' => 'custom_product_data',
            'class'  => array('show_if_simple'),
        );
        return $tabData;
    }

    /**
     * Add Panel for custom tab
     */
    function custom_product_data_panel(){
        global $post;
        $post_id = $post->ID;
        $key_countries = 'izw_countries_woocommerce_' . $post_id;
        $data = array(

            'izw_unavailable'                       => '',

            'izw_number'                            => '',

            $key_countries                           => '',

        );

        foreach($data as $key=>$values){
            if(empty($values)){
                $data[$key] = get_post_meta( $post_id, $key, true );
            }
        }

        ?>

        <div id="custom_product_data" class="panel woocommerce_options_panel">

            <div class="options_group izwsupr">

                <p class="form-field">

                    <input type="checkbox" style="width:auto;" name="izw_unavailable" id="izw_unavailable" value="1" <?php checked( $data['izw_unavailable'] , 1); ?>>

                    <label for="izw_unavailable"><?php _e( "A product as unavailable to order online.", __IZSPDOMAIN__ ); ?></label>

                </p>

            </div>

            <div class="options_group izwsupr izw_number">

                <p class="form-field">

                    <input placeholder="<?php _e( "Phone number", __IZSPDOMAIN__ ); ?>" type="number" style="width:auto;" name="izw_number" id="izw_number" value="<?php echo $data['izw_number']; ?>" >

                </p>

            </div>

            <div class="options_group izwsupr">

                <p>
                    <?php
                    $settings = array(
                        array(
                            'title'   => __( 'Specific Countries', 'woocommerce' ),
                            'desc'    => '',
                            'id'      => $key_countries,
                            'css'     => 'min-width: 350px;',
                            'default' => '',
                            'type'    => 'multi_select_countries',
                        )
                    );

                    WC_Admin_Settings::output_fields($settings);
                    ?>

                </p>

            </div>

            <div class="options_group izwsupr">

                <p class="form-field">

                    <span class="flleft">

                        <input type="submit" name="izw_update" id="izw_update" class="button button-primary" value="Update">

                    </span>

                </p>

            </div>

        </div>

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

        <div class="scripToAppend"></div>
    <?php
    }

    /**
     * Save data
     *
     * @param $post_id
     * @param $post
     */
    function save( $post_id, $post ){

        $key_countries = 'izw_countries_woocommerce_' . $post_id;

        $data = array(
            'izw_unavailable'               => !empty( $_POST['izw_unavailable'] ) ? $_POST['izw_unavailable'] : '0',
            'izw_number'                    => !empty( $_POST['izw_number'] ) ? $_POST['izw_number'] : '01676748290',
            $key_countries                  => !empty( $_POST[$key_countries] ) ? $_POST[$key_countries] : '',
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
    }

}
return new IZW_Custom_Product_Data();