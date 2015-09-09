<?php
/**
 * Created by PhpStorm.
 * User: S
 * Date: 1/24/2015
 * Time: 3:32 PM
 */

class update_product{

    /*
     * contruct
     */
    function __construct(){

        add_action("update_product_import_export",array($this,"update_product_import_export_fun"));

    }

    /*
     * export product
     */
    function update_product_import_export_fun(){

        $options = array(
            'fpt_server'                => get_option( 'fpt_server', true ),
            'fpt_user'                  => get_option( 'fpt_user', true ),
            'fpt_pass'                  => get_option( 'fpt_pass', true ),
            'fpt_port'                  => get_option( 'fpt_port', true ),
            'product_num'               => get_option( 'product_num', true ),
            'import_forder'             => get_option( 'import_forder', true ),
        );

        extract($options);

        // set up basic connection
        $fpt = new FPT($fpt_server,$fpt_port);

        // login with username and password
        $fpt->connect_fpt($fpt_user,$fpt_pass);

        // try to change the directory to somedir
        $fpt->change_dir($import_forder);

        //current dir
        //echo ftp_pwd($fpt->fpt_connect);

        //name file save local
        $file_name_update = __IMEXPATH__ . "import/product.csv";

        //file name server
        $server_file = 'product_thach.csv';
        $link_file = "http://" . $fpt_server . "/" . $import_forder . "/" . $server_file;

        //check file exits
        $file_headers = @get_headers($link_file);

        if($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                // try to download $server_file and save to $file_name_update
                if (ftp_get($fpt->fpt_connect, $file_name_update, $server_file, FTP_ASCII)) {
                    //echo "Successfully written to $file_name_update\n";
                }

        //delete file on server
                // try to delete $file
//                if (ftp_delete($fpt->fpt_connect, $server_file)) {
//                    echo "$file deleted successful\n";
//                } else {
//                    echo "could not delete $file\n";
//                }

        // Change stock
                //open file
                $myfile = fopen($file_name_update, "r");

                while(!feof($myfile)) {

                    $value = fgets($myfile);

                    $key_check = "Stock";

                    //check value key Stock
                    if((str_replace("\"","",get_value_position($value,1)) == $key_check) && (get_value_position($value,2) == $product_num)){

                        $product = wc_get_product(get_value_position($value,3));

                        //set stock
                        $product->set_stock(get_value_position($value,4));

                    }

                }

                fclose($myfile);

        }

        // close connection
        $fpt->close_connect();

    }
}
new update_product();