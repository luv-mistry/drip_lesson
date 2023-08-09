<?php
/*
 Plugin Name: Drip Lesson
 Description: Adds the current page's template name to the admin bar.
 Version: 0.1
 */


$global_course_id ;


function get_course_id(){
    if(isset($_REQUEST)){
        $course_id = $_REQUEST['course_id'];
        global $global_course_id ;
     

        $global_course_id = $course_id;

        $leasson_load = array();
        $lessons = learndash_get_course_lessons_list( $course_id );

        foreach ( $lessons as $lesson ) {
            if ($lesson['id'] != $global_lesson_id){
                $leasson_load[$lesson['id']] = get_the_title($lesson['id']);
            }
        }

        echo json_encode($leasson_load);

    }
    die();
}
add_action( 'wp_ajax_course_id', 'get_course_id' );



add_filter(
    'learndash_settings_fields',
    function ( $setting_option_fields = array(), $settings_metabox_key = '' ) {
        
        if ( 'learndash-lesson-access-settings' === $settings_metabox_key ) {
 
            // Add field here.
            global $global_course_id;


            $post_id           = get_the_ID();
            $global_lesson_id = $post_id ;
            $completion_based_days_data = get_post_meta( $post_id, 'completion_based_days', true );
            $completion_based_next_lesson = get_post_meta($post_id ,'completion_based_days_data', true);
            
            


 
            if ( ! isset( $setting_option_fields['drip_lesson_day_based_on_completion'] ) && ! isset($setting_option_fields['drip_lesson_next_lesson_available'])) {
                $custom_field = array(
                    'completion_based'=> array(
                    'label'       => esc_html__( 'Completion based', 'learndash' ),
                        
                        
                    ),
                );
            
                
                $setting_option_fields['lesson_schedule']['options'] = array_merge($setting_option_fields['lesson_schedule']['options'],$custom_field) ;
                $setting_option_fields['drip_lesson_day_based_on_completion'] = array(
                    'name'      => 'completion_based_days',
                    'label'     => sprintf(
                        // translators: placeholder: Course.
                        esc_html_x( ' ', 'learndash' ),
                        learndash_get_custom_label( 'course' )
                    ),
                    // Check the LD fields ligrary under incldues/settings/settings-fields/
                    'type'      => 'number',
                    'class'     => 'small-text',
                    'value'     => $completion_based_days_data,
                    'default'   => '',
                    'input_label' => esc_html__( 'day(s)', 'learndash' ),
                    'attrs'       => array(
						'step' => 1,
						'min'  => 0,
					),
                    
                );

                
                $setting_option_fields['drip_lesson_next_lesson_available'] = array(
                    'name'      => 'completion_based_next_lesson',
                    'label'     => sprintf(
                        // translators: placeholder: Course.
                        esc_html_x( '', 'learndash' ),
                        learndash_get_custom_label( 'course' )
                    ),
                    // Check the LD fields ligrary under incldues/settings/settings-fields/
                    'type'      => 'select',
                    'class'     => '-medium',
                    'value'     =>  $completion_based_next_lesson,
                    'default'   =>  array(
                        '-1' => sprintf(
                            // translators: placeholder: course.
                            esc_html_x( 'Search or select a %s…', 'placeholder: lessons', 'learndash' ),
                            learndash_get_custom_label( 'lesson' )
                        ),
                    ),
                    'options'   => '',
                    
                );

                
            }
        }
 
        // Always return $setting_option_fields
        return $setting_option_fields;
    },
    30,
    2
);
 

add_action(
    'save_post',
    function( $post_id = 0, $post = null, $update = false ) {
       
        if ( isset( $_POST['learndash-lesson-access-settings']['completion_based_days'] ) && isset($_POST['learndash-lesson-access-settings']['completion_based_next_lesson']) ) {
            $completion_based_days = esc_attr( $_POST['learndash-lesson-access-settings']['completion_based_days'] );
            $completion_based_next_lesson = esc_attr( $_POST['learndash-lesson-access-settings']['completion_based_next_lesson'] );
            // Then update the post meta

            $next_lesson = get_post_meta( $post_id, 'completion_based_days_data', true );
            update_post_meta( $post_id, 'completion_based_days', $completion_based_days );
            update_post_meta( $post_id, 'completion_based_days_data', $completion_based_next_lesson);
            if ($completion_based_next_lesson == '-1') {
                
                update_post_meta ($next_lesson , 'last_completion_based_days' , '0');

            }
            
            
        }
 
    },
    30,
    3
);


add_action( 'learndash_lesson_completed', function( $lesson_data ) {

    $user_id = get_current_user_id();
    $lesson_id = learndash_get_lesson_id();
    $date_of_completion =  strtotime('now');
    $lesson_completion = get_user_meta( $user_id, 'lesson_completion', true );
    if ( empty($lesson_completion)){
        $data = array(
        $lesson_id => $date_of_completion,
    );
    update_user_meta( $user_id, 'lesson_completion', $data );

    }
    else {
        $lesson_completion[$lesson_id] = $date_of_completion;
        update_user_meta( $user_id, 'lesson_completion', $lesson_completion );

    }
      $completion_days = get_post_meta( $lesson_id, 'completion_based_days', true );
      $complete_lesson = get_post_meta ($lesson_id , 'completion_based_days_data' , true);

    update_post_meta ($complete_lesson , 'last_completion_based_days' , $completion_days);
    
    
    

 });



 add_filter(
    'ld_lesson_access_from',
    function( $timestamp, $lesson_id, $user_id ) {
        // May add any custom logic using $timestamp, $lesson_id, $user_id.
        $lesson_access_days = get_post_meta($lesson_id, 'last_completion_based_days', true);
        if (!empty($lesson_access_days)){
            $timestamp = strtotime(date('Y-m-d',strtotime('+'.$lesson_access_days.' day',strtotime('now'))));
        }
        // Always retur
        return $timestamp;
    },
    10,
    3
);



function enqueue_custom_js() {
    
    wp_enqueue_script('your-custom-script', plugin_dir_url( __FILE__ ). 'custom-script.js', array('jquery'));

}
add_action('admin_enqueue_scripts', 'enqueue_custom_js');











