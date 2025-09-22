<?php
/**
 * Acknowledge absence request page
 *
 * This page handles acknowledgment of absence requests by teachers.
 * It validates the user and updates the acknowledged status.
 */

require_once('../../config.php');


// Get query parameters
$id = required_param('id', PARAM_INT); // Record ID from local_absence_req_teacher table
$u = required_param('u', PARAM_INT);   // User ID
$c = required_param('c', PARAM_INT);   // Course ID


// Check if record exists
$record = $DB->get_record('local_absence_req_teacher', array('id' => $id));
if (!$record) {
    core::('Record not found');
}

// Validate that the user ID matches the record's user ID
if ($record->userid != $u) {
    print_error('nopermission', 'local_absence_request');
}

// Toggle the acknowledged field (0 to 1, 1 to 0)
$newvalue = $record->acknowledged ? 0 : 1;

// Update the record
$DB->set_field('local_absence_req_teacher', 'acknowledged', $newvalue, array('id' => $id));

// Redirect back to teacher view with success message
$returnurl = new moodle_url('/local/absence_request/teacher_view.php');
redirect($returnurl, 'Acknowledgment status updated successfully', null, \core\output\notification::NOTIFY_SUCCESS);
