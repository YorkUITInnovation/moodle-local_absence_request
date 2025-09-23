<?php
/**
 * Acknowledge absence request page
 *
 * This page handles acknowledgment of absence requests by teachers.
 * It validates the user and updates the acknowledged status.
 */

require_once('../../config.php');
require_once('classes/helper.php');

use local_absence_request\helper;

global $DB, $USER;

// Check if the plugin is enabled
if (!get_config('local_absence_request', 'enabled')) {
    redirect(new moodle_url('/my/'));
}

// Get encrypted token parameter
$token = required_param('token', PARAM_TEXT);

require_login();

// Decrypt and validate parameters
$decrypted_params = helper::decrypt_params($token);
if ($decrypted_params === false) {
    $returnurl = new moodle_url('/my/');
    redirect($returnurl, 'Invalid or corrupted token', null, \core\output\notification::NOTIFY_ERROR);
}

$validated_params = helper::validate_acknowledge_params($decrypted_params);
if ($validated_params === false) {
    $returnurl = new moodle_url('/my/');
    redirect($returnurl, 'Invalid parameters in token', null, \core\output\notification::NOTIFY_ERROR);
}

// Extract validated parameters
$id = $validated_params['id']; // Record ID from local_absence_req_teacher table
$u = $validated_params['u'];   // User ID
$c = $validated_params['c'];   // Course ID

// Check if the user is the same as the logged in user
if ($u != $USER->id) {
    $returnurl = new moodle_url('/my/');
    redirect($returnurl, get_string('nopermission', 'local_absence_request'), null, \core\output\notification::NOTIFY_ERROR);
}

// Check if record exists
$record = $DB->get_record('local_absence_req_teacher', array('id' => $id));
if (!$record) {
    $returnurl = new moodle_url('/my/');
    redirect($returnurl, 'Record not found', null, \core\output\notification::NOTIFY_ERROR);
}

// Validate that the user ID matches the record's user ID
if ($record->userid != $u) {
    $returnurl = new moodle_url('/my/');
    redirect($returnurl, get_string('nopermission', 'local_absence_request'), null, \core\output\notification::NOTIFY_ERROR);
}

// Toggle the acknowledged field (0 to 1, 1 to 0)
$newvalue = $record->acknowledged ? 0 : 1;

// Update the record
$DB->set_field('local_absence_req_teacher', 'acknowledged', $newvalue, array('id' => $id));

// Redirect back to teacher view with success message
$returnurl = new moodle_url('/my/');
redirect($returnurl, 'Acknowledgment status updated successfully', null, \core\output\notification::NOTIFY_SUCCESS);
