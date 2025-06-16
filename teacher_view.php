<?php
require_once(__DIR__ . '/../../config.php');

global $OUTPUT, $PAGE, $USER;
$context = context_system::instance();
require_login(1, false);
$PAGE->set_url(new moodle_url('/local/absence_request/view.php'));
$selectedfaculty = optional_param('faculty', 'ALL', PARAM_TEXT);
$starttime = optional_param('starttime', '', PARAM_TEXT);
$endtime = optional_param('endtime', '', PARAM_TEXT);
$download = optional_param('download', '', PARAM_ALPHA);

// Get all faculties from user_info_data (fieldid=2, not LW).
$faculties = [
    ['acronym' => 'ALL', 'name' => get_string('all_faculties', 'local_absence_request')],
    ['acronym' => 'AP', 'name' => 'Faculty of Liberal Arts & Professional Studies'],
    ['acronym' => 'ED', 'name' => 'Faculty of Education'],
    ['acronym' => 'EU', 'name' => 'Faculty of Environmental & Urban Change'],
    ['acronym' => 'FA', 'name' => 'School of the Arts, Media, Performance & Design'],
    ['acronym' => 'GL', 'name' => 'Glendon College / Collège universitaire Glendon'],
    ['acronym' => 'GS', 'name' => 'Faculty of Graduate Studies'],
    ['acronym' => 'HH', 'name' => 'Faculty of Health'],
    ['acronym' => 'LE', 'name' => 'Lassonde School of Engineering'],
    ['acronym' => 'LW', 'name' => 'Osgoode Hall Law School'],
    ['acronym' => 'SB', 'name' => 'Schulich School of Business'],
    ['acronym' => 'SC', 'name' => 'Faculty of Science']
];
// Add is_selected property for template rendering
foreach ($faculties as &$faculty) {
    $faculty['is_selected'] = ($faculty['acronym'] === $selectedfaculty);
}
$template_data = [
    'faculties' => $faculties,
    'starttime' => $starttime,
    'endtime' => $endtime,
];
unset($faculty);

// Table class will be loaded from classes/tables/absence_requests_table.php
require_once($CFG->dirroot . '/local/absence_request/classes/tables/absence_requests_table.php');
$table = new \local_absence_request\tables\absence_requests_table('absence-requests-table');
$table->is_downloading($download, 'test', 'testing123');

if (!$table->is_downloading()) {
    // Only print headers if not asked to download data.
    // Print the page header.
    $PAGE->set_context($context);
    $PAGE->set_title(get_string('absence_request', 'local_absence_request'));
    $PAGE->set_heading(get_string('absence_request', 'local_absence_request'));
    echo $OUTPUT->header();
    // Render faculty filter form.
    echo $OUTPUT->render_from_template('local_absence_request/faculty_filter_form', $template_data);
}


$fields = 'art.id,
           ar.faculty,
           ar.circumstance,
           ar.starttime,
           ar.endtime,
           ar.acadyear,
           ar.termperiod,
           ar.timecreated,
           us.firstname As student_firstname,
           us.lastname As student_lastname,
           us.idnumber As sisid,
           us.email As student_email,
           c.fullname,
           c.shortname,
           c.idnumber As course_id_number,
           ut.firstname As teacher_firstname,
           ut.lastname As teacher_lastname,
           ut.email As teacher_email,
           ut.idnumber As employee_id';

$from = ' {local_absence_req_teacher} art Inner Join
           {local_absence_req_course} arc On arc.id = art.absence_req_course_id Inner Join
           {course} c On c.id = arc.courseid Inner Join
           {user} ut On ut.id = art.userid Inner Join
           {local_absence_request} ar On ar.id = arc.absence_request_id Inner Join
           {user} us On us.id = ar.userid';

$where = '';

$params = [];

if ($selectedfaculty !== 'ALL') {
    $where .= "ar.faculty = ?";
    $params[] = $selectedfaculty;
} else {
    $where .= "ar.faculty IS NOT NULL";
}

if (!empty($starttime)) {
    $where .= ($where ? ' AND ' : '') . " ar.starttime  BETWEEN ? AND ?";
    if (empty($endtime)) {
        $endtime = strtotime($starttime . ' 23:59:59');
    }
    $params[] = $starttime;
    $params[] = $endtime;
}

// Always filter by current user.
$where .= ($where ? ' AND ' : '') . " ar.userid = ?";
$params[] = $USER->id;

$table->set_sql($fields, $from, $where, $params);
$table->define_baseurl($PAGE->url);

$table->out(20, true);

if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}