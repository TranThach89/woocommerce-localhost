<?php
/**
 * Created by PhpStorm.
 * User: S
 * Date: 1/23/2015
 * Time: 10:03 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Class FPT
 */
if (! class_exists('FPT')):
    class FPT{

        public $fpt_connect;

        public $fpt_login;

        /*
         * Set up basic connection
         *
         * @param $ftp_server
         */
        function __construct($fpt_server,$fpt_port){

            $this->fpt_connect = ftp_connect($fpt_server,$fpt_port) or die("Couldn't connect to $fpt_server");

        }

        /*
         * Login with username and password
         *
         * @param $fpt_user
         * @param $fpt_pass
         */
        function connect_fpt($fpt_user, $fpt_pass){

            $this->fpt_login = ftp_login($this->fpt_connect, $fpt_user, $fpt_pass);

            if ((!$this->fpt_connect) || (!$this->fpt_login))

                die("FTP Connection Failed");

        }

        /*
         * Try to change the directory to somedir
         *
         * @param $fpt_dir
         */
        function change_dir($fpt_dir){

            if(!empty($fpt_dir)):

                if (ftp_chdir($this->fpt_connect, $fpt_dir)) {

                    //echo "Current directory is now: " . ftp_pwd($conn_id) . "\n";

                } else {

                    die("Couldn't change directory $fpt_dir \n");

                }

            endif;
        }

        /*
         * Close the connection
         */
        function close_connect(){

            ftp_close($this->fpt_connect);

        }

    }
endif;