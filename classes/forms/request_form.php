<?php
namespace local_absence_request\forms;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

use local_absence_request\helper;

/**
 * Form class for submitting an absence request.
 * Defines the form fields, validation, and rules for the absence request process.
 */
class request_form extends \moodleform {
    /**
     * Defines the form fields and their rules.
     */
    public function definition() {
        $formdata = $this->_customdata['formdata'];

        $mform = $this->_form;

        $mform->addElement('hidden', 'courseid', $formdata->courseid);
        $mform->setType('courseid', PARAM_INT);
        $mform->addElement('hidden', 'returnurl', $formdata->returnurl);
        $mform->setType('returnurl', PARAM_URL);
        $mform->addElement('select', 'circumstance', get_string('type_of_circumstance', 'local_absence_request'), [
            'short_term_health' => get_string('short_term_health', 'local_absence_request'),
            'bereavement' => get_string('bereavement', 'local_absence_request'),
            'unforeseen' => get_string('unforeseen', 'local_absence_request'),
        ]);
        $mform->addRule('circumstance', null, 'required', null, 'client');

        $mform->addElement('date_selector', 'starttime', get_string('absence_start', 'local_absence_request'));
        $mform->addHelpButton('starttime', 'absence_start', 'local_absence_request');
        $mform->addRule('starttime', null, 'required', null, 'client');

        $mform->addElement('date_selector', 'endtime', get_string('absence_end_max_days', 'local_absence_request'));
        $mform->addRule('endtime', null, 'required', null, 'client');
        $mform->addHelpButton('endtime', 'absence_end', 'local_absence_request');

        $mform->addElement('hidden', 'userid', 0);
        $mform->setType('userid', PARAM_INT);
        $this->add_action_buttons(true, get_string('submit_request', 'local_absence_request'));
    }

    /**
     * Custom validation for the form.
     * Ensures dates are valid, within the academic year/term, and not duplicated.
     *
     * @param array $data The submitted form data.
     * @param array $files The submitted files (not used here).
     * @return array An array of errors, empty if no errors.
     */
    public function validation($data, $files) {
        global $DB, $USER;
        $errors = parent::validation($data, $files);

        // Normalize dates to midnight to ensure accurate day counting
        if (!empty($data['starttime']) && !empty($data['endtime'])) {
            $start_midnight = strtotime('midnight', $data['starttime']);
            $end_midnight = strtotime('midnight', $data['endtime']);

            // End date cannot be before start date
            if ($end_midnight < $start_midnight) {
                $errors['endtime'] = get_string('error_end_before_start', 'local_absence_request');
            }

            // Calculate days using the helper method with normalized timestamps
            $days = helper::calculate_days($start_midnight, $end_midnight);

            // End date must not be more than 7 days after start date
            if ($days > 7) {
                $errors['endtime'] = get_string('error_max_7_days', 'local_absence_request');
            }
        }

        // The start and end dates must be within the current academic year and term period.
        $acadyear = helper::get_acad_year();
        $currentperiod = helper::get_current_period();

        if (!empty($data['starttime']) && !empty($data['endtime'])) {
            $start_midnight = strtotime('midnight', $data['starttime']);
            $end_midnight = strtotime('midnight', $data['endtime']);

            // Must convert starttime and endtime to appropriate academic year
            $start_month = date('n', $start_midnight);
            switch ($start_month) {
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                case 6:
                case 7:
                case 8:
                    $start_acadyear = (date('Y', $start_midnight) - 1);
                    break;
                case 9:
                case 10:
                case 11:
                case 12:
                    $start_acadyear = date('Y', $start_midnight);
                    break;
            }

            // ensure dates are within the current academic year
            if ($start_acadyear != $acadyear) {
                $errors['starttime'] = get_string('error_academic_year', 'local_absence_request');
            }
            // ensure dates are within the current term period
            if (helper::get_term_period($start_midnight) != $currentperiod) {
                $errors['starttime'] = get_string('error_term_period', 'local_absence_request');
            }
        }

        // Check to see if the user has submitted a request within the current start and end dates.
        $userid = $USER->id;
        $sql = "SELECT id FROM {local_absence_request} 
                WHERE userid = ?
                AND starttime >= ? 
                AND endtime <= ?";
        $params = [
            $userid,
            strtotime('midnight', $data['starttime']),
            strtotime('midnight', $data['endtime'])
        ];

        $requests = $DB->get_records_sql($sql, $params);

        if ($requests) {
            $errors['starttime'] = get_string('error_already_submitted_for_selected_dates', 'local_absence_request');
            $errors['endtime'] = get_string('error_already_submitted_for_selected_dates', 'local_absence_request');
        }

        return $errors;
    }
}
