<?php // Plugin Functions
function add_course_certificate($code, $name, $course, $hours, $dob, $award_date){
	global $wpdb;
    $result = $wpdb->insert('segwitz_course_certificates', array(
        'certificate_code' => $code,
        'student_name' => $name,
        'course_name'  => $course,
        'course_hours' => $hours,
        'dob' => $dob,
        'award_date' => $award_date,
        )
    );
    return $result;
}