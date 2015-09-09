<?php
/**
 * Created by PhpStorm.
 * User: S
 * Date: 1/23/2015
 * Time: 2:49 PM
 */

/*
 * class export_product
 */
class export_product{

    /*
     * contruct
     */
    function __construct(){

        add_action("export_product_import_export",array($this,"export_product_import_export_fun"));

    }

    /*
     * export product
     */
    function export_product_import_export_fun(){

        $args = array(
            'post_type'           => 'product',
            'posts_per_page'      => -1,
        );
        $querys = new WP_Query($args);
        $count = 1;
        $lists = array();
        if ( $querys->have_posts() ) {
            while ( $querys->have_posts() ) {
                $querys->the_post();
                global $post,$product;

                //get value
                $article = "\"ARTICLE\";";
                $id_key = get_option( 'product_num', true ) . ";";
                $ean = "\"EAN1234567890123\";";
                $sku = get_info_forduct(get_the_ID(),"sku") . ";";
                $another = "\"\";";
                $productID = get_the_ID() . ";";
                $stock = get_info_forduct(get_the_ID(),"stock_quantity") . ";";
                $product_title = "\"" . get_the_title() . "\"";
//                    $lists[$count][] = $article;
//                    $lists[$count][] = $id_key;
//                    $lists[$count][] = $ean;
//                    $lists[$count][] = $sku;
//                    $lists[$count][] = $another;
//                    $lists[$count][] = $stock;
//                    $lists[$count][] = $product_title;

                //save value
                $lists[$count][] = $article . $id_key . $ean . $sku . $another . $productID . $stock . $product_title;
                $lists[$count][] = "";
                $count++;
            }
        }

        //end of file
        $lists[$count][] = "\"END_OF_FILE\";" . $count;
        $lists[$count][] = "";

        //type save
//“ARTICLE”;28148;”EAN1234567890123”;SKU;”Article123”;Product_ID;Stock-Quantity;”Testartikel”;”Various Artists”;”SON”;”LF123”;”24.12.2011”

    // Create file csv
        $filedir = __IMEXPATH__ . "/export/product" . date("m-d-Y_H-i") . ".csv";

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

        $file_name_upload = "product_thach.csv";

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
new export_product();