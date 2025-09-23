<?php
/**
 * Student view page for reported absences
 *
 * This page displays all reported absences for the logged-in student
 * organized by term (Fall, Winter, Summer) for the current academic year.
 */

require_once('../../config.php');
require_once('classes/helper.php');

use local_absence_request\helper;

require_login();

global $DB, $USER, $PAGE, $OUTPUT;

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/absence_request/student_view.php');
$PAGE->set_title(get_string('my_reported_absences', 'local_absence_request'));
$PAGE->set_heading(get_string('my_reported_absences', 'local_absence_request'));
$PAGE->requires->css('/local/absence_request/styles.css');

// Ensure jQuery and Bootstrap JavaScript are loaded
$PAGE->requires->jquery();
$PAGE->requires->js_call_amd('theme_boost/bootstrap/collapse', 'init');

// Get current academic year and student ID
$academic_year = helper::get_acad_year();
$student_id = $USER->id;

// Get all absence requests for the current academic year
$sql = "SELECT ar.*, 
               u.firstname as student_firstname, 
               u.lastname as student_lastname,
               u.idnumber as student_idnumber
        FROM {local_absence_request} ar
        INNER JOIN {user} u ON u.id = ar.userid
        WHERE ar.userid = :userid 
        AND ar.acadyear = :acadyear
        ORDER BY ar.timecreated DESC";

$absence_requests = $DB->get_records_sql($sql, [
    'userid' => $student_id,
    'acadyear' => $academic_year
]);

// Organize requests by term period
$terms_data = [
    'F' => [
        'name' => get_string('fall_term', 'local_absence_request'),
        'period' => 'F',
        'requests' => []
    ],
    'W' => [
        'name' => get_string('winter_term', 'local_absence_request'),
        'period' => 'W',
        'requests' => []
    ],
    'S' => [
        'name' => get_string('summer_term', 'local_absence_request'),
        'period' => 'S',
        'requests' => []
    ]
];

// Process each absence request
foreach ($absence_requests as $request) {
    $term_period = $request->termperiod;

    // Get courses for this request
    $courses_sql = "SELECT arc.*, c.fullname, c.shortname, c.idnumber
                    FROM {local_absence_req_course} arc
                    INNER JOIN {course} c ON c.id = arc.courseid
                    WHERE arc.absence_request_id = :request_id
                    ORDER BY c.fullname";

    $courses = $DB->get_records_sql($courses_sql, ['request_id' => $request->id]);

    $request_data = [
        'id' => $request->id,
        'circumstance' => get_string($request->circumstance, 'local_absence_request'),
        'startdate' => userdate($request->starttime, '%B %d, %Y'),
        'enddate' => userdate($request->endtime, '%B %d, %Y'),
        'duration' => helper::calculate_days($request->starttime, $request->endtime),
        'timecreated' => userdate($request->timecreated, '%B %d, %Y at %I:%M %p'),
        'courses' => []
    ];

    // Process each course
    foreach ($courses as $course) {
        // Get teachers for this course
        $teachers_sql = "SELECT art.*, u.firstname, u.lastname, u.email, u.idnumber as employee_id
                        FROM {local_absence_req_teacher} art
                        INNER JOIN {user} u ON u.id = art.userid
                        WHERE art.absence_req_course_id = :course_id
                        ORDER BY u.lastname, u.firstname";

        $teachers = $DB->get_records_sql($teachers_sql, ['course_id' => $course->id]);

        // Generate unique, clean identifier for accordion
        $course_clean_id = 'course_' . $course->courseid . '_' . $course->id;

        $course_data = [
            'fullname' => $course->fullname,
            'shortname' => $course->shortname,
            'idnumber' => $course->idnumber,
            'clean_id' => $course_clean_id,
            'request_id' => $request->id, // Add request ID to each course for template access
            'teachers' => []
        ];

        // Process each teacher
        foreach ($teachers as $teacher) {
            $course_data['teachers'][] = [
                'firstname' => $teacher->firstname,
                'lastname' => $teacher->lastname,
                'email' => $teacher->email,
                'employee_id' => $teacher->employee_id,
                'acknowledged' => $teacher->acknowledged,
                'acknowledged_text' => $teacher->acknowledged ?
                    get_string('yes') : get_string('no'),
                'acknowledged_class' => $teacher->acknowledged ? 'text-success' : 'text-warning'
            ];
        }

        $request_data['courses'][] = $course_data;
    }

    // Add request to appropriate term
    if (isset($terms_data[$term_period])) {
        $terms_data[$term_period]['requests'][] = $request_data;
    }
}

// Add sequential numbering to requests within each term
foreach ($terms_data as &$term) {
    $counter = 1;
    foreach ($term['requests'] as &$request) {
        $request['sequence_number'] = $counter;
        $counter++;
    }
}

// Prepare template data
$template_data = [
    'academic_year' => $academic_year,
    'student_name' => fullname($USER),
    'terms' => array_values($terms_data),
    'has_requests' => !empty($absence_requests)
];

// Add flags to determine if terms have requests
foreach ($template_data['terms'] as &$term) {
    $term['has_requests'] = !empty($term['requests']);
}

echo $OUTPUT->header();

// Render the template
echo $OUTPUT->render_from_template('local_absence_request/student_view', $template_data);

echo $OUTPUT->footer();
