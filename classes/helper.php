<?php

namespace local_absence_request;

use core\event\role_assigned;
use FastRoute\RouteParser\Std;
use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;

class helper
{

    const PERIOD_FALL = 'F';
    const PERIOD_WINTER = 'W';
    const PERIOD_SUMMER = 'S';

    /**
     * Returns the current academic year based on the current month.
     * The academic year is defined as follows:
     * - January to August: Previous year
     * - September to December: Current year
     *
     * @return int The current academic year
     */
    public static function get_acad_year()
    {
        $month = date('n', time());
        switch ($month) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
                $acad_year = (date('Y', time()) - 1);
                break;
            case 9:
            case 10:
            case 11:
            case 12:
                $acad_year = date('Y', time());
                break;
        }
        return $acad_year;
    }

    /**
     * Filters the given courses to return only those that match the current academic year.
     *
     * @param array $courses An array of course objects, each expected to have an 'idnumber' property.
     * @return array Filtered array of courses that belong to the current academic year.
     */
    public static function get_courses_in_acadyear($courses)
    {
        $year_to_process = self::get_acad_year();
        $data = array();
        $i = 0;
        foreach ($courses as $course) {
            if (isset($course->idnumber)) {
                $idnumber = explode('_', $course->idnumber);
                $year_from_idnumber = $idnumber[0];
                $is_valid_year = is_numeric($year_from_idnumber);
                // Only process course if the term is identified.
                if (isset($idnumber[3])) {
                    switch ($idnumber[3]) {
                        case 'F':
                            $term_period = self::PERIOD_FALL;
                            break;
                        case 'W':
                            $term_period = self::PERIOD_WINTER;
                            break;
                        case 'Y':
                            if (self::get_current_period() == self::PERIOD_FALL) {
                                $term_period = self::PERIOD_FALL;
                            } else if (self::get_current_period() == self::PERIOD_WINTER) {
                                $term_period = self::PERIOD_WINTER;
                            } else {
                                $term_period = -1; // Not within the fall or winter term.
                            }
                            break;
                        default:
                            $term_period = self::PERIOD_SUMMER; // Default to Summer if not specified.
                    }
                    if ($is_valid_year &&
                        $year_from_idnumber == $year_to_process &&
                        $term_period == self::get_current_period()
                    ) {
                        $data[$i] = $course;
                        $i++;
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Returns the current period based on the current month.
     * The periods are defined as follows:
     * - Winter: January to April
     * - Summer: May to August
     * - Fall: September to December
     * - Winter Year: January to April of the next year
     *
     * @return int The current period constant
     */
    public static function get_current_period()
    {
        return self::PERIOD_FALL;
        $month = date('n', time());
        switch ($month) {
            case 1:
            case 2:
            case 3:
            case 4:
                $period = self::PERIOD_WINTER;
                break;
            case 9:
            case 10:
            case 11:
            case 12:
                $period = self::PERIOD_FALL;
                break;
            default:
                $period = self::PERIOD_SUMMER;
        }
        return $period;
    }

    /**
     * Returns the term period based on the start date.
     * @param $start
     * @return string|null
     */
    public static function get_term_period($start)
    {
        $month = date('n', $start);
        switch ($month) {
            case 1:
            case 2:
            case 3:
            case 4:
                return self::PERIOD_WINTER;
            case 5:
            case 6:
            case 7:
            case 8:
                return self::PERIOD_SUMMER;
            case 9:
            case 10:
            case 11:
            case 12:
                return self::PERIOD_FALL;
        }
        return null; // Should not reach here.
    }

    /**
     * Checks if a user is eligible to submit an absence request.
     * undergraduate (fieldid=1, data contains 'undergraduate'), not Osgoode (fieldid=2, data='LW').
     * @param $userid
     * @return \stdClass
     * @throws \dml_exception
     */
    public static function is_eligible($userid) {
        global $DB;
        $eligiblity = new \stdClass();
        // Check eligibility: undergraduate (fieldid=1, data contains 'undergraduate'), not Osgoode (fieldid=2, data='LW').
        $undergrad = $DB->record_exists('user_info_data', [
                'userid' => $userid,
                'fieldid' => 1
            ]) && $DB->get_field_select('user_info_data', 'data', 'userid = ? AND fieldid = 1', [$userid], IGNORE_MISSING) !== false &&
            strpos(strtolower($DB->get_field_select('user_info_data', 'data', 'userid = ? AND fieldid = 1', [$userid], IGNORE_MISSING)), 'undergraduate') !== false;

        //Check if the user is Osgoode.
        $sql = "SELECT 1
          FROM {user_info_data}
         WHERE userid = :userid
           AND fieldid = 2
           AND " . $DB->sql_compare_text('data') . " = :lw";
        $params = ['userid' => $userid, 'lw' => 'LW'];
        $osgoode = $DB->record_exists_sql($sql, $params);

        // Initialize eligibility status.
        if ($undergrad && !$osgoode) {
            $eligible = true;
            // Check to see how many requests the user has made this term.
            $term_params = [
                'userid' => $userid,
                'acadyear' => helper::get_acad_year(),
                'termperiod' => helper::get_current_period()
            ];
            $number_of_request = $DB->count_records('local_absence_request', $term_params);
            $max_requests = get_config('local_absence_request', 'requests_per_term');
            $max_requests_exceeded = false;
            if ($number_of_request >= $max_requests) {
                $eligible = false;
                $max_requests_exceeded = true;
            }
        }
        // Set eligibility status.
        $eligiblity->eligible = $eligible;
        $eligiblity->osgoode = $osgoode ?? false;
        $eligiblity->max_requests_exceeded = $max_requests_exceeded ?? false;

        return $eligiblity;
    }

    /**
     * Generates the SQL parameters for the absence request report.
     * @return \stdClass
     */
    public static function get_report_sql() {
        $params = new \stdClass();
        $params->fields = 'art.id,
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

        $params->from = ' {local_absence_req_teacher} art Inner Join
           {local_absence_req_course} arc On arc.id = art.absence_req_course_id Inner Join
           {course} c On c.id = arc.courseid Inner Join
           {user} ut On ut.id = art.userid Inner Join
           {local_absence_request} ar On ar.id = arc.absence_request_id Inner Join
           {user} us On us.id = ar.userid';

        return $params;
    }
}

