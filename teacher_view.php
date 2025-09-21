<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/absence_request/classes/tables/absence_requests_table.php');

global $OUTPUT, $PAGE, $USER;
$context = context_system::instance();
require_login(1, false);
$PAGE->set_url(new moodle_url('/local/absence_request/teacher_view.php'));
$PAGE->requires->js_call_amd('local_absence_request/acknowledge', 'init');
// Get form parameters.
$starttime = optional_param('starttime', '', PARAM_TEXT);
$endtime = optional_param('endtime', '', PARAM_TEXT);
$download = optional_param('download', '', PARAM_ALPHA);

// If starttime is empty, set starttime to sunday of the current week
if (empty($starttime)) {
    $starttime = strtotime('last sunday');
    $starttime = date('Y-m-d', $starttime);
    // Now set endtime to the next saturday
    $endtime = strtotime('next saturday');
    $endtime = date('Y-m-d', $endtime);
}

// Set parameters for mustache template rendering.
$template_data = [
    'showfaculties' => false,
    'starttime' => $starttime,
    'endtime' => $endtime,
];

// Table class will be loaded from classes/tables/absence_requests_table.php
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

// Get default SQL fields and from statement
$sqlparams = \local_absence_request\helper::get_report_sql();
$fields = $sqlparams->fields;
$from = $sqlparams->from;

$where = '';

$params = [];

if (!empty($starttime)) {
    $where .= ($where ? ' AND ' : '') . " ar.starttime  BETWEEN ? AND ?";
    if (empty($endtime)) {
        $endtime = $starttime . ' 23:59:59';
    } else {
        $endtime = $endtime . ' 23:59:59';
    }
    $params[] = strtotime($starttime);
    $params[] = strtotime($endtime);
}

// Always filter by current user.
$where .= ($where ? ' AND ' : '') . " art.userid = ?";

$params[] = $USER->id;

$table->set_sql($fields, $from, $where, $params);
$table->define_baseurl($PAGE->url);

$table->out(20, true);

if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}