<?php
/**
 * Created by PhpStorm.
 * User: S
 * Date: 1/23/2015
 * Time: 10:29 AM
 */

echo "<h1>Import Order Settings</h1>";


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(check_info_post('save_change','Save Change')){
        do_action("save_change_import_export");
    }

    if(check_info_post('export_product','Export Product')){
        do_action("export_product_import_export");
    }

    if(check_info_post('export_order','Export Order')){
        do_action("export_order_import_export");
    }

    if(check_info_post('update_product','Update Product')){
        do_action("update_product_import_export");
    }

    if(check_info_post('update_order','Update Order')){
        do_action("update_order_import_export");
    }
}

$options = array(
    'fpt_server'                => get_option( 'fpt_server', true ),
    'fpt_user'                  => get_option( 'fpt_user', true ),
    'fpt_pass'                  => get_option( 'fpt_pass', true ),
    'fpt_port'                  => get_option( 'fpt_port', true ),
    'import_forder'             => get_option( 'import_forder', true ),
    'export_forder'             => get_option( 'export_forder', true ),
    'import_time'               => get_option( 'import_time', true ),
    'export_time'               => get_option( 'export_time', true ),
    'product_num'               => get_option( 'product_num', true ),
    'order_num'                 => get_option( 'order_num', true ),
);
$options = apply_filters( 'promolizer_general_settings_arg', $options );
extract($options);?>

<form name="" action="" method="post" class="import_export">
    <h2>FPT information</h2>
        <ul>
            <li><label for="fpt_server">FPT Server </label><input type="text" class="fpt_info" value="<?php echo $fpt_server;?>" name="fpt_server" id="fpt_server"></li>
            <li><label for="fpt_user">FPT UserName </label><input type="text" class="fpt_info" value="<?php echo $fpt_user;?>" name="fpt_user" id="fpt_user"></li>
            <li><label for="fpt_pass">FPT Password </label><input type="password" class="fpt_info" value="<?php echo $fpt_pass;?>" name="fpt_pass" id="fpt_pass"></li>
            <li><label for="fpt_port">FPT & explicit FTPS port </label><input type="text" class="fpt_info" value="<?php echo $fpt_port;?>" name="fpt_port" id="fpt_port"></li>
        </ul>
    <hr>

    <h2>Import / Export information</h2>
        <ul>
            <li><label for="import_forder">Import Form Forder </label><input type="text" class="fpt_info" value="<?php echo $import_forder;?>" name="import_forder" id="import_forder"></li>
            <li><label for="export_forder">Export To Forder </label><input type="text" class="fpt_info" value="<?php echo $export_forder;?>" name="export_forder" id="export_forder"></li>
            <li><label for="import_time">Import Time </label>
                <select class="fpt_info" value="" name="import_time" id="import_time">
                    <option value="hourly" <?php selected($export_time,"hourly");?> >Hourly</option>
                    <option value="twicedaily" <?php selected($export_time,"twicedaily");?>>Twicedaily</option>
                    <option value="daily" <?php selected($export_time,"daily");?>>Daily</option>
                    <option value="weekly" <?php selected($export_time,"weekly");?>>Weekly</option>
                </select>
            </li>
            <li><label for="export_time">Export Time </label>
                <select class="fpt_info" value="" name="export_time" id="export_time">
                    <option value="hourly" <?php selected($export_time,"hourly");?> >Hourly</option>
                    <option value="twicedaily" <?php selected($export_time,"twicedaily");?>>Twicedaily</option>
                    <option value="daily" <?php selected($export_time,"daily");?>>Daily</option>
                    <option value="weekly" <?php selected($export_time,"weekly");?>>Weekly</option>
                </select>
            </li>
            <li><label for="product_num">Product Number </label><input type="text" class="fpt_info" value="<?php echo $product_num;?>" name="product_num" id="product_num"></li>
            <li><label for="order_num">Order Number </label><input type="text" class="fpt_info" value="<?php echo $order_num;?>" name="order_num" id="order_num"></li>
        </ul>
    <hr>

    <?php submit_button( 'Save Change', 'primary', 'save_change');?>

    <hr>
    <h2>Button To Test</h2>

    <div class="button_sumbit">
        <?php submit_button( 'Export Product', 'primary', 'export_product');?>
        <?php submit_button( 'Export Order', 'primary', 'export_order');?>
        <?php submit_button( 'Update Product', 'primary', 'update_product');?>
        <?php submit_button( 'Update Order', 'primary', 'update_order');?>
    </div>

</form>