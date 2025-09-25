<?php
require_once(__DIR__ . '/../../config.php');
include_once('lib.php');

use local_absence_request\helper;
use local_absence_request\notifications;

global $USER, $DB, $OUTPUT, $PAGE, $CFG;

require_once($CFG->dirroot . '/local/absence_request/classes/forms/request_form.php');
// Check if the plugin is enabled
if (!get_config('local_absence_request', 'enabled')) {
    redirect(new moodle_url('/my/'));
}




$courseid = optional_param('courseid', 0, PARAM_INT);
$return = optional_param('r', '', PARAM_TEXT);
$context = context_course::instance($courseid);
require_login($courseid);


$PAGE->set_url(new moodle_url('/local/absence_request/index.php', ['courseid' => $courseid]));
$PAGE->set_title(get_string('absence_request', 'local_absence_request'));
$PAGE->set_heading(get_string('absence_request', 'local_absence_request'));
$PAGE->set_context($context);


$userid = $USER->id;
$eligible = false;
$osgoode = false;
$max_requests_exceeded = false;

if (!empty($return)) {
    // If a return URL is provided, use it.
    $returnurl = new moodle_url(helper::return_urls($return));
} else if ($courseid) {
    // If courseid is provided, redirect to course view.
    $returnurl = new moodle_url('/course/view.php', ['id' => $courseid]);
} else {
    // Default return URL is the My Moodle page.
    $returnurl = new moodle_url('/my/');
}


// Check eligibility
$eligibility = helper::is_eligible($userid);

$eligible = $eligibility->eligible;
$osgoode = $eligibility->osgoode;
$max_requests_exceeded = $eligibility->max_requests_exceeded;

// Render main page.
echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_absence_request/student_view_absences',['courseid' => $courseid]);

if (!$eligible) {
    // Not eligible: show message using mustache.
    $renderer = $PAGE->get_renderer('core');
    if ($max_requests_exceeded) {
        $message = get_string('max_requests_reached', 'local_absence_request');
    } else {
        $message = get_string('not_eligible', 'local_absence_request');
    }
    echo $OUTPUT->render_from_template('local_absence_request/not_eligible', [
        'message' => $message,
        'url' => $returnurl->out(false)
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


if ($numrequests >= get_config('local_absence_request','requests_per_term')) {
    echo $OUTPUT->render_from_template('local_absence_request/not_eligible', [
        'message' => get_string('max_requests_reached', 'local_absence_request'),
        'url' => $returnurl->out(false)
    ]);
    echo $OUTPUT->footer();
    exit;
}

$mycourses = enrol_get_users_courses($userid, false);
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
$form_data->returnurl = $returnurl->out(false);
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

    // Array to track teachers that have been notified to avoid duplicate notifications
    $notified_teachers = [];

    foreach ($mycourses as $course) {
        // Check if the student is suspended in this specific course.
        $sql = "SELECT ue.status 
                FROM {user_enrolments} ue 
                JOIN {enrol} e ON e.id = ue.enrolid 
                WHERE ue.userid = ? AND e.courseid = ?";
        $studentenrolment = $DB->get_record_sql($sql, [$userid, $course->id]);

        if ($studentenrolment && $studentenrolment->status == ENROL_USER_SUSPENDED) {
            // Student is suspended in this course, skip to next course
            continue;
        }

        // Student is not suspended, create course record
        $course_record = new stdClass();
        $course_record->absence_request_id = $absence_request_id;
        $course_record->courseid = $course->id;
        $course_record->timecreated = time();
        $absence_req_course_id = $DB->insert_record('local_absence_req_course', $course_record);
        $context = context_course::instance($course->id);
        $teachers = get_role_users($role->id, $context);
        foreach ($teachers as $teacher) {
            // Get the enrolment method for this teacher in this specific course
            $sql = "SELECT e.id, e.enrol 
                    FROM {enrol} e 
                    JOIN {user_enrolments} ue ON e.id = ue.enrolid 
                    WHERE ue.userid = ? AND e.courseid = ?";
            $teacherenrolment = $DB->get_record_sql($sql, [$teacher->id, $course->id]);

            // Check if this teacher should receive notifications based on enrollment methods setting
            $should_notify = false;
            $enrollment_methods_setting = get_config('local_absence_request', 'enrollment_methods');

            if ($teacherenrolment) {
                switch ($enrollment_methods_setting) {
                    case 'all':
                        // Notify all teachers regardless of enrollment method
                        $should_notify = true;
                        break;
                    case 'manual':
                        // Only notify teachers enrolled manually
                        $should_notify = ($teacherenrolment->enrol == 'manual');
                        break;
                    case 'arms':
                    default:
                        // Only notify teachers enrolled via ARMS (default behavior)
                        $should_notify = ($teacherenrolment->enrol == 'arms');
                        break;
                }
            }

            if ($should_notify) {
                $teacher_record = new stdClass();
                $teacher_record->absence_req_course_id = $absence_req_course_id;
                $teacher_record->userid = $teacher->id;
                $teacher_record->timecreated = time();
                $new_teacher_record = $DB->insert_record('local_absence_req_teacher', $teacher_record);
            }
        }
    }

    echo $OUTPUT->render_from_template('local_absence_request/request_submitted', [
        'message' => get_string('request_submitted', 'local_absence_request'),
        'url' => $data->returnurl,
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
