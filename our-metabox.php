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
        add_action( 'admin_enqueue_scripts', array($this,'omb_admin_assets'));
    }

    function omb_admin_assets()
    {
        wp_enqueue_style( 'omb-admin-style', plugin_dir_url(__FILE__)."assets/admin/css/style.css", null, time());
        wp_enqueue_style( 'jquery-ui-css','//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css', null, time());
        wp_enqueue_script( 'omb-admin-js', plugin_dir_url( __FILE__ )."assets/admin/js/main.js",array('jquery','jquery-ui-datepicker'),time(),true);

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
        $colors2 = isset($_POST["omb_color"]) ? $_POST["omb_color"] : '';
        $select_color = isset($_POST["select_color"]) ? $_POST["select_color"] : '';

        if($location=='' || $country==''){
            return $post_id;
        }

        update_post_meta( $post_id, 'omb_location', $location);
        update_post_meta( $post_id, 'omb_country', $country);
        update_post_meta( $post_id, 'omb_is_favorite', $is_favorite);
        update_post_meta( $post_id, 'omb_clr', $colors);
        update_post_meta( $post_id, 'omb_color', $colors2);
        update_post_meta( $post_id, 'omb_select_color', $select_color);
    }


   public function omb_add_metabox(){
        add_meta_box( 
            'omb_post_location',
            __('Location Info','our-metabox'), 
            array($this,'omb_display_metabox'), 
            array('post','page'),
            'normal',
            'default'
        );
        add_meta_box( 
            'omb_book_info',
            __('Book Info','our-metabox'), 
            array($this,'omb_book_info'), 
            array('book')
        );
    }

    public function omb_book_info($post)
    {
     wp_nonce_field( 'omb_book', 'omb_book_nonce');
     
     $metabox_html = <<<EOD
        <div class="fields">
            <div class="field_c">
                <div class="label_c" >
                    <label for="book_author">Book Author</label>
                </div>
                <div class="input_c">
                    <input text ="text" id="book_author">
                </div>
            </div>
            <div class="float_c"></div>
        </div>
        <div class="fields">
            <div class="field_c">
                <div class="label_c" >
                    <label for="book_isbn">Book ISBN</label>
                </div>
                <div class="input_c">
                    <input text ="text" id="book_isbn">
                </div>
            </div>
            <div class="float_c"></div>
        </div>
        <div class="fields">
            <div class="field_c">
                <div class="label_c" >
                    <label for="book_publish">Publish Year</label>
                </div>
                <div class="input_c">
                    <input text ="text" class="omb_dp" id="publish_year">
                </div>
            </div>
            <div class="float_c"></div>
        </div>
        
     EOD;

     echo $metabox_html;
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
        $saved_colors = get_post_meta( $post->ID, 'omb_clr', true );

        $saved_radio_color = get_post_meta( $post->ID, 'omb_color', true );

        $select_color = get_post_meta( $post->ID, 'omb_select_color', true);

        // print_r($saved_radio_color);

        

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

                $_color = ucwords($color);
                $checked =  in_array($color, $saved_colors) ? "checked='checked'" : '';
                $metabox_html .= <<<EOD
                    <label for="omb_clr_{$color}">{$_color}</label>
                    <input type="checkbox" name="omb_clr[]" id="omb_clr_{$color}" value="{$color}" {$checked} />
                EOD;
            }
            $metabox_html .= "</p>"; 

            $metabox_html .= '<p> <label > ' . $label4 . ':</label> </p>';

            foreach($colors as $color){
                $_color = ucwords($color);
                $checked = $color == $saved_radio_color ? 'checked': '';
                $metabox_html .= <<<EOD
                    <label for="omb_color_{$color}">{$_color}</label>
                    <input type="radio" name="omb_color" id="omb_color_{$color}" value="{$color}" {$checked}/>
                EOD;
            }

            $metabox_html .= '<p> <label> '. $label4 . ': </label> ';

            $metabox_html .= '<select name="select_color">';

            foreach ( $colors as $color ) {
                $selected = $color == $select_color ? 'selected="selected"' : '';
                $option = "<option value='{$color}'{$selected}>".ucwords($color)."</option>";
                $metabox_html .= $option;
            }
               
            $metabox_html .= '</select></p>';
            

            echo $metabox_html;
    }

    public function omb_load_textdomain(){
        load_plugin_textdomain( 'our-metabox', false, dirname(__FILE__)."/languages");
    }
}
new OurMetabox;