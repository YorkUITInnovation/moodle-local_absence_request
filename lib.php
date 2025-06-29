<?php
require_once(__DIR__ . '/../../config.php');

// Add link to the absence request plugin in the primary navigation
defined('MOODLE_INTERNAL') || die();

use local_absence_request\helper;

/**
 * Extends the frontpage navigation to include links for the absence request plugin.
 * Adds links for eligible users and those with faculty report permissions.
 *
 * @param navigation_node $parentnode
 * @param stdClass $course
 * @param context_course $context
 */
function local_absence_request_extend_navigation_frontpage(
    navigation_node $parentnode,
    stdClass        $course,
    context_course  $context
)
{
    global $USER;

    // If the user has capabilitey to view faculty report, add the link.
    if (has_capability('local/absence_request:view_faculty_report', $context)) {
        $parentnode->add(
            get_string('view_faculty_report', 'local_absence_request'),
            new moodle_url('/local/absence_request/view.php'),
            navigation_node::TYPE_CUSTOM,
            null,
            'local_absence_request_view_faculty_report'
        );
    }

    $eligibility = helper::is_eligible($USER->id);
    // Add the absence request link to the course navigation.
    if ($eligibility->osgoode) {
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

/**
 * Extends the course navigation to include links for the absence request plugin.
 * Adds links for eligible users and those with teacher report permissions.
 *
 * @param navigation_node $parentnode
 * @param stdClass $course
 * @param context_course $context
 */
function local_absence_request_extend_navigation_course(
    navigation_node $parentnode,
    stdClass        $course,
    context_course  $context
)
{
    global $USER;

    // Get the users role in this course. If editingteacher, add a link to the teacher_view.php page.
    if (has_capability('local/absence_request:view_teacher_report', $context)) {
        $parentnode->add(
            get_string('view_faculty_report', 'local_absence_request'),
            new moodle_url('/local/absence_request/teacher_view.php', ['courseid' => $course->id]),
            navigation_node::TYPE_CUSTOM,
            null,
            'local_absence_request_view_faculty_report'
        );
    }

    $eligibility = helper::is_eligible($USER->id);
    // Add the absence request link to the course navigation.
    if ($eligibility->osgoode) {
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
