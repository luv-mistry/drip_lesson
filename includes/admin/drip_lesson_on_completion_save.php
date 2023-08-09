<?php

function learndash_lesson_access_setting_save_completion_based_fields( $post_id = 0, $post = null, $update = false ) {
       
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

}



add_action('save_post','learndash_lesson_access_setting_save_completion_based_fields',  30, 3);




?>