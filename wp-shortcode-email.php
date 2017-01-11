<?php

/*
Plugin Name: Email Shortcode
Plugin URI:  https://github.com/mi-roh/wp-shortcode-email/
GitHub Plugin URI:  https://github.com/mi-roh/wp-shortcode-email/
GitHub Branch:     master
Description: Adds CSS Encryption for E-Mails as ShortCode to Wordpress
Version:     0.0.2
Author:      Micha Rohde
Author URI:  https://github.com/mi-roh/
License:     MIT
License URI: https://github.com/mi-roh/wp-shortcode-email/blob/master/LICENSE
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if (!class_exists('MiRoh_Email', false)) {
    class MiRoh_Email {

        private $_headStyles = array();
        private $_globalKey = 'miRoh_email_headStyles';

        public static $SALT = 'G0uLUxcWeYRFpoYTMVOq';

        public function __construct() {

            if (!isset($GLOBALS[$this->_globalKey])) {
                $GLOBALS[$this->_globalKey] = array();
                add_shortcode('email', array(__CLASS__, 'embed'));
                add_action('wp_footer', array(__CLASS__, 'headStyle'));
            }
            $this->_headStyles = &$GLOBALS[$this->_globalKey];

        }

        static public function embed($data) {
            $email = array_shift($data);

            $self = new self();


            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return '';
            }
            return $self->_email($email);
        }

        static public function headStyle($a = false, $b = false) {
            $self = new self();
            if (!empty($self->_headStyles)) {
                echo '<style>';
                echo '.miRoh-email {unicode-bidi:bidi-override; direction: rtl;white-space:nowrap;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;-o-user-select: none;user-select: none;}';
                foreach ($self->_headStyles as $style) {
                    echo $style;
                }
                echo '</style>';
            }
        }

        private function _email($email) {
            list($user, $domain) = explode('@', $email);
            $key = sha1 (strrev($user) . 'aAdress' . strrev($domain) . static::$SALT . rand( 10000, 99999 ) );

            $this->_headStyles[] = '.miRoh-email' . $key . ':after {content:\'' . strrev($user) . '\'}';
            $this->_headStyles[] = '.miRoh-email' . $key . ':before {content:\'' . strrev($domain) . '\'}';
            return'<span class="miRoh-email miRoh-email' . $key . '">&#064;</span>';
        }


    }
}

new MiRoh_Email();
