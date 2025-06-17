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
 * Privacy Subsystem implementation for local_absence_request.
 *
 * @package    local_absence_request
 * @copyright  2025 Patrick Thibaudeau
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_absence_request\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\deletion_criteria;
use core_privacy\local\request\helper;
use core_privacy\local\request\writer;
use core_privacy\local\request\userlist;
use core_privacy\local\request\approved_userlist;

defined('MOODLE_INTERNAL') || die();

/**
 * Privacy Subsystem implementation for local_absence_request.
 *
 * @copyright  2025 Patrick Thibaudeau
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
    // This plugin stores personal data.
    \core_privacy\local\metadata\provider,

    // This plugin is a core_user_data_provider.
    \core_privacy\local\request\plugin\provider,

    // This plugin is capable of determining which users have data within it.
    \core_privacy\local\request\core_userlist_provider {

    /**
     * Return the fields which contain personal data.
     *
     * @param collection $collection a reference to the collection to use to store the metadata.
     * @return collection the updated collection of metadata items.
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table(
            'local_absence_request',
            [
                'userid' => 'privacy:metadata:local_absence_request:userid',
                'faculty' => 'privacy:metadata:local_absence_request:faculty',
                'circumstance' => 'privacy:metadata:local_absence_request:circumstance',
                'starttime' => 'privacy:metadata:local_absence_request:starttime',
                'endtime' => 'privacy:metadata:local_absence_request:endtime',
                'acadyear' => 'privacy:metadata:local_absence_request:acadyear',
                'termperiod' => 'privacy:metadata:local_absence_request:termperiod',
                'timecreated' => 'privacy:metadata:local_absence_request:timecreated',
            ],
            'privacy:metadata:local_absence_request'
        );

        $collection->add_database_table(
            'local_absence_req_teacher',
            [
                'userid' => 'privacy:metadata:local_absence_req_teacher:userid',
                'absence_req_course_id' => 'privacy:metadata:local_absence_req_teacher:absence_req_course_id',
                'timecreated' => 'privacy:metadata:local_absence_req_teacher:timecreated',
            ],
            'privacy:metadata:local_absence_req_teacher'
        );

        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid The user to search.
     * @return contextlist The contextlist containing the list of contexts used in this plugin.
     */
    public static function get_contexts_for_userid(int $userid): contextlist {
        $contextlist = new contextlist();

        // Add the system context for absences and teacher records.
        $sql = "SELECT c.id
                  FROM {context} c
                 WHERE c.contextlevel = :contextlevel
                   AND (EXISTS (SELECT 1 FROM {local_absence_request} ar
                               WHERE ar.userid = :userid1)
                        OR 
                        EXISTS (SELECT 1 FROM {local_absence_req_teacher} art
                               WHERE art.userid = :userid2))";

        $params = [
            'contextlevel' => CONTEXT_SYSTEM,
            'userid1' => $userid,
            'userid2' => $userid,
        ];

        $contextlist->add_from_sql($sql, $params);

        return $contextlist;
    }

    /**
     * Get the list of users who have data within a context.
     *
     * @param userlist $userlist The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {
        $context = $userlist->get_context();

        if (!$context instanceof \context_system) {
            return;
        }

        // Add users who have created absence requests.
        $sql = "SELECT userid FROM {local_absence_request}";
        $userlist->add_from_sql('userid', $sql, []);

        // Add teachers associated with absence requests.
        $sql = "SELECT userid FROM {local_absence_req_teacher}";
        $userlist->add_from_sql('userid', $sql, []);
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist->count())) {
            return;
        }

        $user = $contextlist->get_user();
        $userid = $user->id;
        $context = \context_system::instance();

        // Export absence requests.
        $absencerequests = $DB->get_records('local_absence_request', ['userid' => $userid]);
        if (!empty($absencerequests)) {
            foreach ($absencerequests as $absencerequest) {
                // Get associated courses for this absence request.
                $courses = $DB->get_records('local_absence_req_course', ['absence_request_id' => $absencerequest->id]);
                $absencerequest->courses = array_values($courses);

                // Add course names for readability.
                foreach ($absencerequest->courses as $key => $course) {
                    $courserecord = $DB->get_record('course', ['id' => $course->courseid], 'id, fullname, shortname');
                    if ($courserecord) {
                        $absencerequest->courses[$key]->coursename = $courserecord->fullname;
                        $absencerequest->courses[$key]->courseshortname = $courserecord->shortname;
                    }
                }
            }

            writer::with_context($context)->export_data(
                [get_string('privacy:path:absencerequests', 'local_absence_request')],
                (object) ['absencerequests' => array_values($absencerequests)]
            );
        }

        // Export teacher records.
        $teacherrecords = $DB->get_records_sql(
            "SELECT art.*, arc.courseid, arc.absence_request_id
               FROM {local_absence_req_teacher} art
               JOIN {local_absence_req_course} arc ON arc.id = art.absence_req_course_id
              WHERE art.userid = :userid",
            ['userid' => $userid]
        );

        if (!empty($teacherrecords)) {
            // Add course names for readability.
            foreach ($teacherrecords as $key => $record) {
                $courserecord = $DB->get_record('course', ['id' => $record->courseid], 'id, fullname, shortname');
                if ($courserecord) {
                    $teacherrecords[$key]->coursename = $courserecord->fullname;
                    $teacherrecords[$key]->courseshortname = $courserecord->shortname;
                }
            }

            writer::with_context($context)->export_data(
                [get_string('privacy:path:teacherrequests', 'local_absence_request')],
                (object) ['teacherrequests' => array_values($teacherrecords)]
            );
        }
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param \context $context The specific context to delete data for.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;

        if (!$context instanceof \context_system) {
            return;
        }

        // Delete all absence request related data.
        $DB->delete_records('local_absence_req_teacher');
        $DB->delete_records('local_absence_req_course');
        $DB->delete_records('local_absence_request');
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts and user information to delete information for.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist->count())) {
            return;
        }

        $userid = $contextlist->get_user()->id;
        $systemcontext = \context_system::instance();

        // Ensure the contexts contain the system context (which they should).
        if (!in_array($systemcontext->id, $contextlist->get_contextids())) {
            return;
        }

        // Delete teacher records for this user.
        $DB->delete_records('local_absence_req_teacher', ['userid' => $userid]);

        // Get all absence requests for this user.
        $absencerequests = $DB->get_records('local_absence_request', ['userid' => $userid]);

        // For each absence request, delete related records.
        foreach ($absencerequests as $absencerequest) {
            // Delete course records for each absence request.
            $DB->delete_records('local_absence_req_course', ['absence_request_id' => $absencerequest->id]);
        }

        // Finally, delete the absence requests.
        $DB->delete_records('local_absence_request', ['userid' => $userid]);
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param approved_userlist $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;

        $context = $userlist->get_context();

        if (!$context instanceof \context_system) {
            return;
        }

        $userids = $userlist->get_userids();

        // Delete teacher records for these users.
        list($usersql, $userparams) = $DB->get_in_or_equal($userids, SQL_PARAMS_NAMED);
        $DB->delete_records_select('local_absence_req_teacher', "userid $usersql", $userparams);

        // For each user, delete their absence requests and related records.
        foreach ($userids as $userid) {
            // Get all absence requests for this user.
            $absencerequests = $DB->get_records('local_absence_request', ['userid' => $userid]);

            // For each absence request, delete related records.
            foreach ($absencerequests as $absencerequest) {
                // Delete course records for each absence request.
                $DB->delete_records('local_absence_req_course', ['absence_request_id' => $absencerequest->id]);
            }

            // Delete the absence requests.
            $DB->delete_records('local_absence_request', ['userid' => $userid]);
        }
    }
}
