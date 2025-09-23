<?php

namespace local_absence_request;

use core\event\role_assigned;
use FastRoute\RouteParser\Std;
use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;

/**
 * Helper class for the local_absence_request plugin.
 * Provides utility functions for academic year, period, eligibility, and reporting.
 */
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
        global $CFG;
        if (!empty($CFG->local_absence_request_academic_year)) {
            return $CFG->local_absence_request_academic_year;
        }
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
     * Filters the given courses to return only those that match the current academic year and period.
     *
     * @param array $courses An array of course objects, each expected to have an 'idnumber' property.
     * @return array Filtered array of courses that belong to the current academic year and period.
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
     * Returns the current period based on the current month or a configured value.
     * The periods are defined as follows:
     * - Winter: January to April
     * - Summer: May to August
     * - Fall: September to December
     *
     * @return int The current period constant
     */
    public static function get_current_period()
    {
        // For testing purposes we can use a configured period.
        $use_period = get_config('local_absence_request', 'use_period');
        if ($use_period != 'no') {
            // If use_period is set, return the configured period.
            switch ($use_period) {
                case 'F':
                    return self::PERIOD_FALL;
                case 'W':
                    return self::PERIOD_WINTER;
                case 'S':
                    return self::PERIOD_SUMMER;
                case 'Y':
                    return self::PERIOD_WINTER;
            }
        }

        // Get month from the current time.
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
     * Returns the term period based on the start date timestamp.
     * @param int $start Unix timestamp for the start date.
     * @return string|null The period constant or null if not found.
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
     * Eligibility: undergraduate (fieldid=1, data contains 'undergraduate'), not Osgoode (fieldid=2, data='LW').
     * Also checks if the user has exceeded the max requests per term.
     * @param int $userid
     * @return \stdClass
     * @throws \dml_exception
     */
    public static function is_eligible($userid)
    {
        global $DB;
        $eligible = false;
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
    public static function get_report_sql()
    {
        $params = new \stdClass();
        $params->fields = 'art.id,
           art.acknowledged,
           ar.faculty,
           ar.circumstance,
           ar.starttime,
           ar.endtime,
           ar.acadyear,
           ar.termperiod,
           ar.timecreated,
           ar.userid,
           us.firstname As student_firstname,
           us.lastname As student_lastname,
           us.idnumber As sisid,
           us.email As student_email,
           c.fullname As course_fullname,
           c.shortname As course_shortname,
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

    /**
     * Calculates the number of days between start and end dates.
     * If start date equals end date, it counts as one day.
     *
     * @param int $start_timestamp Unix timestamp for the start date
     * @param int $end_timestamp Unix timestamp for the end date
     * @return int Number of days
     */
    public static function calculate_days($start_timestamp, $end_timestamp)
    {
        // Convert timestamps to dates (removing time component)
        $start_date = new \DateTime(date('Y-m-d', $start_timestamp));
        $end_date = new \DateTime(date('Y-m-d', $end_timestamp));

        // Calculate the difference
        $diff = $start_date->diff($end_date);
        $days = $diff->days;

        // If dates are equal or difference is 0, return 1 day
        // Otherwise return the difference + 1 (inclusive of both start and end dates)
        return ($days == 0) ? 1 : $days + 1;
    }

    /**
     * Returns an array of URLs for the plugin based on a query string.
     * @param string $query_string
     * @return string
     */
    public static function return_urls($query_string): string
    {
        $urls = [
            'sb' => 'https://schulich.instructure.com/'
        ];

        // Return value in the $urls array based on the query string.
        return $urls[strtolower($query_string)];
    }

    /**
     * Returns an array of faculty information with title and value pairs.
     * The value is the faculty abbreviation and the title is the full faculty name.
     *
     * @param string $selected_faculty The currently selected faculty abbreviation
     * @return array Array of objects with 'title', 'value', and 'selected' properties
     */
    public static function get_faculties($selected_faculty = '')
    {
        $faculties = [
            (object)['title' => 'Faculty of Liberal Arts & Professional Studies', 'value' => 'AP'],
            (object)['title' => 'Faculty of Education', 'value' => 'ED'],
            (object)['title' => 'Faculty of Environmental & Urban Change', 'value' => 'EU'],
            (object)['title' => 'School of the Arts, Media, Performance & Design', 'value' => 'FA'],
            (object)['title' => 'Glendon College / Collège universitaire Glendon', 'value' => 'GL'],
            (object)['title' => 'Faculty of Graduate Studies', 'value' => 'GS'],
            (object)['title' => 'Faculty of Health', 'value' => 'HH'],
            (object)['title' => 'Lassonde School of Engineering', 'value' => 'LE'],
            (object)['title' => 'Osgoode Hall Law School', 'value' => 'LW'],
            (object)['title' => 'Schulich School of Business', 'value' => 'SB'],
            (object)['title' => 'Faculty of Science', 'value' => 'SC'],
        ];

        // Add selected property to each faculty
        foreach ($faculties as $faculty) {
            $faculty->selected = ($faculty->value === $selected_faculty);
        }

        return $faculties;
    }

    /**
     * Get encryption key from Moodle configuration
     * Uses the site's dataroot and password salt for key generation
     *
     * @return string The encryption key
     */
    private static function get_encryption_key()
    {
        global $CFG;

        // Get custom password salt from plugin settings, fallback to Moodle's default
        $custom_salt = get_config('local_absence_request', 'passwordsaltmain');
        $password_salt = !empty($custom_salt) ? $custom_salt : $CFG->passwordsaltmain;

        // Use a combination of site-specific values for key generation
        $key_material = $CFG->dataroot . $password_salt . 'absence_request_secure_params';
        return hash('sha256', $key_material);
    }

    /**
     * Encrypt parameters for secure URL transmission
     *
     * @param array $params Array of parameters to encrypt (e.g., ['id' => 123, 'u' => 456, 'c' => 789])
     * @return string Base64 encoded encrypted string
     */
    public static function encrypt_params(array $params)
    {
        $key = self::get_encryption_key();
        $data = json_encode($params);

        // Generate a random IV
        $iv = random_bytes(16);

        // Encrypt the data
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        // Combine IV and encrypted data
        $result = $iv . $encrypted;

        // Return base64 encoded for URL safety
        return base64_encode($result);
    }

    /**
     * Decrypt parameters from secure URL
     *
     * @param string $encrypted_data Base64 encoded encrypted string
     * @return array|false Decrypted parameters array or false on failure
     */
    public static function decrypt_params($encrypted_data)
    {
        try {
            $key = self::get_encryption_key();

            // Decode from base64
            $data = base64_decode($encrypted_data);
            if ($data === false) {
                return false;
            }

            // Extract IV (first 16 bytes) and encrypted data
            $iv = substr($data, 0, 16);
            $encrypted = substr($data, 16);

            // Decrypt
            $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
            if ($decrypted === false) {
                return false;
            }

            // Decode JSON
            $params = json_decode($decrypted, true);
            if ($params === null) {
                return false;
            }

            return $params;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Validate decrypted parameters for acknowledge.php
     *
     * @param array $params Decrypted parameters
     * @return array|false Validated parameters or false on failure
     */
    public static function validate_acknowledge_params($params)
    {
        // Check required parameters exist and are integers
        if (!isset($params['id']) || !isset($params['u']) || !isset($params['c'])) {
            return false;
        }

        if (!is_numeric($params['id']) || !is_numeric($params['u']) || !is_numeric($params['c'])) {
            return false;
        }

        // Convert to integers
        return [
            'id' => (int)$params['id'],
            'u' => (int)$params['u'],
            'c' => (int)$params['c']
        ];
    }
}
