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
    }
        function adminPage(){
            add_options_page(
                'Posts Read Statics',
                'Read Statics',
                'manage_options',
                'posts-read-statics',
                array($this, 'pluginSettingsHTML')
            );
        }
        
        function pluginSettingsHTML(){  ?>
<div class="wrap">
    <h1>Posts Read Statics Settings</h1>
</div>
<?php }
}

$readingStatics = new ReadingStatics();