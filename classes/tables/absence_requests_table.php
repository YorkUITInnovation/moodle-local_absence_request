<?php
// This file defines the absence_requests_table class for displaying absence requests in a table format.
// It is part of the local_absence_request plugin for Moodle.

namespace local_absence_request\tables;

// Ensure this file is being included by a Moodle script.
defined('MOODLE_INTERNAL') || die();

// Include Moodle's tablelib for table_sql base class.
require_once($CFG->libdir . '/tablelib.php');

use moodle_url;

/**
 * Class absence_requests_table
 * Displays a table of absence requests with student and course director details.
 */
class absence_requests_table extends \table_sql
{
    // Faculty filter (if needed for future extension)
    protected $faculty;

    // Track if this is a teacher view (for download column exclusion)
    protected $teacher_view;

    protected $ta;

    /**
     * Constructor for the absence_requests_table.
     * Defines columns and headers for the table.
     *
     * @param string $uniqueid Unique identifier for the table instance.
     * @param bool $teacher_view If true, hide the teacher column (default: false).
     */
    public function __construct($uniqueid, $teacher_view = false, $ta = false)
    {
        parent::__construct($uniqueid);

        $this->teacher_view = $teacher_view;
        $this->ta = $ta;

        // Check if acknowledge receipt is enabled
        $acknowledge_enabled = get_config('local_absence_request', 'acknowledge_enabled');

        // Define the base columns to be displayed in the table.
        $columns = [
            'student_lastname',
            'sisid',
            'circumstance',
            'starttime',
            'endtime',
            'duration',
            'course_fullname'
        ];

        // Define the base column headers (localized strings).
        $headers = [
            get_string('student', 'local_absence_request'),
            get_string('sisid', 'local_absence_request'),
            get_string('circumstance', 'local_absence_request'),
            get_string('absence_start', 'local_absence_request'),
            get_string('absence_end', 'local_absence_request'),
            get_string('duration', 'local_absence_request'),
            get_string('course', 'local_absence_request')
        ];

        // Add teacher column only if not in teacher view
        if (!$teacher_view) {
            $columns[] = 'teacher_lastname';
            $headers[] = get_string('teacher', 'local_absence_request');
        }

        // Add timecreated column
        $columns[] = 'timecreated';
        $headers[] = get_string('submitted', 'local_absence_request');

        // Add acknowledged column and checkbox column only if enabled and in teacher view
        if ($acknowledge_enabled && $teacher_view && !$ta) {
            // Add checkbox column for bulk selection
            array_unshift($columns, 'checkbox');
            array_unshift($headers, '<input type="checkbox" id="select_all_absences" title="Select All">');
        }

        // Add acknowledged column only if enabled
        if ($acknowledge_enabled) {
            $columns[] = 'acknowledged';
            $headers[] = get_string('acknowledged', 'local_absence_request');
        }

        $this->define_columns($columns);
        $this->define_headers($headers);

        // Make checkbox column not sortable if acknowledge is enabled and in teacher view
        if ($acknowledge_enabled && $teacher_view) {
            $this->no_sorting('checkbox');
        }
    }

    /**
     * Override to exclude checkbox column from downloads.
     * IMPORTANT: This ensures the checkbox column is excluded and array is properly indexed.
     * PHP 7.4 requires explicit re-indexing after unset() to prevent column misalignment.
     *
     * @return array List of columns to include in download
     */
    public function download_columns()
    {
        global $USER;
        $columns = $this->columns;

        $debug_before = count($columns) . ' columns: ' . implode(', ', array_keys($columns));

        // Remove checkbox column from downloads if it exists
        // Note: $this->columns is an associative array with column names as keys
        if (isset($columns['checkbox'])) {
            $debug_action = 'Removing checkbox column';
            unset($columns['checkbox']);
        } else {
            $debug_action = 'No checkbox to remove';
        }

        $debug_after = count($columns) . ' columns: ' . implode(', ', array_keys($columns));

        // Store debug info in config for retrieval
        $debuginfo = [
            'method' => 'download_columns',
            'before' => $debug_before,
            'action' => $debug_action,
            'after' => $debug_after,
            'timestamp' => time()
        ];
        set_config('last_export_download_cols_user_' . $USER->id, json_encode($debuginfo), 'local_absence_request');

        return $columns;
    }

    /**
     * Override setup to exclude checkbox column when downloading.
     * CRITICAL: Must be called before parent::setup() to ensure proper column handling in PHP 7.4
     */
    public function setup()
    {
        global $USER;

        // If downloading and checkbox column exists, remove it BEFORE parent setup
        if ($this->is_downloading()) {
            $debug_steps = [];

            // DEBUG: Log column state before modification
            $debug_steps[] = 'Before: ' . count($this->columns) . ' columns: ' . implode(', ', array_keys($this->columns));
            $debug_steps[] = 'Headers count: ' . count($this->headers);

            // After define_columns(), $this->columns is an associative array with column names as keys
            if (isset($this->columns['checkbox'])) {
                $debug_steps[] = 'Found checkbox column, removing it';

                // Find the index position of checkbox to remove the corresponding header
                $column_keys = array_keys($this->columns);
                $checkbox_position = array_search('checkbox', $column_keys);

                $debug_steps[] = 'Checkbox position: ' . $checkbox_position;

                // Remove checkbox from columns (associative array)
                unset($this->columns['checkbox']);

                // Remove the header at the same position (indexed array)
                if ($checkbox_position !== false && isset($this->headers[$checkbox_position])) {
                    unset($this->headers[$checkbox_position]);
                    // Re-index headers array to remove gaps
                    $this->headers = array_values($this->headers);
                    $debug_steps[] = 'Removed header at position ' . $checkbox_position;
                }

                $debug_steps[] = 'After: ' . count($this->columns) . ' columns: ' . implode(', ', array_keys($this->columns));
                $debug_steps[] = 'Headers count after: ' . count($this->headers);
            } else {
                $debug_steps[] = 'No checkbox column found';
            }

            // Store debug info in config for retrieval
            $debuginfo = [
                'method' => 'setup',
                'steps' => $debug_steps,
                'timestamp' => time()
            ];
            set_config('last_export_setup_user_' . $USER->id, json_encode($debuginfo, JSON_PRETTY_PRINT), 'local_absence_request');
        }

        parent::setup();
    }

    /**
     * Override get_sql_sort to always include starttime and endtime in sort order.
     * Also fixes ambiguous column references by using proper table aliases.
     *
     * @return string The ORDER BY clause for the SQL query.
     */
    public function get_sql_sort()
    {
        $sort = parent::get_sql_sort();

        // Always include starttime and endtime in sort order for consistency
        $additional_sort = 'ar.starttime ASC, ar.endtime ASC';

        if (!empty($sort)) {
            // If there's already a sort, append the additional sort
            return $sort . ', ' . $additional_sort;
        }

        // If no sort is specified, use just the additional sort
        return $additional_sort;
    }

    /**
     * Override other_cols to handle columns without explicit col_* methods.
     * This is called by table_sql for columns that don't have a specific col_columnname() method.
     *
     * @param string $colname Column name
     * @param stdClass $row Row data object
     * @return string Column value
     */
    public function other_cols($colname, $row)
    {
        // For columns that don't have col_* methods, return the raw field value
        // The col_* methods are automatically called by table_sql for columns that have them
        if (isset($row->$colname)) {
            return $row->$colname;
        }

        // If the column doesn't exist in the row data, return empty string
        return '';
    }

    /**
     * Define the base URL for the table with filters persisted.
     *
     * @param moodle_url $url The base URL for the table
     */
    public function define_baseurl($url)
    {
        // Get current request parameters
        $starttime = optional_param('starttime', '', PARAM_TEXT);
        $endtime = optional_param('endtime', '', PARAM_TEXT);
        $faculty = optional_param('faculty', 'ALL', PARAM_TEXT);

        // Add these parameters to the base URL so they persist through sorting
        if (!empty($starttime)) {
            $url->param('starttime', $starttime);
        }
        if (!empty($endtime)) {
            $url->param('endtime', $endtime);
        }
        if (!empty($faculty)) {
            $url->param('faculty', $faculty);
        }

        parent::define_baseurl($url);
    }

    /**
     * Set link for student profile.
     * When downloading, return plain text name without HTML.
     *
     * @param object $values Row data object.
     * @return string Student name (with link for web view, plain text for download).
     */
    public function col_student_lastname($values)
    {
        $student_name = $values->student_lastname . ', ' . $values->student_firstname;

        // If downloading, return plain text
        if ($this->is_downloading()) {
            return $student_name;
        }

        // Otherwise return HTML link
        $url = new moodle_url('/user/profile.php', ['id' => $values->userid]);
        return '<a href="' . $url->out() . '" target="_blank" rel="noopener noreferrer" aria-label="View profile for ' . htmlspecialchars($student_name, ENT_QUOTES) . ' (opens in new tab)">' . $student_name . '</a>';
    }

    /**
     * Set link for student profile
     */
    public function col_teacher_lastname($values)
    {

        return $values->teacher_firstname . ' ' . $values->teacher_lastname;
    }

    /**
     * Render the 'circumstance' column using a localized string.
     *
     * @param object $values Row data object.
     * @return string Localized circumstance string.
     */
    public function col_circumstance($values)
    {
        return get_string($values->circumstance, 'local_absence_request');
    }

    /**
     * Render the 'starttime' column as a formatted date.
     *
     * @param object $values Row data object.
     * @return string Formatted start date.
     */
    public function col_starttime($values)
    {
        return date('l F d, Y', $values->starttime);
    }

    /**
     * Render the 'endtime' column as a formatted date.
     *
     * @param object $values Row data object.
     * @return string Formatted end date.
     */
    public function col_endtime($values)
    {
        return date('l F d, Y', $values->endtime);
    }

    /**
     * Render the 'duration' column using helper::calculate_days.
     *
     * @param object $values Row data object.
     * @return string Duration in days with proper pluralization.
     */
    public function col_duration($values)
    {
        $days = \local_absence_request\helper::calculate_days($values->starttime, $values->endtime);
        return $days . ' ' . ($days == 1 ? get_string('day', 'core') : get_string('days', 'core'));
    }

    /**
     * Render the 'timecreated' column as a formatted date.
     *
     * @param object $values Row data object.
     * @return string Formatted creation date.
     */
    public function col_timecreated($values)
    {
        return userdate($values->timecreated);
    }

    /**
     * Render the 'acknowledged' column with clickable checkmark or X.
     * When downloading, return Yes/No text instead of HTML.
     *
     * @param object $values Row data object.
     * @return string HTML for clickable acknowledged status or Yes/No text.
     */
    public function col_acknowledged($values)
    {
        // If downloading, return plain text Yes/No
        if ($this->is_downloading()) {
            return $values->acknowledged == 1 ? 'Yes' : 'No';
        }

        if ($values->acknowledged == 1) {
            // Green checkmark for acknowledged
            return '<i 
            class="fa fa-check local-absence-request-acknowledge" 
            data-id="' . $values->id . '" 
            style="color: green; cursor: pointer; font-size: 1.2em;" 
            title="Acknowledged - Click to toggle"></i>';
        } else {
            // Red X for not acknowledged
            return '<i 
            class="fa fa-times local-absence-request-acknowledge" 
            data-id="' . $values->id . '" 
            style="color: red; cursor: pointer; font-size: 1.2em;" 
            title="Not acknowledged - Click to toggle"></i>';
        }
    }

    /**
     * Render the checkbox column for bulk actions.
     * When downloading, return empty string to exclude from export.
     *
     * @param object $values Row data object.
     * @return string HTML for the checkbox input or empty string when downloading.
     */
    public function col_checkbox($values)
    {
        // Don't include checkbox in downloads
        if ($this->is_downloading()) {
            return '';
        }

        // Return a checkbox input for the row, checked if acknowledged
        $checked = $values->acknowledged ? 'checked' : '';
        return '<input type="checkbox" class="absence-checkbox" data-id="' . $values->id . '" ' . $checked . '>';
    }
}
