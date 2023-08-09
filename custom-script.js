jQuery(document).ready(function($) {
    var course_id = $('#learndash-lesson-access-settings_course').val()
    $('#learndash-lesson-access-settings_completion_based_days_field').hide();
    $('#learndash-lesson-access-settings_completion_based_next_lesson_field').hide();
    post_id =  $('#post_ID').val();
   
    $.ajax({
        url : '/wp-admin/admin-ajax.php',
        data : {
            'action' : 'course_id',
            'course_id' : course_id 
        },success : function(data){
            let lessons = JSON.parse(data)
            $('#learndash-lesson-access-settings_completion_based_next_lesson').append($('<option>').text('Select the  lesson').val('-1'));
            for (let lesson in lessons){
                if (lesson != post_id){
                    console.log(lesson + ' = ' + lessons[lesson] );
                    $('#learndash-lesson-access-settings_completion_based_next_lesson').append($('<option>').text(lessons[lesson]).val(lesson));
                }
            }
        }
    })

    $('#learndash-lesson-access-settings_course').on('change', function() {
        var course_id = $(this).val();
        console.log(course_id);
        $('#learndash-lesson-access-settings_completion_based_next_lesson').find('option').remove();
        $.ajax({
            url : '/wp-admin/admin-ajax.php',
            data : {
                'action' : 'course_id',
                'course_id' : course_id 
            },success : function(data){
                let lessons = JSON.parse(data)
                $('#learndash-lesson-access-settings_completion_based_next_lesson').append($('<option>').text('Select the  Field').val('-1'));
                for (let lesson in lessons){
                    if (lesson != post_id){
                        
                        $('#learndash-lesson-access-settings_completion_based_next_lesson').append($('<option>').text(lessons[lesson]).val(lesson));
                    }
                }
                
            }
        })
        
    
    });


    $('input[name="learndash-lesson-access-settings[lesson_schedule]"]').click(function(){
        if ($(this).val() == 'completion_based'){
            $('#learndash-lesson-access-settings_completion_based_days_field').show();
            $('#learndash-lesson-access-settings_completion_based_next_lesson_field').show();
        }
        else{
           
            $('#learndash-lesson-access-settings_completion_based_days_field').hide();
            $('#learndash-lesson-access-settings_completion_based_next_lesson_field').hide();
           
        }
    })




});


