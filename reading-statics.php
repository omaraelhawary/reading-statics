<?php 

/*
    Plugin Name: Posts Read Statics
    Description: This plugin to get read statics for posts.
    Version: 1.0
    Author: Omar ElHawary
    Author URI: https://www.linkedin.com/in/omaraelhawary/
*/

function postsReadStatics(){
    add_options_page(
        'Posts Read Statics',
        'Read Statics',
        'manage_options',
        'posts-read-statics',
        'ourSettignsPageHTML'
    );
}

function ourSettignsPageHTML(){  ?>

<h1>Posts Read Statics</h1>

<?php    
}

add_action('admin_menu', 'postsReadStatics');