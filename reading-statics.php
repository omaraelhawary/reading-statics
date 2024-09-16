<?php 

/*
    Plugin Name: Posts Read Statics
    Description: This plugin to get read statics for posts.
    Version: 1.0
    Author: Omar ElHawary
    Author URI: https://www.linkedin.com/in/omaraelhawary/
    Text Domain: prsdomain
    Domain Path: /languages
*/

Class ReadingStatics{
    /**
     * Class constructor method.
     * 
     * Initializes the plugin by adding actions and filters to WordPress hooks.
     * 
     * @return void
     */
    function __construct(){
        add_action('admin_menu', array($this, 'adminPage'));
        add_action('admin_init', array($this, 'registerSettings'));
        add_filter('the_content', array($this, 'readStatics'));
        add_action('init', array($this, 'loadTextDomain'));
    }

    /**
     * Loads the plugin's text domain.
     *
     * This function is used to load the plugin's text domain for translation purposes.
     * It uses the load_plugin_textdomain function provided by WordPress.
     *
     * @return void
     */
    function loadTextDomain(){
        load_plugin_textdomain( 'prsdomain', false, dirname(plugin_basename(__FILE__)) . '/languages' );
    }

    /**
     * Reads and processes statics for a post based on the given content.
     *
     * @param string $content The content of the post to process.
     * @return string The processed content with statics added if applicable.
     */
    function readStatics($content){
        if(is_main_query() && is_single() && 
        (
            get_option('prs_word_count', 1) || 
            get_option('prs_char_count', 1) || 
            get_option('prs_read_time', 1)
        ) ){
            return $this -> readStaticsHTML($content);
        }
        return $content;
    }

    /**
     * Generates the HTML for post statics based on the given content.
     *
     * @param string $content The content of the post to process.
     * @return string The HTML for post statics.
     */
    function readStaticsHTML($content){
        $html = '<h3>' . esc_html(get_option('prs_headline', 'Post Statistics')) . '</h3> <p>';

        //Word Count 
        if(get_option('prs_word_count', '1') || get_option('prs_read_time', '1')){
            $wordCount = str_word_count(strip_tags($content));
        }

        if(get_option('prs_word_count', '1')){
            $html .= esc_html__('This Post has', 'prsdomain') . ' ' . $wordCount . ' ' . esc_html__('words', 'prsdomain').'.<br>';
        }

        if(get_option('prs_char_count', '1')){
            $html .= 'This Post has '. strlen(strip_tags($content)) .' characters.<br>';
        }

        if(get_option('prs_read_time', '1')){
            $html .= ' This Post will take about '. round($wordCount/225) .' minute(s) to read.<br>';
        }

        $html .= '</p>';

        if(get_option('prs_location', '0') == '0'){
            return $html . $content;
        }

        return $content . $html;
    }

    /**
     * Adds the admin page for the Posts Read Statics plugin.
     *
     * @return void
     */
    function adminPage(){
        add_options_page(
            'Posts Read Statics',
            __('Posts Read Statics', 'prsdomain'),
            'manage_options',
            'posts-read-statics-settings',
            array($this, 'pluginSettingsHTML')
        );
    }

    /**
     * Registers settings for the Posts Read Statics plugin.
     *
     * This function adds settings sections, fields, and registers settings for the plugin.
     * It includes settings for display location, headline text, word count, character count, and read time.
     *
     * @return void
     */
    function registerSettings(){
        add_settings_section( 'prsFirstSection', null, null, 'posts-read-statics-settings');
        //Display Location
        add_settings_field('prs_location', 'Display Location', array($this, 'locationHTML'), 'posts-read-statics-settings', 'prsFirstSection');
        register_setting('postsReadStatics', 'prs_location', array(
            'sanitize_callback' => array($this, 'sanitizeLocation'),
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

    /**
     * Sanitizes the display location input to ensure it is either '0' (Beginning of Post) or '1' (End of Post).
     *
     * @param string $input The display location input to be sanitized.
     * @return string The sanitized display location input.
     */
    function sanitizeLocation($input){
        if($input != '0' && $input != '1'){
            add_settings_error( 'prs_location', 'prs_location_error', 'Display Location must be Beginning of Post or End of Post');
            return get_option('prs_location');
        }
        return $input;
    }

    /**
     * Generates HTML for a checkbox input field based on the given arguments.
     *
     * @param array $args An array containing the name of the checkbox input field.
     * @return void
     */
    function checkboxHTML($args){?>
<input type="checkbox" name="<?php echo $args['theName'] ?>" value="1"
    <?php checked( get_option($args['theName']), '1' ); ?>>
<?php
    }


    /**
     * Generates the HTML for the headline input field.
     *
     * @return string The HTML for the headline input field.
     */
    function headlineHTML(){ ?>
<input type=" text" name="prs_headline" value="<?php echo esc_attr( get_option('prs_headline') ) ?>">
<?php
    }

    /**
     * Generates the HTML for the display location setting.
     *
     * @return string The HTML for the display location setting.
     */
    function locationHTML(){ ?>
<select name="prs_location">
    <option value="0" <?php selected( get_option('prs_location'), '0'); ?>>Beginning of post</option>
    <option value="1" <?php selected( get_option('prs_location'), '1'); ?>>End of post</option>
</select>
<?php   
    }
        
    /**
     * Generates the HTML for the plugin settings page.
     *
     * This function is responsible for rendering the settings page for the Posts Read Statics plugin.
     * It includes a form with fields for plugin settings and a submit button.
     *
     * @return void
     */
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