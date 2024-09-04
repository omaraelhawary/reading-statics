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
        //Display Location
        add_settings_field('prs_location', 'Display Location', array($this, 'locationHTML'), 'posts-read-statics-settings', 'prsFirstSection');
        register_setting('postsReadStatics', 'prs_location', array(
            'sanitize_callback' => 'sanitize_text_field',
                'default' => '0'
        ));
        //Headline Text
        add_settings_field('prs_headline', 'Headline Location', array($this, 'headlineHTML'), 'posts-read-statics-settings', 'prsFirstSection');
        register_setting('postsReadStatics', 'prs_headline', array(
            'sanitize_callback' => 'sanitize_text_field',
                'default' => 'Post Statics'
        ));

        //Word Count
        add_settings_field('prs_word_count', 'Word Count', array($this, 'checkboxHTML'), 'posts-read-statics-settings', 'prsFirstSection', array('theName' => 'prs_word_count'));
        register_setting('postsReadStatics', 'prs_word_count', array(
            'sanitize_callback' => 'sanitize_text_field',
                'default' => '1'
        ));

        //Character Count
        add_settings_field('prs_char_count', 'Character Count', array($this, 'checkboxHTML'), 'posts-read-statics-settings', 'prsFirstSection', array('theName' => 'prs_char_count'));
        register_setting('postsReadStatics', 'prs_char_count', array(
            'sanitize_callback' => 'sanitize_text_field',
                'default' => '1'
        ));

         //Read Time
         add_settings_field('prs_read_time', 'Read Time', array($this, 'checkboxHTML'), 'posts-read-statics-settings', 'prsFirstSection', array('theName' => 'prs_read_time'));
         register_setting('postsReadStatics', 'prs_read_time', array(
             'sanitize_callback' => 'sanitize_text_field',
                 'default' => '1'
         ));
    }



    function checkboxHTML($args){?>
<input type="checkbox" name="<?php echo $args['theName'] ?>" value="1"
    <?php checked( get_option($args['theName']), '1' ); ?>>
<?php
    }


    function headlineHTML(){ ?>
<input type=" text" name="prs_headline" value="<?php echo esc_attr( get_option('prs_headline') ) ?>">
<?php
    }

    function locationHTML(){ ?>
<select name="prs_location">
    <option value="0" <?php selected( get_option('prs_location'), '0'); ?>>Beginning of post</option>
    <option value="1" <?php selected( get_option('prs_location'), '1'); ?>>End of post</option>
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