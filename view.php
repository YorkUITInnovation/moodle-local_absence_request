<?php
require_once(__DIR__ . '/../../config.php');

global $OUTPUT, $PAGE, $USER;
$context = context_system::instance();
require_login(1, false);

if (!has_capability('local/absence_request:view_faculty_report', $context)) {
    redirect(new moodle_url('/my/'), get_string('nopermission', 'local_absence_request'));
}

$PAGE->set_url(new moodle_url('/local/absence_request/view.php'));
$selectedfaculty = optional_param('faculty', 'ALL', PARAM_TEXT);
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
    'showfaculties' => true,
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

// Get default SQL fields and from statement
$sqlparams = \local_absence_request\helper::get_report_sql();
$fields = $sqlparams->fields;
$from = $sqlparams->from;

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
        $endtime = $starttime . ' 23:59:59';
    }
    $params[] = strtotime($starttime);
    $params[] = strtotime($endtime);
}

$table->set_sql($fields, $from, $where, $params);
$table->define_baseurl($PAGE->url);

$table->out(20, true);

if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}