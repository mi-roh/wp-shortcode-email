<?php
/**
 * Created by PhpStorm.
 * User: micharohde on 29.11.17 16:47
 */

namespace MIROH\email;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class EMail {

    private $_styles = array();
    private $_globalKey = 'miRoh_generateEmail_styles';

    protected $_shortCode = 'email';

    protected static $_SALT = 'G0uLUxcWeYRFpoYTMVOq';


    /**
     * @var EMail
     */
    private static $instance;

    /**
     * Setup the instance.
     *
     * @return EMail
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) ) {
            self::$instance = new self;
            self::$instance->setup_actions();
        }

        return self::$instance;
    }

    public function setup_actions() {

        if ( !isset( $GLOBALS[ $this->_globalKey ] ) ) {
            $GLOBALS[ $this->_globalKey ] = array();
        }

        add_shortcode( $this->_shortCode, array( $this, '_shortCode' ) );
        add_action( 'wp_footer', array( $this, '_footerStyle' ) );

    }

    public function _shortCode($data) {
        $email = array_shift($data);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return '';
        }
        return $this->_generateEmail($email);
    }

    protected function _getFooterStyle() {
        $content = '<style>';
        $content .= '.miRoh-email {unicode-bidi:bidi-override; direction: rtl;white-space:nowrap;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;-o-user-select: none;user-select: none;}';
        foreach ($this->_styles as $style) {
            $content .= $style;
        }
        $content .= '</style>';
        return $content;
    }

    public function _footerStyle() {

        if (!empty($this->_styles)) {
            echo $this->_getFooterStyle();
        }
    }

    private function _generateEmail($email) {
        list($user, $domain) = explode('@', $email);
        $key = sha1 (strrev($user) . 'aAdress' . strrev($domain) . static::$_SALT . rand( 10000, 99999 ) );

        $this->_styles[] = '.miRoh-email' . $key . ':after {content:\'' . strrev($user) . '\'}';
        $this->_styles[] = '.miRoh-email' . $key . ':before {content:\'' . strrev($domain) . '\'}';

        return'<span class="miRoh-email miRoh-email' . $key . '">&#064;</span>';
    }
    
    public function addEmail( $email, $styles = false ) {

        $content = $this->_generateEmail( $email );

        if ( $styles ) {
            $content .= $this->_getFooterStyle();
        }

        return  $content;
    }

}