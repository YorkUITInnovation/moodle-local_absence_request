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
class absence_requests_table extends \table_sql {
    // Faculty filter (if needed for future extension)
    protected $faculty;

    /**
     * Constructor for the absence_requests_table.
     * Defines columns and headers for the table.
     *
     * @param string $uniqueid Unique identifier for the table instance.
     */
    public function __construct($uniqueid) {
        parent::__construct($uniqueid);

        // Define the columns to be displayed in the table.
        $this->define_columns([
            'student_firstname',
            'student_lastname',
            'sisid','circumstance',
            'starttime',
            'endtime',
            'course_id_number',
            'teacher_firstname',
            'teacher_lastname',
            'timecreated']
        );
        // Define the column headers (localized strings).
        $this->define_headers([
            get_string('student_firstname', 'local_absence_request'),
            get_string('student_lastname', 'local_absence_request'),
            get_string('sisid', 'local_absence_request'),
            get_string('type_of_circumstance', 'local_absence_request'),
            get_string('absence_start', 'local_absence_request'),
            get_string('absence_end', 'local_absence_request'),
            get_string('course', 'local_absence_request'),
            // These columns display the course director's first and last name. The language string keys remain 'teacher_firstname' and 'teacher_lastname' for compatibility.
            get_string('teacher_firstname', 'local_absence_request'),
            get_string('teacher_lastname', 'local_absence_request'),
            get_string('timecreated', 'moodle'),
        ]);
    }

    /**
     * Render the 'circumstance' column using a localized string.
     *
     * @param object $values Row data object.
     * @return string Localized circumstance string.
     */
    public function col_circumstance($values) {
        return get_string($values->circumstance, 'local_absence_request');
    }

    /**
     * Render the 'starttime' column as a formatted date.
     *
     * @param object $values Row data object.
     * @return string Formatted start date.
     */
    public function col_starttime($values) {
        return userdate($values->starttime);
    }

    /**
     * Render the 'endtime' column as a formatted date.
     *
     * @param object $values Row data object.
     * @return string Formatted end date.
     */
    public function col_endtime($values) {
        return userdate($values->endtime);
    }

    /**
     * Render the 'timecreated' column as a formatted date.
     *
     * @param object $values Row data object.
     * @return string Formatted creation date.
     */
    public function col_timecreated($values) {
        return userdate($values->timecreated);
    }
}
