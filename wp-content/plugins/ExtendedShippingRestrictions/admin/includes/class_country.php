<?php
/**
 * Created by PhpStorm.
 * User: S
 * Date: 3/19/2015
 * Time: 10:21 PM
 */

class IZW_Country{
    /*
     * get country by Allow, Restrict, None(tab Restriction)
     */
    public static function get_array_country(){
        global $post;
        $post_id = $post->ID;

        $key_countries = 'izw_countries_woocommerce_' . $post_id;

        $data = array(

            'izw_country_type'                       => '',

            $key_countries                           => '',

            'izw_available'                          => '',

        );

        /*
         * get value
         */
        foreach($data as $key=>$values){
            if(empty($values)){
                $data[$key] = get_post_meta( $post_id, $key, true );
            }
        }

        /*
         * get all country default of WC
         */
        $countrys = WC()->countries->countries;

        //$array_country = $countrys;

        if(isset($data['izw_country_type']) && !empty($data['izw_country_type'])):

            switch ($data['izw_country_type']) {
                case "allow":

                    if(! isset($data[$key_countries]) || empty($data[$key_countries])){

                        $array_country = array();

                    }else{

                        foreach($countrys as $key=>$values){

                            if( in_array($key,$data[$key_countries])){

                                $array_country[$key] = $values;

                            }
                        }
                    }

                    break;

                case "restrict":

                    if(! isset($data[$key_countries]) || empty($data[$key_countries])){

                        $array_country = $countrys;

                    }else {

                        foreach ($countrys as $key => $values) {

                            if (!in_array($key, $data[$key_countries])) {

                                $array_country[$key] = $values;

                            }
                        }
                    }

                    break;

                default:

                    $array_country = $countrys;

            }

        else:

            $array_country = $countrys;

        endif;

        return $array_country;

    }//END FUC get_array_country
}