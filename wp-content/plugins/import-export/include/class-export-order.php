<?php
/**
 * Created by PhpStorm.
 * User: S
 * Date: 1/24/2015
 * Time: 1:08 PM
 */

/*
 * class export_order
 */
class export_order{

    /*
     * contruct
     */
    function __construct(){

        add_action("export_order_import_export",array($this,"export_order_import_export_fun"));

    }

    /*
     * export order
     */
    function export_order_import_export_fun(){

        $args = array(
            'post_type'         =>  'shop_order',
            'posts_per_page'    =>  -1,
            'post_status'       => 'publish',
        );
        $querys = new WP_Query($args);
        $count = 1;
        $lists = array();
        if ( $querys->have_posts() ) {
            while ( $querys->have_posts() ) {
                $querys->the_post();
                global $post;
                $order = new WC_Order( get_the_ID() );

        /*     CUSTOMER     */

                //get value CUSTOMER
                $customer = "\"CUSTOMER\",";
                $id_key = get_option( 'order_num', true ) . ",";
                $userID = "\"" . $order->get_user_id() . "\",";
                $first_name = "\"" . $order->billing_first_name . "\",";
                $last_name = "\"" . $order->billing_last_name . "\",";
                $city = "\"" . $order->billing_city . "\"";
                //echo $customer . $id_key . $userID . $first_name . $last_name . $city . "<br>";

                //save value CUSTOMER
                $lists[$count][] = $customer . $id_key . $userID . $first_name . $last_name . $city;
                $lists[$count][] = "";
                $count++;

        /*     PICKLIST     */

                //get value PICKLIST
                $picklist = "\"PICKLIST\",";
                $orderID = get_the_ID() . ",";
                $custom1 = "\"\",";
                $custom2 = "\"\",";
                $orderstatus = "\"" . $order->get_status() . "\"";
                //echo $picklist . $id_key . $userID . $orderID . $custom1 . $custom2 . $orderstatus . "<br>";

                //save value PICKLIST
                $lists[$count][] = $picklist . $id_key . $userID . $orderID . $custom1 . $custom2 . $orderstatus;
                $lists[$count][] = "";
                $count++;

        /*     ADDR     */

                //get value ADDR
                $addr = "\"ADDR\",";
                $country = "\"" . $order->billing_country . "\",";
                $address1  = "\"" . $order->billing_address_1 . "\"";
                //echo $addr . $id_key . $userID . $orderID . $first_name . $last_name . $country . $address1 . "<br>";

                //save value ADDR
                $lists[$count][] = $addr . $id_key . $userID . $orderID . $first_name . $last_name . $country . $address1;
                $lists[$count][] = "";
                $count++;

        /*     ORDER_LINE     */

                //get value ORDER_LINE
                $orderline = "\"ORDER_LINE\",";
                $items = $order->get_items();

                //save value ORDER_LINE
                foreach($items as $item) {

                    $productID = $item["product_id"] . ",";
                    $articleID = "\"" . $item["variation_id"] . "\",";
                    $quantily = $item["qty"] . ",";
                    $ean = "\"EAN1234567890123\";";
                    //echo $orderline . $id_key . $orderID . $productID . $articleID . $quantily . $ean . "<br>";

                    $lists[$count][] = $orderline . $id_key . $orderID . $productID . $articleID . $quantily . $ean;
                    $lists[$count][] = "";
                    $count++;

                }

            }
        }

        //end of file
        $lists[$count][] = "\"END_OF_FILE\"," . $count;
        $lists[$count][] = "";

        // Create file csv
        $filedir = __IMEXPATH__ . "/export/order" . date("m-d-Y_H-i") . ".csv";

        $fp = fopen($filedir, 'w') or die("can't open file");

        foreach ($lists as $list) {
            fputcsv($fp, $list);
        }

        fclose($fp);

        //Upload file
        $options = array(
            'fpt_server'                => get_option( 'fpt_server', true ),
            'fpt_user'                  => get_option( 'fpt_user', true ),
            'fpt_pass'                  => get_option( 'fpt_pass', true ),
            'fpt_port'                  => get_option( 'fpt_port', true ),
            'export_forder'             => get_option( 'export_forder', true ),
        );
        extract($options);

        // set up basic connection
        $fpt = new FPT($fpt_server,$fpt_port);

        // login with username and password
        $fpt->connect_fpt($fpt_user,$fpt_pass);

        $file_name_upload = "order_thach.csv";

        // try to change the directory to somedir
        $fpt->change_dir($export_forder);

        // upload file
        if (ftp_put($fpt->fpt_connect, $file_name_upload, $filedir, FTP_ASCII)) {

            //echo "Successfully uploaded $file.";

        }else{

            echo "Error uploading $file.";

        }

        // close connection
        $fpt->close_connect();

    }

}
new export_order();