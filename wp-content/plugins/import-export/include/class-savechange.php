<?php
/**
 * Created by PhpStorm.
 * User: S
 * Date: 1/23/2015
 * Time: 11:34 AM
 */

/*
 * class savechange
 */
class savechange{

    /*
     * contruct
     */
    function __construct(){

        add_action("save_change_import_export",array($this,"save_change_import_export_fun"));

    }

    /*
     * save FPT and Import/Export information
     */
    function save_change_import_export_fun(){

        if(check_info_post('save_change','Save Change')):

            //save all info
            foreach($_POST as $key=>$value){

                if($key == 'save_change') continue;

                update_option($key,$value);

            }

            //run cron job
            wp_schedule_event( time(), get_option( 'import_time', true ), 'cron_job_import' );

            wp_schedule_event( time(), get_option( 'export_time', true ), 'cron_job_export' );

        endif;

    }

}
new savechange();