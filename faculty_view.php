<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/absence_request/classes/tables/absence_requests_table.php');

use local_absence_request\helper;

global $OUTPUT, $PAGE, $USER;

require_login(1, false);

// Check if the plugin is enabled
if (!get_config('local_absence_request', 'enabled')) {
    redirect(new moodle_url('/my/'));
}


$context = context_system::instance();

if (!has_capability('local/absence_request:view_faculty_report', $context)) {
    // User does not have permission to view faculty report, redirect to their own report.
    redirect(new moodle_url('/my/'));
}

$faculty = optional_param('faculty', '', PARAM_TEXT);
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
    'showfaculties' => true,
    'starttime' => $starttime,
    'endtime' => $endtime,
    'faculties' => helper::get_faculties($faculty),
    'teacher_view' => false,
    'acknowledge_enabled' => get_config('local_absence_request', 'acknowledge_enabled')
];

// Table class will be loaded from classes/tables/absence_requests_table.php
$table = new \local_absence_request\tables\absence_requests_table('absence-requests-table');
$table->is_downloading($download, 'faculty_absence_report_' . date('Ymd', time()), 'absence_report');

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
    $where .= ($where ? ' AND ' : '') . " ar.timecreated  BETWEEN ? AND ?";
    if (empty($endtime)) {
        $endtime = $starttime . ' 23:59:59';
    } else {
        $endtime = $endtime . ' 23:59:59';
    }
    $params[] = strtotime($starttime);
    $params[] = strtotime($endtime);
}

// Only filter by faculty if a specific faculty is selected
if (!empty($faculty) && $faculty !== 'ALL') {
    $where .= ($where ? ' AND ' : '') . " ar.faculty = ?";
    $params[] = $faculty;
}

$table->set_sql($fields, $from, $where, $params);

// Create base URL with all current parameters to preserve them during pagination
$baseurl = new moodle_url($PAGE->url);
if (!empty($faculty)) {
    $baseurl->param('faculty', $faculty);
}
if (!empty($starttime)) {
    $baseurl->param('starttime', $starttime);
}
if (!empty($endtime)) {
    $baseurl->param('endtime', $endtime);
}

$table->define_baseurl($baseurl);

$table->out(20, true);

if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}
