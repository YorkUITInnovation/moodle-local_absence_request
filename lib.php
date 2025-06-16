<?php
require_once(__DIR__ . '/../../config.php');

// Add link to the absence request plugin in the primary navigation
defined('MOODLE_INTERNAL') || die();

use local_absence_request\helper;

function local_absence_request_extend_navigation_frontpage(
    navigation_node $parentnode,
    stdClass        $course,
    context_course  $context
)
{
    global $USER;
    $eligibility = helper::is_eligible($USER->id);
    // Add the absence request link to the course navigation.
    if (!$eligibility->eligible) {
        // If the user is not eligible, do not add the link.
        return;
    }
    // Add the absence request link to the front page navigation.
    $parentnode->add(
        get_string('pluginname', 'local_absence_request'),
        new moodle_url('/local/absence_request/index.php'),
        navigation_node::TYPE_CUSTOM,
        null,
        'local_absence_request'
    );
}

function local_absence_request_extend_navigation_course(
    navigation_node $parentnode,
    stdClass        $course,
    context_course  $context
)
{
    global $USER;
    $eligibility = helper::is_eligible($USER->id);
    // Add the absence request link to the course navigation.
    if (!$eligibility->eligible) {
        // If the user is not eligible, do not add the link.
        return;
    }
    $parentnode->add(
        get_string('pluginname', 'local_absence_request'),
        new moodle_url('/local/absence_request/index.php?courseid=' . $course->id),
        navigation_node::TYPE_CUSTOM,
        null,
        'local_absence_request'
    );
}

