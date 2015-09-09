<?php
/**
 * Plugin Name: EXport GF
 * Date: 1/5/15
 * Time: 10:24 AM
 * Author:      S
 * Version:     0.1
 * Domain Path: /languages/
 * License:     GPL
 */

/**
 * define
 */
define( '__EXPORTPATH__', plugin_dir_path( __FILE__ ) );
define( '__EXPORTURL__', plugin_dir_url( __FILE__ ) );
define( '__EXVERSION__', '1.0.1' );

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Class EXport_Support
 */
class export_support{

    /**
     * construct
     */
    public function __construct(){
        add_action( 'admin_menu', array( $this, 'admin_menu' ),200 );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts_admin' ) );
    }

    /**
     * Add Submenu EXport GF on themes.php
     */
    public function admin_menu(){
        if (function_exists('add_options_page')) {
            add_options_page('EXport GF', 'EXport GF', 'edit_theme_options', 'gf_export_options', array($this, 'admin_guis_page'));
            //add_submenu_page( 'options-general.php', 'EXport GF', 'EXport GF', 'edit_theme_options', 'gf_export_options', array('export_support', 'admin_guis_page'));
        }
    }

    /**
     * Gui EXport GF
     */
    public function admin_guis_page(){
        require_once __EXPORTPATH__ . "/admin/guis.php";
    }

    /**
     * register scripts admin
     */
    public function register_scripts_admin(){

        //register style
        wp_register_style( 'wp-datepicker', __EXPORTURL__ . 'admin/css/jquery-ui.css' );
        wp_enqueue_style( 'wp-datepicker' );

        //register jquery
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-datepicker');
        wp_register_script( 'date-pick', __EXPORTURL__ . 'admin/js/date_pick.js');
        wp_enqueue_script( 'date-pick' );
    }

    /**
     * destruct
     */
    public function __destruct(){

    }

}

$export_support = new export_support;