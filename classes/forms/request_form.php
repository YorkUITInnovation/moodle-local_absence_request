<?php
namespace local_absence_request\forms;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

use local_absence_request\helper;

class request_form extends \moodleform {
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
        $mform->addRule('starttime', null, 'required', null, 'client');
        $mform->addElement('date_selector', 'endtime', get_string('absence_end', 'local_absence_request'));
        $mform->addRule('endtime', null, 'required', null, 'client');
        $mform->addElement('hidden', 'userid', 0);
        $mform->setType('userid', PARAM_INT);
        $this->add_action_buttons(true, get_string('submit_request', 'local_absence_request'));
    }

    /**
     * Custom validation for the form.
     *
     * @param array $data The submitted form data.
     * @param array $files The submitted files (not used here).
     * @return array An array of errors, empty if no errors.
     */
    public function validation($data, $files) {
        global $DB, $USER;
        $errors = parent::validation($data, $files);

        // End date must not be more than 7 days after start date.
        if (!empty($data['starttime']) && !empty($data['endtime'])) {
            $start = $data['starttime'];
            $end = $data['endtime'];
            if ($end > $start + 7 * 24 * 60 * 60) {
                $errors['endtime'] = get_string('error_max_7_days', 'local_absence_request');
            }
        }

        // End date cannot be before start date
        if (!empty($data['starttime']) && !empty($data['endtime'])) {
            $start = $data['starttime'];
            $end = $data['endtime'];
            if ($end < $start) {
                $errors['endtime'] = get_string('error_end_before_start', 'local_absence_request');
            }
        }

        // The start and end dates must be within the current academic year and term period.
        $acadyear = helper::get_acad_year();
        $currentperiod = helper::get_current_period();
        if (!empty($data['starttime']) && !empty($data['endtime'])) {
            $start = $data['starttime'];
            $end = $data['endtime'];
            if (date('Y', $start) != $acadyear || date('Y', $end) != $acadyear) {
                $errors['starttime'] = get_string('error_academic_year', 'local_absence_request');
                $errors['endtime'] = get_string('error_academic_year', 'local_absence_request');
            }
            if (helper::get_term_period($start) != $currentperiod || helper::get_term_period($end) != $currentperiod) {
                $errors['starttime'] = get_string('error_term_period', 'local_absence_request');
                $errors['endtime'] = get_string('error_term_period', 'local_absence_request');
            }
        }

        // Check to see if the user has submitted a request within the curretn start and end dates.
        $userid = $USER->id;
        $sql = "SELECT id FROM {local_absence_request} 
                WHERE userid = ?
                AND starttime >= ? 
                AND endtime <= ?";
        $params = [
            $userid,
            $data['starttime'],
            $data['endtime']
        ];

        $requests = $DB->get_records_sql($sql, $params);

        if ($requests) {
            $errors['starttime'] = get_string('error_already_submitted_for_selected_dates', 'local_absence_request');
            $errors['endtime'] = get_string('error_already_submitted_for_selected_dates', 'local_absence_request');
        }

        return $errors;
    }
}
