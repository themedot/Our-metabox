<?php
/*
Plugin Name: Our metabox
Plugin URI: http://example.com/
Description: This is a metabox plugin
Version: 1.0
Author: sadathimel
Author URI: http://example.com/
License: GPLv2 or later
Text Domain: our-metabox
Domain Path: /languages
*/

class OurMetabox {
    public function __construct(){
        add_action('plugin_loaded',array($this,'omb_load_textdomain'));

        add_action('admin_menu', array($this,'omb_add_metabox'));
    }

   public function omb_add_metabox(){
        add_meta_box( 
            'omb_post_location',
            __('Location Info','our-metabox'), 
            array($this,'omb_display_post_location'), 
            'post',
            'side',
            'low'
            );
    }

    public function omb_display_post_location()
    {
        $label = __("Location","our-metabox");
        $email =__("Email", "our-metabox");
        $metabox_html = <<<EOD
            <P>
                <label for="omb_location">{$label}</label>
                <input type="text" name="omb_location" id="omb_location" /></br>
                <label for="omb_location">{$email}</label>
                <input type="email" name="omb_location" id="omb_location" />
            </p>
        EOD;

        echo $metabox_html;
    }

    public function omb_load_textdomain(){
        load_plugin_textdomain( 'our-metabox', false, dirname(__FILE__)."/languages");
    }
}
new OurMetabox;