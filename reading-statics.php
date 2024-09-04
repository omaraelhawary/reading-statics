<?php 

/*
    Plugin Name: Posts Read Statics
    Description: This plugin to get read statics for posts.
    Version: 1.0
    Author: Omar ElHawary
    Author URI: https://www.linkedin.com/in/omaraelhawary/
*/

Class ReadingStatics{
    function __construct(){
        add_action('admin_menu', array($this, 'adminPage'));
        add_action('admin_init', array($this, 'registerSettings'));
    }
    function adminPage(){
        add_options_page(
            'Posts Read Statics',
            'Read Statics',
            'manage_options',
            'posts-read-statics-settings',
            array($this, 'pluginSettingsHTML')
        );
    }

    function registerSettings(){
        add_settings_section( 'prsFirstSection', null, null, 'posts-read-statics-settings');
        add_settings_field('prs_location', 'Display Location', array($this, 'locationHTML'), 'posts-read-statics-settings', 'prsFirstSection');
        register_setting('postsReadStatics', 'prs_location', array(
            'sanitize_callback' => 'sanitize_text_field',
                'default' => '0'
        ));
    }

    function locationHTML(){ ?>
<select name="prs_location">
    <option value="0">Beginning of post</option>
    <option value="1">End of post</option>
</select>
<?php   
    }
        
    function pluginSettingsHTML(){  ?>
<div class="wrap">
    <h1>Posts Read Statics Settings</h1>
    <form action="options.php" method="POST">
        <?php
            settings_fields('postsReadStatics'); 
            do_settings_sections('posts-read-statics-settings'); 
            submit_button();
        ?>
    </form>
</div>
<?php
}
}

$readingStatics = new ReadingStatics();