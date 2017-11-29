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

require_once __DIR__ . '/lib/EMail.php';

MIROH\email\EMail::get_instance();

