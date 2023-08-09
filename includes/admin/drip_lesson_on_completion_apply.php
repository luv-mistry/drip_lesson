<?php


function learndash_lessson_completed_for_completion_fields( $lesson_data ) {

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

    
    
    $lesson_schedule = get_user_meta($user_id, 'lesson_schedule',true);
    if (empty($lesson_schedule)){
        $lesson_schedule = array(
            $complete_lesson => $completion_days,
        );
        update_user_meta( $user_id,'lesson_schedule' , $lesson_schedule );
    }else {
        if (in_array($lesson_id, $lesson_schedule , true)){
            unset ($lesson_schedule[$lesson_id]);
        }
        $lesson_schedule[$complete_lesson] = $completion_days;
        update_user_meta( $user_id,'lesson_schedule' , $lesson_schedule );
    }
    

 }

add_action( 'learndash_lesson_completed', 'learndash_lessson_completed_for_completion_fields');



function lesson_access_for_completion_based_fields( $timestamp, $lesson_id, $user_id ) {
    // May add any custom logic using $timestamp, $lesson_id, $user_id.

    $lesson_schedule = get_user_meta($user_id, 'lesson_schedule',true);
    if (!empty($lesson_schedule)){
        $flag = false;
        foreach ($lesson_schedule as $key => $value ){
            if ($key == $lesson_id){
                $flag = true;
            }
        }
        if ($flag){
            $timestamp = strtotime(date('Y-m-d',strtotime('+'.$lesson_schedule[$lesson_id].' day',strtotime('now'))));
        }
        
          
        
    }

    return $timestamp;
}

 add_filter(
    'ld_lesson_access_from',
    'lesson_access_for_completion_based_fields',
    10,
    3
);

?>