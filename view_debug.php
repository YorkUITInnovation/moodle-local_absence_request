<?php
/**
 * Debug information viewer for CSV export troubleshooting
 *
 * Access this page to view debug information stored during CSV exports.
 * URL: /local/absence_request/view_debug.php?userid=YOUR_USER_ID
 */

require_once(__DIR__ . '/../../config.php');

global $DB, $OUTPUT, $PAGE;

require_login();

$userid = optional_param('userid', $USER->id, PARAM_INT);

// Only allow viewing your own debug info unless you're an admin
$context = context_system::instance();
if ($userid != $USER->id && !has_capability('moodle/site:config', $context)) {
    print_error('nopermission');
}

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/absence_request/view_debug.php', ['userid' => $userid]));
$PAGE->set_title('Absence Export Debug Info');
$PAGE->set_heading('Absence Export Debug Info');

echo $OUTPUT->header();

echo '<h2>Export Debug Information for User ID: ' . $userid . '</h2>';

// Retrieve debug info from config
$debug_main = get_config('local_absence_request', 'last_export_debug_user_' . $userid);
$debug_setup = get_config('local_absence_request', 'last_export_setup_user_' . $userid);
$debug_cols = get_config('local_absence_request', 'last_export_download_cols_user_' . $userid);

if ($debug_main || $debug_setup || $debug_cols) {
    echo '<div style="background: #f5f5f5; padding: 15px; margin: 20px 0; border-radius: 5px;">';

    if ($debug_main) {
        echo '<h3>Main Export Debug (teacher_view.php)</h3>';
        echo '<pre style="background: white; padding: 10px; border: 1px solid #ddd; overflow: auto;">';
        echo htmlspecialchars($debug_main);
        echo '</pre>';
    }

    if ($debug_setup) {
        echo '<h3>Table Setup Debug (absence_requests_table.php - setup())</h3>';
        echo '<pre style="background: white; padding: 10px; border: 1px solid #ddd; overflow: auto;">';
        echo htmlspecialchars($debug_setup);
        echo '</pre>';
    }

    if ($debug_cols) {
        echo '<h3>Download Columns Debug (absence_requests_table.php - download_columns())</h3>';
        echo '<pre style="background: white; padding: 10px; border: 1px solid #ddd; overflow: auto;">';
        echo htmlspecialchars($debug_cols);
        echo '</pre>';
    }

    echo '</div>';

    echo '<p><a href="' . $PAGE->url->out() . '" class="btn btn-secondary">Refresh</a></p>';

} else {
    echo '<div class="alert alert-info">';
    echo 'No debug information found for this user. Debug info is stored when you download a CSV/Excel export from the Faculty Absence Report.';
    echo '</div>';
}

echo '<hr>';
echo '<h3>How to use this debug viewer:</h3>';
echo '<ol>';
echo '<li>Go to the Faculty Absence Report page</li>';
echo '<li>Apply your filters</li>';
echo '<li>Click "Download table data as" and choose CSV or Excel</li>';
echo '<li>Come back to this page to view the debug information</li>';
echo '</ol>';

echo '<h3>SQL Query to check config table directly:</h3>';
echo '<pre style="background: #f5f5f5; padding: 10px; border: 1px solid #ddd;">';
echo "SELECT name, value 
FROM {config_plugins} 
WHERE plugin = 'local_absence_request' 
  AND name LIKE 'last_export%' 
ORDER BY name;";
echo '</pre>';

echo $OUTPUT->footer();
