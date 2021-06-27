<?php
/*
Plugin Name: Our Metabox
Plugin URI: http://example.com/
Description:
Version: 1.0
Author: metabox api demo
Author URI: http://example.com/
License: GPLv2 or later
Text Domain: our-metabox
Domain Path: /languages
 */

class OurMetabox {
    public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'omb_load_textdomain' ] );
        add_action( 'admin_menu', [ $this, 'omb_add_metabox' ] );
    }

    function omb_add_metabox() {
        add_meta_box( 
            'omb_post_location', 
            __( 'Location Info', 'our-metabox' ), 
            [$this, 'omb_display_post_location' ], 
            'post',
            'side'
            
        );
    }

    function omb_display_post_location() {
        $label        = __( 'Location', 'our-metabox' );
        $metabox_html = <<<EOD
        <p>
            <label for="omb_location">{$label}</label>
            <input type="text" name="omb_location" id="omb_location >
        </p>
        EOD;
        echo $metabox_html;
    }

    function omb_load_textdomain() {
        load_plugin_textdomain( 'our-metabox', false, dirname( __FILE__ ) . "/languages" );
    }
}

new OurMetabox();
