<?php
/**
 * Created by PhpStorm.
 * User: s
 * Date: 1/5/15
 * Time: 10:49 AM
 */
if(isset($_POST['export_leads']) && $_POST['export_lead'] = 'Download Export File'){
    global $wpdb;
    $table = $wpdb->prefix . 'rg_form';
    $ids = $wpdb->get_results( "SELECT id FROM $table" );
    $form_id = 1;
    if (class_exists('RGFormsModel')) {
        $form = RGFormsModel::get_form_meta($form_id);
        $charset = get_option('blog_charset');
        header('Content-Description: File Transfer');
        header("Content-Disposition: attachment; filename=data.csv");
        header('Content-Type: text/plain; charset=' . $charset, true);
        $buffer_length = ob_get_length(); //length or false if no buffer
        if ($buffer_length > 1) {
            ob_clean();
        }
        if (class_exists('GFExport')) {
            GFExport::start_export($form);
            die();
        }
    }else die();
};?>
<?php echo "<h2>Export Entries</h2>";?>
<hr>
<form name="" action="" method="post">
    <div id="col-container">
        <div id="col-left">
            <label for="export_date_start">Start Date:</label>
        </div>
        <div id="col-right">
            <input type="text" id="export_date_start" name="export_date_start">
        </div>

        <div id="col-left">
            <label for="export_date_end">End Date:</label>
        </div>
        <div id="col-right">
            <input type="text" id="export_date_end" name="export_date_end">
        </div>
        <input type="hidden" class="gform_export_field" value="1" name="export_field[]">
        <input type="hidden" class="gform_export_field" value="created_by" name="export_field[]">
        <input type="hidden" class="gform_export_field" value="id" name="export_field[]">
        <input type="hidden" class="gform_export_field" value="date_created" name="export_field[]">
        <input type="hidden" class="gform_export_field" value="source_url" name="export_field[]">
        <input type="hidden" class="gform_export_field" value="transaction_id" name="export_field[]">
        <input type="hidden" class="gform_export_field" value="payment_amount" name="export_field[]">
        <input type="hidden" class="gform_export_field" value="payment_status" name="export_field[]">
        <input type="hidden" class="gform_export_field" value="post_id" name="export_field[]">
        <input type="hidden" class="gform_export_field" value="user_agent" name="export_field[]">
        <input type="hidden" class="gform_export_field" value="ip" name="export_field[]">
    </div>

    <?php submit_button( 'Download Export File', 'primary', 'export_leads');?>

</form>