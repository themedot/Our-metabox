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

        add_action( 'save_post', array($this,'omb_save_metabox'));
    }

    private function is_secured($nonce_field,$action,$post_id)
    {
        $nonce = isset($_POST[$nonce_field]) ? $_POST[$nonce_field]:"";

        if($nonce == ''){
            return false;
        }

        if(!wp_verify_nonce( $nonce, $action )){
            return false;
        }

        if(!current_user_can( 'edit_post', $post_id )){
            return false;
        }

        if(wp_is_post_autosave($post_id)){
            return false;
        }

        if(wp_is_post_revision( $post_id )){
            return false;
        }
        return true;
    }


    public function omb_save_metabox($post_id)
    {
        if(!$this->is_secured('omb_location_field','omb_location',$post_id)){
            return $post_id;
        }
        $location = isset($_POST["omb_location"])? $_POST["omb_location"]:"";
        $country = isset($_POST["omb_country"])? $_POST["omb_country"]:"";
        $is_favorite = isset($_POST["omb_is_favorite"]) ? $_POST["omb_is_favorite"] : 0;
        $colors = isset($_POST["omb_clr"]) ? $_POST["omb_clr"] : array();

        if($location=='' || $country==''){
            return $post_id;
        }

        update_post_meta( $post_id, 'omb_location', $location);
        update_post_meta( $post_id, 'omb_country', $country);
        update_post_meta( $post_id, 'omb_is_favorite', $is_favorite);
    }

   public function omb_add_metabox(){
        add_meta_box( 
            'omb_post_location',
            __('Location Info','our-metabox'), 
            array($this,'omb_display_metabox'), 
            'post',
            'normal',
            'default'
            );
    }

    public function omb_display_metabox($post)
    {
        $location = get_post_meta( $post->ID, 'omb_location', true);
        $label1 = __("Location","our-metabox");

        $country = get_post_meta( $post->ID, 'omb_country', true);
        $label2 = __("Country","our-metabox");

        $is_favorite = get_post_meta( $post->ID, 'omb_is_favorite', true);
        $checked = $is_favorite == 1 ? "checked" : '';
        $label3 = __("Is Favorite","our-metabox");

        $label4 = __("Colors","our-metabox");
        $colors = array('pink','yellow','blue','red','black','green','magenta');

        wp_nonce_field( 'omb_location', 'omb_location_field');

        $metabox_html = <<<EOD
            <P>
                <label for="omb_location">{$label1}:</label>
                <input type="text" name="omb_location" id="omb_location" value="{$location}" /></br>
                <label for="omb_country">{$label2}:</label>
                <input type="text" name="omb_country" id="omb_country" value="{$country}" /></br>
            </p>
            <p>
                <label for="omb_is_favorite">{$label3}:</label>
                <input type="checkbox" name="omb_is_favorite" id="omb_is_favorite" value="1" {$checked} /></br>
            </p>  
              
            <p>
                <label >{$label4}:</label>
        EOD;

            foreach($colors as $color){
                $metabox_html .= <<<EOD
                    <label for="omb_clr_{$color}">{$color}</label>
                    <input type="checkbox" name="omb_clr[]" id="omb_clr_{$color}" value="{$color}"/>
                EOD;
            }
            $metabox_html .= "</p>"; 
            echo $metabox_html;
    }

    public function omb_load_textdomain(){
        load_plugin_textdomain( 'our-metabox', false, dirname(__FILE__)."/languages");
    }
}
new OurMetabox;