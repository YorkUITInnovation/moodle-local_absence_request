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
 * Upgrade script for the absence_request plugin
 *
 * @package    local_absence_request
 * @copyright  2025 Patrick Thibaudeau
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Function to upgrade local_absence_request
 *
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_local_absence_request_upgrade($oldversion)
{
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2025090700) {

        // Define field acknowledged to be added to local_absence_req_teacher.
        $table = new xmldb_table('local_absence_req_teacher');
        $field = new xmldb_field('acknowledged', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'userid');

        // Conditionally launch add field acknowledged.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define index userid_ackn_x (not unique) to be added to local_absence_req_teacher.
        $table = new xmldb_table('local_absence_req_teacher');
        $index = new xmldb_index('userid_ackn_x', XMLDB_INDEX_NOTUNIQUE, ['userid', 'acknowledged']);

        // Conditionally launch add index userid_ackn_x.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }
        // Absence_request savepoint reached.
        upgrade_plugin_savepoint(true, 2025090700, 'local', 'absence_request');
    }

    if ($oldversion < 2025092201) {

        // Define field emailsent to be added to local_absence_req_teacher.
        $table = new xmldb_table('local_absence_req_teacher');
        $field = new xmldb_field('emailsent', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'acknowledged');

        // Conditionally launch add field emailsent.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define index emailsent_idx (not unique) to be added to local_absence_req_teacher.
        $table = new xmldb_table('local_absence_req_teacher');
        $index = new xmldb_index('emailsent_idx', XMLDB_INDEX_NOTUNIQUE, ['emailsent']);


        // Conditionally launch add index emailsent_idx.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }


        // Define index user_emailsent_idx (not unique) to be added to local_absence_req_teacher.
        $table = new xmldb_table('local_absence_req_teacher');
        $index = new xmldb_index('user_emailsent_idx', XMLDB_INDEX_NOTUNIQUE, ['userid', 'emailsent']);

        // Conditionally launch add index user_emailsent_idx.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Update all existing records to have emailsent = 1.
        $sql = "UPDATE {local_absence_req_teacher} SET emailsent = 1";
        $DB->execute($sql);

        // Absence_request savepoint reached.
        upgrade_plugin_savepoint(true, 2025092201, 'local', 'absence_request');
    }

    return true;
}
