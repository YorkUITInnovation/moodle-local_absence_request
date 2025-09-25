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
 * Task to send teacher notifications for absence requests.
 *
 * @package    local_absence_request
 * @copyright  2025 Your Institution
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_absence_request\task;

defined('MOODLE_INTERNAL') || die();

use local_absence_request\notifications;

/**
 * Task to send teacher notifications for absence requests.
 *
 * @package    local_absence_request
 * @copyright  2025 Your Institution
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class send_teacher_notifications extends \core\task\scheduled_task {

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('task_send_teacher_notifications', 'local_absence_request');
    }

    /**
     * Execute the task.
     */
    public function execute() {
        global $DB;

        mtrace('Starting teacher notification task for absence requests...');

        // Get all teachers with pending notifications and count their unique absence requests
        $sql = "SELECT art.userid as teacher_userid,
                       COUNT(DISTINCT ar.id) as absence_count
                FROM {local_absence_req_teacher} art
                INNER JOIN {local_absence_req_course} arc ON art.absence_req_course_id = arc.id
                INNER JOIN {local_absence_request} ar ON arc.absence_request_id = ar.id
                WHERE art.emailsent = :emailsent
                GROUP BY art.userid";

        $params = ['emailsent' => 0];
        $teachers = $DB->get_records_sql($sql, $params);

        if (empty($teachers)) {
            mtrace('No pending teacher notifications found.');
            return;
        }

        mtrace('Found ' . count($teachers) . ' teacher(s) with pending notifications to process.');

        // Process each teacher and send one notification with their total absence count
        foreach ($teachers as $teacher) {
            mtrace('Processing teacher notification for user ID: ' . $teacher->teacher_userid .
                   ' with ' . $teacher->absence_count . ' unique absence request(s).');

            // Send notification to teacher with the count of unique absence requests
            $notification_result = notifications::notify_teacher($teacher->teacher_userid, $teacher->absence_count);

            if ($notification_result) {
                // Mark all teacher records as email sent for this teacher
                $update_sql = "UPDATE {local_absence_req_teacher} 
                              SET emailsent = 1 
                              WHERE userid = :teacherid AND emailsent = 0";

                $DB->execute($update_sql, ['teacherid' => $teacher->teacher_userid]);

                mtrace('Successfully sent notification and updated email status for teacher ID: ' . $teacher->teacher_userid);
            } else {
                mtrace('Failed to send notification for teacher ID: ' . $teacher->teacher_userid);
            }
        }

        mtrace('Teacher notification task completed successfully.');
    }
}
