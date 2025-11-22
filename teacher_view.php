<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/absence_request/classes/tables/absence_requests_table.php');

global $OUTPUT, $PAGE, $USER;

require_login(1, false);

// Check if the plugin is enabled
if (!get_config('local_absence_request', 'enabled')) {
    redirect(new moodle_url('/my/'));
}

// Get form parameters.
$faculty = optional_param('faculty', '', PARAM_TEXT);
$starttime = optional_param('starttime', '', PARAM_TEXT);
$endtime = optional_param('endtime', '', PARAM_TEXT);
$download = optional_param('download', '', PARAM_ALPHA);
$ta = optional_param('ta', false, PARAM_BOOL);
$courseid = required_param('courseid',  PARAM_INT);

$context = context_system::instance();

// If starttime is empty, set starttime to sunday of the current week
if (empty($starttime)) {
    $starttime = strtotime('last sunday');
    $starttime = date('Y-m-d', $starttime);
    // Now set endtime to the next saturday
    $endtime = strtotime('next saturday');
    $endtime = date('Y-m-d', $endtime);
}
$achnowledged_enabled = false;
if ($ta == false && get_config('local_absence_request', 'acknowledge_enabled')) {
    $achnowledged_enabled = true;
}
// Set parameters for mustache template rendering.
$template_data = [
    'showfaculties' => false,
    'starttime' => $starttime,
    'endtime' => $endtime,
    'teacher_view' => true,
    'courseid' => $courseid,
    'ta' => $ta,
    'acknowledge_enabled' => $achnowledged_enabled
];

// Table class will be loaded from classes/tables/absence_requests_table.php
$table = new \local_absence_request\tables\absence_requests_table('absence-requests-table', true, $ta);
$table->is_downloading($download, 'absence_report_' . date('Ymd', time()) , 'absence_report');

$PAGE->set_url(new moodle_url('/local/absence_request/teacher_view.php'));
// Create base URL with all current parameters to preserve them during pagination
$baseurl = new moodle_url($PAGE->url);
$baseurl->param('courseid', $courseid);
if (!empty($starttime)) {
    $baseurl->param('starttime', $starttime);
}
if (!empty($endtime)) {
    $baseurl->param('endtime', $endtime);
}
if ($ta) {
    $baseurl->param('ta', $ta);
}

$table->define_baseurl($baseurl);

if (!$table->is_downloading()) {
    // Only print headers if not asked to download data.
    // Print the page header.
    $PAGE->set_context($context);
    $PAGE->set_title(get_string('reported_absences', 'local_absence_request'));
    $PAGE->set_heading(get_string('reported_absences', 'local_absence_request'));


    // Load the acknowledge JavaScript module for bulk operations
    $PAGE->requires->js_call_amd('local_absence_request/acknowledge', 'init');

    echo $OUTPUT->header();
    // Render faculty filter form.
    echo $OUTPUT->render_from_template('local_absence_request/faculty_filter_form', $template_data);
}

// Get default SQL fields and from statement
$sqlparams = \local_absence_request\helper::get_report_sql();
$fields = $sqlparams->fields;
$from = $sqlparams->from;

$where = '1=1'; // Default condition to ensure WHERE clause is never empty

$params = [];

if (!empty($starttime)) {
    $where .= " AND ar.timecreated BETWEEN ? AND ?";
    if (empty($endtime)) {
        $endtime = $starttime . ' 23:59:59';
    } else {
        $endtime = $endtime . ' 23:59:59';
    }
    $params[] = strtotime($starttime);
    $params[] = strtotime($endtime);
}

// Editing teacher, search by userid
if (!$ta) {
    $where .= " AND art.userid = ?";
    $params[] = $USER->id;
} else {
    // Non-editing teacher, search by courseid
    $where .= " AND arc.courseid = ?";
    $params[] = $courseid;
}

$table->set_sql($fields, $from, $where, $params);

$table->out(20, true);

if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}
