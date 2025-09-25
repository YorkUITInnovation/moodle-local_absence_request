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

    /**
     * Constructor for the absence_requests_table.
     * Defines columns and headers for the table.
     *
     * @param string $uniqueid Unique identifier for the table instance.
     */
    public function __construct($uniqueid)
    {
        parent::__construct($uniqueid);

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
            'course_fullname',
            'teacher_lastname',
            'timecreated'
        ];

        // Define the base column headers (localized strings).
        $headers = [
            get_string('student', 'local_absence_request'),
            get_string('sisid', 'local_absence_request'),
            get_string('circumstance', 'local_absence_request'),
            get_string('absence_start', 'local_absence_request'),
            get_string('absence_end', 'local_absence_request'),
            get_string('duration', 'local_absence_request'),
            get_string('course', 'local_absence_request'),
            // These columns display the course director's first and last name. The language string keys remain 'teacher_firstname' and 'teacher_lastname' for compatibility.
            get_string('teacher', 'local_absence_request'),
            get_string('submitted', 'local_absence_request')
        ];

        // Add acknowledged column only if enabled
        if ($acknowledge_enabled) {
            $columns[] = 'acknowledged';
            $headers[] = get_string('acknowledged', 'local_absence_request');
        }

        $this->define_columns($columns);
        $this->define_headers($headers);
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

        // Fix ambiguous column references by replacing bare column names with aliased versions
        if (!empty($sort)) {
            // Map problematic column names to their proper aliases
            $column_mappings = [
                'firstname' => 'us.firstname',
                'lastname' => 'us.lastname',
                'student_lastname' => 'us.lastname',
                'student_firstname' => 'us.firstname',
                'teacher_lastname' => 'ut.lastname',
                'teacher_firstname' => 'ut.firstname'
            ];

            foreach ($column_mappings as $column => $replacement) {
                // Replace column names that are not already prefixed with a table alias
                $sort = preg_replace('/\b' . preg_quote($column) . '\b(?!\s*\.)/', $replacement, $sort);
            }
        }

        // Always append starttime and endtime to the sort order using proper field references
        $additional_sort = 'ar.starttime ASC, ar.endtime ASC';

        if (empty($sort)) {
            // If no sort is specified, use just the additional sort
            return $additional_sort;
        } else {
            // If there's already a sort, append the additional sort
            return $sort . ', ' . $additional_sort;
        }
    }

    /**
     * Override define_baseurl to preserve starttime and endtime parameters in sort URLs.
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
     * Set link for student profile
     */
    public function col_student_lastname($values)
    {
        $url = new moodle_url('/user/profile.php', ['id' => $values->userid]);
        return '<a href="' . $url->out() . '">' . $values->student_lastname . ', ' . $values->student_firstname . '</a>';
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
     *
     * @param object $values Row data object.
     * @return string HTML for clickable acknowledged status.
     */
    public function col_acknowledged($values)
    {
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
}
