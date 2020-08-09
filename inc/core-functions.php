<?php // Plugin Functions
function course_certificate_add_course_certificate($code, $name, $course, $hours, $dob, $award_date, $editid){
	global $wpdb;
    if( is_numeric($editid) && $editid != '' ) {
        $result = $wpdb->update('segwitz_course_certificates', array(
            'certificate_code' => $code,
            'student_name' => $name,
            'course_name'  => $course,
            'course_hours' => $hours,
            'dob' => $dob,
            'award_date' => $award_date,
            ),
            array( 'id' => $editid )
        );
    } else {
        $result = $wpdb->insert('segwitz_course_certificates', array(
            'certificate_code' => $code,
            'student_name' => $name,
            'course_name'  => $course,
            'course_hours' => $hours,
            'dob' => $dob,
            'award_date' => $award_date,
            )
        );
    }
    return $result;
}

function course_certificate_delete_course_certificate($editid) {
    global $wpdb;
    $result = false;
    if( is_numeric($editid) && $editid != '' ) {
        $result = $wpdb->delete('segwitz_course_certificates', array( 'id' => $editid ));
    }
    return $result;
}