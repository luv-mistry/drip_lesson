<?php
/*
 Plugin Name: Drip Lesson
 Description: Drip Lesson based on completion of Lesson 
 Version: 1.0
 */

 if(!defined('ABSPATH')){
    die("");
}

include  (plugin_dir_path( __FILE__ ).'/includes/admin/drip_lesson_on_completion_display.php') ;


include(plugin_dir_path( __FILE__ ).'/includes/admin/drip_lesson_on_completion_save.php');


include(plugin_dir_path( __FILE__ ).'/includes/admin/drip_lesson_on_completion_apply.php');



function enqueue_custom_js() {
    
    wp_enqueue_script('your-custom-script', plugin_dir_url( __FILE__ ). 'assets/js/custom-script.js', array('jquery'));

}
add_action('admin_enqueue_scripts', 'enqueue_custom_js');











