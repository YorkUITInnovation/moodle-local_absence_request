<?php
// Language strings for absence_request plugin.
$string['absence_end'] = 'End Date';
$string['absence_end_help'] = 'The last day of your absence. If your absence is only one day, set the end date to be the same as the start date.';
$string['absence_end_max_days'] = 'End Date (max 7 days from start date)';
$string['absence_request'] = 'Report an Absence';
$string['absence_start'] = 'Start Date';
$string['absence_start_help'] = 'The first day of your absence. Note that absences cannot be backdated.';
$string['academic_year'] = 'Academic Year';
$string['academic_year_desc'] = 'Set the academic year (example: 2024) for reported absences. Leave blank to use the current academic year based on the current month.';
$string['acknowledged'] = 'Acknowledged';
$string['acknowledge'] = 'Acknowledge';
$string['all_faculties'] = 'All Faculties';
$string['back_to_my_courses'] = 'Back to My Courses';
$string['bereavement'] = 'Bereavement of an immediate family member';
$string['circumstance'] = 'Circumstance';
$string['course'] = 'Course';
$string['duration'] = 'Duration';
$string['enabled'] = 'Enable Absence Request Plugin';
$string['enabled_desc'] = 'Enable or disable the absence request functionality. When disabled, students will not be able to submit absence requests.';
$string['acknowledge_enabled'] = 'Enable Acknowledge Receipt';
$string['acknowledge_enabled_desc'] = 'Enable or disable the acknowledge receipt functionality. When enabled, instructors will receive acknowledgment links in their notification emails to confirm they have reviewed the absence request.';
$string['error_academic_year'] = 'The reported absence must be within the current academic year.';
$string['error_already_submitted_for_selected_dates'] = 'You have already submitted an absence for the selected dates.';
$string['error_end_before_start'] = 'The end date cannot be before the start date.';
$string['error_max_7_days'] = 'The absence cannot be longer than 7 days.';
$string['error_term_period'] = 'The absence must be within the current term period.';
$string['faculty'] = 'Faculty';
$string['from_date'] = 'From Submission Date';
$string['max_requests_reached'] = 'You have reached the maximum number of absences for this term.';
$string['nopermission'] = 'You do not have permission to view this page.';
$string['nopermissiontoviewpage'] = 'You do not have permission to view this page.';
$string['not_eligible'] = 'You are not eligible to report an absence.';
$string['not_enrolled_in_courses'] = 'You are not enrolled in any courses for this term. You can only report absences for courses you are enrolled in.';
$string['notify_instructor_body'] = 'A student has reported an absence. View the report: {$a}';
$string['notify_instructor_subject'] = 'Student Reported Absence Notification';
$string['pluginname'] = 'Absence Reporting';
$string['report_link'] = 'View Absence Report';
$string['request_submitted'] = 'Your reported absence has been submitted. Note: It is the responsibility of the student to arrange any accommodations for missed course work with your individual instructors';
$string['short_term_health'] = 'Short-term health conditions (illness, physical injury, scheduled surgery)';
$string['sisid'] = 'Student ID';
$string['student'] = 'Student';
$string['student_firstname'] = 'First Name';
$string['student_instructions'] = '<p>Please fill out the form below to report an absence. You can only submit up to two absences per term. '
    . 'Ensure that your absence does not exceed 7 days in duration. Any reason beyond those listed in the drop-down '
    . 'menu are not eligible for a self-reported absence. Note that this reported absence will be submitted for all courses you are enrolled in, in this term.</p>'
    . '<p>Any accommodations for missed course work need to be arranged with your individual instructors</p>'
    . '<p>For more information about the policy on Academic Consideration for Missed Work, please refer to the '
    . '<a href="https://www.yorku.ca/secretariat/policies/policies/academic-consideration-for-missed-course-work-policy-on/" target="_blank">Academic Consideration Policy</a>.</p>';
$string['student_lastname'] = 'Last Name';
$string['submitted'] = 'Submitted';
$string['task_send_teacher_notifications'] = 'Send teacher notifications for absence requests';
$string['submit_request'] = 'Report An Absence';
$string['teacher'] = 'Instructor';
$string['to_date'] = 'To Submission Date';
$string['type_of_circumstance'] = 'Type of Circumstance';
$string['unforeseen'] = 'Unforeseen or unavoidable incidents beyond the student’s control';
$string['view_faculty_report'] = 'Faculty Absence Report';
$string['view_my_reported_absences'] = 'View My Reported Absences';
$string['my_reported_absences'] = 'My Reported Absences';


// Settings strings
$string['requests_per_term'] = 'Maximum requests per term';
$string['requests_per_term_desc'] = 'Maximum number of absence requests a student can submit per term';
$string['use_period'] = 'Use specific period';
$string['use_period_desc'] = 'Override automatic period detection for testing purposes';
$string['use_period_no'] = 'Use automatic detection';
$string['use_period_F'] = 'Fall period';
$string['use_period_W'] = 'Winter period';
$string['use_period_S'] = 'Summer period';
$string['use_period_Y'] = 'Full year period';
$string['enrollment_methods'] = 'Enrollment methods';
$string['enrollment_methods_desc'] = 'Which enrollment methods to include when checking student enrollments';
$string['enrollment_methods_all'] = 'All enrollment methods';
$string['enrollment_methods_manual'] = 'Manual enrollment only';
$string['enrollment_methods_arms'] = 'ARMS enrollment only';
$string['passwordsaltmain'] = 'Password Salt for Encryption';
$string['passwordsaltmain_desc'] = 'A secret password salt used to encrypt acknowledgment URLs in teacher emails. This prevents users from hacking the acknowledgment system by manipulating URL parameters. Leave blank to use the default Moodle password salt.';

// Notifications
$string['absence_request:acknowledge'] = 'Acknowledge Absence Request';
$string['absence_request:view_faculty_report'] = 'View Faculty Absence Report';
$string['absence_request:view_teacher_report'] = 'View Absence Report';
$string['messageprovider:absence_notification'] = 'Absence Notifications';
$string['student_message_subject'] = 'Absence Reported Successfully';
$string['teacher_message_subject'] = 'Reported Absence Notification';
$string['student_full_message'] = 'Hello {$a->firstname}, '
    .'<p>Your reported absence has been successfully submitted.</p>'
    . '<p>'
    . '<b>Circumstance:</b> {$a->circumstance}<br>'
    . '<b>Start date:</b> {$a->startdate}<br>'
    . '<b>End date:</b> {$a->enddate}<br>'
    . '<b>Absence duration in days</b> {$a->numberofdays}<br>'
    . '</p>'
    . '<p>Any accommodations for missed course work need to be arranged with your individual instructors</p>'
    . '<p>Thank you!</p>';
$string['teacher_message'] = 'Hello, <p>You have received {$a->absence_count} new self-reported absence(s) under the Policy on Academic '
    . '<a href="{$a->policylink}">Consideration for Missed Course Work</a>.</p>'
    . '<p>Please visit the <a href="{$a->url}">absence report</a> to view the details of all reported absences and acknowledge them.</p>'
    . '<p>Thank you!</p>';

// Privacy API strings
$string['privacy:metadata:local_absence_request'] = 'Information about student reported absence';
$string['privacy:metadata:local_absence_request:userid'] = 'The ID of the user who reported the absence';
$string['privacy:metadata:local_absence_request:faculty'] = 'The faculty associated with the reported absence';
$string['privacy:metadata:local_absence_request:circumstance'] = 'The circumstance for the reported absence';
$string['privacy:metadata:local_absence_request:starttime'] = 'The start time of the absence';
$string['privacy:metadata:local_absence_request:endtime'] = 'The end time of the absence';
$string['privacy:metadata:local_absence_request:acadyear'] = 'The academic year of the reported absence';
$string['privacy:metadata:local_absence_request:termperiod'] = 'The term period of the reported absence';
$string['privacy:metadata:local_absence_request:timecreated'] = 'The time the reported absence was created';

$string['privacy:metadata:local_absence_req_teacher'] = 'Information about teachers associated with reported absence';
$string['privacy:metadata:local_absence_req_teacher:userid'] = 'The ID of the teacher associated with the reported absence';
$string['privacy:metadata:local_absence_req_teacher:absence_req_course_id'] = 'The ID of the reported absence course';
$string['privacy:metadata:local_absence_req_teacher:timecreated'] = 'The time the teacher association was created';

$string['privacy:path:absencerequests'] = 'Reported absences';
$string['privacy:path:teacherrequests'] = 'Teacher notifications';

$string['teacher_firstname'] = 'Teacher First Name';
$string['teacher_lastname'] = 'Teacher Last Name';

// Student view strings
$string['view_my_reported_absences'] = 'View My Reported Absences';
$string['fall_term'] = 'Fall Term';
$string['winter_term'] = 'Winter Term';
$string['summer_term'] = 'Summer Term';
$string['affected_courses'] = 'Affected Courses';
$string['notified_instructors'] = 'Notified Instructors';
$string['employee_id'] = 'Employee ID';
$string['pending_acknowledgment'] = 'Pending Acknowledgment';
$string['no_instructors_notified'] = 'No instructors were notified for this course';
$string['no_absences_for_term'] = 'No reported absences for this term';
$string['no_reported_absences'] = 'No Reported Absences';
$string['no_absences_message'] = 'You have not reported any absences for the current academic year.';
$string['report_new_absence'] = 'Report New Absence';
$string['back_to_dashboard'] = 'Back to Dashboard';
$string['days'] = 'days';
$string['my_reported_absences'] = 'My Reported Absences';
$string['reported_absence'] = 'Reported Absence';

// Access language strings

// Bulk acknowledge strings
$string['bulk_acknowledge'] = 'Acknowledge Selected';
$string['select_all'] = 'Select All';
$string['bulk_acknowledge_success'] = 'Successfully acknowledged {$a} absence request(s)';
$string['bulk_acknowledge_error'] = 'Error acknowledging absence requests';
$string['select_items_to_acknowledge'] = 'Select items in the table below to acknowledge them';

