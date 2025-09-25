<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Teacher web services for absence request plugin.
 *
 * @package    local_absence_request
 * @copyright  2025 Your Institution
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_absence_request\external;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;
use external_multiple_structure;

/**
 * Teacher web services class.
 */
class teacher_ws extends external_api {

    /**
     * Returns description of method parameters for acknowledge_request.
     *
     * @return external_function_parameters
     */
    public static function acknowledge_request_parameters() {
        return new external_function_parameters([
            'id' => new external_value(PARAM_INT, 'Teacher record ID'),
            'acknowledged' => new external_value(PARAM_INT, 'Acknowledgment status (0 or 1)')
        ]);
    }

    /**
     * Acknowledge or unacknowledge a single absence request.
     *
     * @param int $id Teacher record ID
     * @param int $acknowledged Acknowledgment status
     * @return array Result of the operation
     */
    public static function acknowledge_request($id, $acknowledged) {
        global $DB, $USER;

        // Validate parameters
        $params = self::validate_parameters(self::acknowledge_request_parameters(), [
            'id' => $id,
            'acknowledged' => $acknowledged
        ]);

        // Validate context
        $context = \context_system::instance();
        self::validate_context($context);

        try {
            // Get the teacher record to verify it belongs to the current user
            $teacher_record = $DB->get_record('local_absence_req_teacher', ['id' => $params['id']], '*', MUST_EXIST);

            // Check if the current user is the teacher for this record
            if ($teacher_record->userid != $USER->id) {
                return [
                    'success' => false,
                    'message' => get_string('nopermission', 'local_absence_request')
                ];
            }

            // Update the acknowledgment status
            $update_data = new \stdClass();
            $update_data->id = $params['id'];
            $update_data->acknowledged = $params['acknowledged'];

            $result = $DB->update_record('local_absence_req_teacher', $update_data);

            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Acknowledgment updated successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to update acknowledgment'
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Returns description of method result value for acknowledge_request.
     *
     * @return external_single_structure
     */
    public static function acknowledge_request_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Whether the operation was successful'),
            'message' => new external_value(PARAM_TEXT, 'Result message')
        ]);
    }

    /**
     * Returns description of method parameters for bulk_acknowledge.
     *
     * @return external_function_parameters
     */
    public static function bulk_acknowledge_parameters() {
        return new external_function_parameters([
            'ids' => new external_multiple_structure(
                new external_value(PARAM_INT, 'Teacher record ID')
            )
        ]);
    }

    /**
     * Bulk acknowledge multiple absence requests.
     *
     * @param array $ids Array of teacher record IDs
     * @return array Result of the operation
     */
    public static function bulk_acknowledge($ids) {
        global $DB, $USER;

        // Validate parameters
        $params = self::validate_parameters(self::bulk_acknowledge_parameters(), [
            'ids' => $ids
        ]);

        // Validate context
        $context = \context_system::instance();
        self::validate_context($context);

        if (empty($params['ids'])) {
            return [
                'success' => false,
                'message' => 'No IDs provided'
            ];
        }

        try {
            $updated_count = 0;
            $transaction = $DB->start_delegated_transaction();

            foreach ($params['ids'] as $id) {
                // Get the teacher record to verify it belongs to the current user
                $teacher_record = $DB->get_record('local_absence_req_teacher', ['id' => $id]);

                if (!$teacher_record) {
                    continue; // Skip invalid IDs
                }

                // Check if the current user is the teacher for this record
                if ($teacher_record->userid != $USER->id) {
                    continue; // Skip records that don't belong to this user
                }

                // Update the acknowledgment status
                $update_data = new \stdClass();
                $update_data->id = $id;
                $update_data->acknowledged = 1; // Always acknowledge (set to 1)

                if ($DB->update_record('local_absence_req_teacher', $update_data)) {
                    $updated_count++;
                }
            }

            $transaction->allow_commit();

            return [
                'success' => true,
                'message' => get_string('bulk_acknowledge_success', 'local_absence_request', $updated_count),
                'count' => $updated_count
            ];

        } catch (\Exception $e) {
            $transaction->rollback($e);
            return [
                'success' => false,
                'message' => get_string('bulk_acknowledge_error', 'local_absence_request')
            ];
        }
    }

    /**
     * Returns description of method result value for bulk_acknowledge.
     *
     * @return external_single_structure
     */
    public static function bulk_acknowledge_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Whether the operation was successful'),
            'message' => new external_value(PARAM_TEXT, 'Result message'),
            'count' => new external_value(PARAM_INT, 'Number of records updated', VALUE_OPTIONAL)
        ]);
    }
}
