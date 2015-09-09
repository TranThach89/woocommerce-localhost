<?php
/**
 * Plugin Name: Import / Export
 * Plugin URI:  null
 * Description: .
 * Author:      S
 * Author URI:
 * Version:     0.1
 * Text Domain:
 * Domain Path: /languages/
 * License:     GPL
 */

/**
 * define
 */
define( '__IMEXPATH__', plugin_dir_path( __FILE__ ) );
define( '__IMEXURL__', plugin_dir_url( __FILE__ ) );
define( '__IMEXVERSION__', '1.0.1' );

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Class import_export
 */
class import_export{

    /**
     * construct
     */
    public function __construct(){
        add_action( 'admin_menu', array( $this, 'admin_menu' ),110 );
        add_action( 'init', array( $this, 'include_file'), 50);
        add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts_admin' ) );
    }

    /**
     * Add menu IMPORT EXPORT
     */
    public function admin_menu(){
        if (function_exists('add_options_page')) {

            add_menu_page("IMPORT ~ EXPORT", "IMPORT / EXPORT", 'manage_options', 'import_export', array($this,'admin_guis_page'), plugins_url('/import-export/admin/images/form_options.png' ), 110 );

        }
    }

    /**
     * Gui IMPORT EXPORT
     */
    public function admin_guis_page(){

        require_once __IMEXPATH__ . "/admin/guis.php";

    }

    /**
     * register scripts admin
     */
    public function register_scripts_admin(){

        //register style
        wp_register_style( 'style', __IMEXURL__ . 'admin/css/style.css' );
        wp_enqueue_style( 'style' );

        //register jquery
//        wp_enqueue_script( 'jquery' );
//        wp_enqueue_script( 'jquery-ui-datepicker');
//        wp_register_script( 'date-pick', __IMEXURL__ . 'admin/js/date_pick.js');
//        wp_enqueue_script( 'date-pick' );
    }

    /*
     * Include File
     */
    function include_file(){
        include_once ( __IMEXPATH__ . "/functions.php");
        include_once ( __IMEXPATH__ . "/include/class-savechange.php");
        include_once ( __IMEXPATH__ . "/include/class-export-product.php");
        include_once ( __IMEXPATH__ . "/include/class-export-order.php");
        include_once ( __IMEXPATH__ . "/include/class-update-product.php");
        include_once ( __IMEXPATH__ . "/include/class-update-order.php");
        include_once ( __IMEXPATH__ . "/include/class-fpt.php");
    }

    /**
     * destruct
     */
    public function __destruct(){

    }

}

$import_export = new import_export;
