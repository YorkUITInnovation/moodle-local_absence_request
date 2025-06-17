<?php
namespace local_absence_request\tables;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/tablelib.php');

use moodle_url;

class absence_requests_table extends \table_sql {
    protected $faculty;
    public function __construct($uniqueid) {
        parent::__construct($uniqueid);

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
        $this->define_headers([
            get_string('student_firstname', 'local_absence_request'),
            get_string('student_lastname', 'local_absence_request'),
            get_string('sisid', 'local_absence_request'),
            get_string('type_of_circumstance', 'local_absence_request'),
            get_string('absence_start', 'local_absence_request'),
            get_string('absence_end', 'local_absence_request'),
            get_string('course', 'local_absence_request'),
            get_string('teacher_firstname', 'local_absence_request'),
            get_string('teacher_lastname', 'local_absence_request'),
            get_string('timecreated', 'moodle'),
        ]);
    }

    public function col_circumstance($values) {
        return get_string($values->circumstance, 'local_absence_request');
    }

    public function col_starttime($values) {
        return userdate($values->starttime);
    }

    public function col_endtime($values) {
        return userdate($values->endtime);
    }

    public function col_timecreated($values) {
        return userdate($values->timecreated);
    }
}
