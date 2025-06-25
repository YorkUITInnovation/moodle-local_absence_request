<?php
require_once(__DIR__ . '/../../config.php');
include_once('lib.php');

use local_absence_request\helper;
use local_absence_request\notifications;

global $USER, $DB, $OUTPUT, $PAGE, $CFG;
require_login();
$context = context_system::instance();

$PAGE->set_url(new moodle_url('/local/absence_request/index.php'));
$PAGE->set_context($context);
$PAGE->set_title(get_string('absence_request', 'local_absence_request'));
$PAGE->set_heading(get_string('absence_request', 'local_absence_request'));

require_once($CFG->dirroot . '/local/absence_request/classes/forms/request_form.php');

$courseid = optional_param('courseid', 0, PARAM_INT);

$userid = $USER->id;
$eligible = false;
$osgoode = false;
$max_requests_exceeded = false;
// Set return URL based on courseid.
$returnurl = $courseid ? new moodle_url('/course/view.php', ['id' => $courseid]) : new moodle_url('/my/');

// Check eligibility
$eligibility = helper::is_eligible($userid);

$eligible = $eligibility->eligible;
$osgoode = $eligibility->osgoode;
$max_requests_exceeded = $eligibility->max_requests_exceeded;

// Render main page.
echo $OUTPUT->header();

if (!$eligible) {
    // Not eligible: show message using mustache.
    $renderer = $PAGE->get_renderer('core');
    if ($max_requests_exceeded) {
        $message = get_string('max_requests_reached', 'local_absence_request');
    } else {
        $message = get_string('not_eligible', 'local_absence_request');
    }
    echo $OUTPUT->render_from_template('local_absence_request/not_eligible', [
        'message' => $message
    ]);
    echo $OUTPUT->footer();
    exit;
}

// Check if user has already made 2 requests this term.
$termstart = strtotime('first day of January this year'); // Adjust as needed for your academic term logic.
$termend = strtotime('last day of December this year');
$numrequests = $DB->count_records_select('local_absence_request', 'userid = ? AND timecreated >= ? AND timecreated <= ?', [
    $userid, $termstart, $termend
]);

if ($numrequests >= 2) {
    echo $OUTPUT->render_from_template('local_absence_request/not_eligible', [
        'message' => get_string('max_requests_reached', 'local_absence_request'),
        'url' => $returnurl->out(false)
    ]);
    echo $OUTPUT->footer();
    exit;
}

$mycourses = enrol_get_users_courses($userid, true);
// Make sure we only have the courses for this academic year
$mycourses = helper::get_courses_in_acadyear($mycourses);

if (empty($mycourses)) {
    echo $OUTPUT->render_from_template('local_absence_request/not_eligible', [
        'message' => get_string('not_enrolled_in_courses', 'local_absence_request'),
        'url' => $returnurl->out(false)
    ]);
    echo $OUTPUT->footer();
    exit;
}

echo $OUTPUT->render_from_template('local_absence_request/student_instructions', []);

$form_data = new stdClass();
$form_data->userid = $userid;
$form_data->courseid = $courseid;
// Show the request form.
$form = new \local_absence_request\forms\request_form(null, ['formdata' => $form_data]);
if ($form->is_cancelled()) {
    if ($courseid) {
        // If the form is cancelled, redirect to the course page.
        redirect(new moodle_url('/course/view.php', ['id' => $courseid]));
    } else {
        redirect(new moodle_url('/my/'));
    }

} else if ($data = $form->get_data()) {
    // Save request to DB for each instructor.
    require_once($CFG->dirroot . '/lib/messagelib.php');
    require_once($CFG->dirroot . '/user/lib.php');

    $instructors = [];
    // Get the editingteacher in the role table 
    $role = $DB->get_record('role', ['shortname' => 'editingteacher'], '*', MUST_EXIST);

    $reporturl = (new moodle_url('/local/absence_request/view.php'))->out(false);
    // Get the ldapfaculty id from the user_info_field table
    $ldapfacultyfield = $DB->get_record('user_info_field', ['shortname' => 'ldapfaculty'], '*', MUST_EXIST);
    // Get faculty from user profile field 'ldapfaculty'.
    $faculty = $DB->get_field('user_info_data', 'data', ['userid' => $userid, 'fieldid' => $ldapfacultyfield->id], IGNORE_MISSING);

    // Insert one absence request record for the user.
    $record = new stdClass();
    $record->userid = $userid;
    $record->faculty = $faculty;
    $record->circumstance = $data->circumstance;
    $record->starttime = $data->starttime;
    $record->endtime = $data->endtime;
    $record->acadyear = helper::get_acad_year();
    $record->termperiod = helper::get_current_period();
    $record->timecreated = time();
    $absence_request_id = $DB->insert_record('local_absence_request', $record);
    // Notify the student.
    notifications::notify_student($userid, $absence_request_id);
    foreach ($mycourses as $course) {
        $course_record = new stdClass();
        $course_record->absence_request_id = $absence_request_id;
        $course_record->courseid = $course->id;
        $course_record->timecreated = time();
        $absence_req_course_id = $DB->insert_record('local_absence_req_course', $course_record);
        $context = context_course::instance($course->id);
        $teachers = get_role_users($role->id, $context);
        foreach ($teachers as $teacher) {
            $teacher_record = new stdClass();
            $teacher_record->absence_req_course_id = $absence_req_course_id;
            $teacher_record->userid = $teacher->id;
            $teacher_record->timecreated = time();
            $DB->insert_record('local_absence_req_teacher', $teacher_record);
            // Notify the teacher.
            notifications::notify_teacher($teacher->id, $absence_request_id);
        }
    }

    echo $OUTPUT->render_from_template('local_absence_request/request_submitted', [
        'message' => get_string('request_submitted', 'local_absence_request')
    ]);
    echo $OUTPUT->footer();
    exit;
}
// Render the form using mustache.
$formhtml = $form->render();
echo $OUTPUT->render_from_template('local_absence_request/request_form', [
    'form' => $formhtml
]);

echo $OUTPUT->footer();
