<?php

require_once($CFG->libdir . "/externallib.php");

class local_absence_request_teacher extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function acknowledge_parameters() {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'ID from local_absence_req_teacher table')
            )
        );
    }

    /**
     * Toggle the acknowledged field for a teacher absence request
     * @param int $id The ID from local_absence_req_teacher table
     * @return array
     */
    public static function acknowledge($id) {
        global $DB;

        // Validate parameters
        $params = self::validate_parameters(self::acknowledge_parameters(), array('id' => $id));
        $id = $params['id'];

        // Validate context
        $context = context_system::instance();
        self::validate_context($context);

        // Check if record exists
        $record = $DB->get_record('local_absence_req_teacher', array('id' => $id));
        if (!$record) {
            throw new invalid_parameter_exception('Record not found');
        }

        // Toggle the acknowledged field (0 to 1, 1 to 0)
        $newvalue = $record->acknowledged ? 0 : 1;

        // Update the record
        $DB->set_field('local_absence_req_teacher', 'acknowledged', $newvalue, array('id' => $id));

        return array(
            'id' => $id,
            'acknowledged' => $newvalue,
            'success' => true
        );
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function acknowledge_returns() {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'Record ID'),
                'acknowledged' => new external_value(PARAM_INT, 'New acknowledged value (0 or 1)'),
                'success' => new external_value(PARAM_BOOL, 'Whether the operation was successful')
            )
        );
    }
}
