<?php


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



function learndash_lesson_setting_fields_display( $setting_option_fields = array(), $settings_metabox_key = '' ) {
        
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
                'description' => sprintf(
                    // translators: placeholder: lesson, course.
                    esc_html_x( 'The next %s will be available X days after %s  completion', 'placeholder: lesson', 'learndash' ),
                    learndash_get_custom_label_lower( 'lesson' ),
                    learndash_get_custom_label_lower( 'lesson' )
                ),
                    
                    
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
}


add_filter('learndash_settings_fields','learndash_lesson_setting_fields_display',  30, 2);
 





?>