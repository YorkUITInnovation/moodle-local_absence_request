<?php
// Language strings for absence_request plugin.
$string['absence_end'] = 'Absence End Date and Time';
$string['absence_request'] = 'Absence Request';
$string['absence_start'] = 'Absence Start Date and Time';
$string['all_faculties'] = 'All Faculties';
$string['back_to_my_courses'] = 'Back to My Courses';
$string['bereavement'] = 'Bereavement of an immediate family member';
$string['course'] = 'Course';
$string['duration'] = 'Duration (days)';
$string['error_academic_year'] = 'The absence request must be within the current academic year.';
$string['error_already_submitted_for_selected_dates'] = 'You have already submitted an absence request for the selected dates.';
$string['error_end_before_start'] = 'The end date cannot be before the start date.';
$string['error_max_7_days'] = 'The absence cannot be longer than 7 days.';
$string['error_term_period'] = 'The absence request must be within the current term period.';
$string['faculty'] = 'Faculty';
$string['from_date'] = 'From Date';
$string['max_requests_reached'] = 'You have reached the maximum number of absence requests for this term.';
$string['nopermission'] = 'You do not have permission to view this page.';
$string['not_eligible'] = 'You are not eligible to submit an absence request.';
$string['not_enrolled_in_courses'] = 'You are not enrolled in any courses for this term. You can only submit absence requests for courses you are enrolled in.';
$string['notify_instructor_body'] = 'A student has submitted an absence request. View the report: {$a}';
$string['notify_instructor_subject'] = 'Student Absence Request Notification';
$string['pluginname'] = 'Absence Request';
$string['report_link'] = 'View Absence Requests Report';
$string['request_submitted'] = 'Your absence request has been submitted.';
$string['short_term_health'] = 'Short-term health conditions (illness, physical injury, scheduled surgery)';
$string['sisid'] = 'Student ID';
$string['student_firstname'] = 'Student First Name';
$string['student_instructions'] = '<p>Please fill out the form below to submit your absence request. You can only submit up to two requests per term. '
    . 'Ensure that your request does not exceed 7 days in duration. Note that this request will be submitted for all courses you are enrolled in this term.</p>'
    . '<p>For more information about the policy on Academic Consideration for Missed Work, please refer to the '
    . '<a href="20250227_Senate_approved_Acad Consid_for_Missed_Course_Work_Policy_Final.pdf" target="_blank">Academic Consideration Policy</a>.</p>';
$string['student_lastname'] = 'Student Last Name';
$string['submit_request'] = 'Submit Absence Request';
$string['to_date'] = 'To Date';
$string['type_of_circumstance'] = 'Type of Circumstance';
$string['unforeseen'] = 'Unforeseen or unavoidable incidents beyond the student’s control';
$string['view_faculty_report'] = 'Faculty Absence Report';

// Settings page strings
$string['requests_per_term'] = 'Number of requests per term';
$string['requests_per_term_desc'] = 'Set the maximum number of absence requests a student can submit per term.';
$string['teacher_message'] = 'Teacher message';
$string['teacher_message_desc'] = 'Default message to be sent to teachers when an absence request is submitted.';
$string['student_message'] = 'Student message';
$string['student_message_desc'] = 'Default message to be sent to students when an absence request is submitted.';

// Notifications
$string['messageprovider:absence_notification'] = 'Absence Request Notifications';
$string['student_message_subject'] = 'Absence Request Submitted';
$string['teacher_message_subject'] = 'Absence Request Notification';
$string['student_message'] = 'Your absence request has been successfully submitted.';
$string['teacher_message'] = 'A student has submitted an absence request. Please review the request in the <a href="{$a->url}">absence report.</a>';

// Privacy API strings
$string['privacy:metadata:local_absence_request'] = 'Information about student absence requests';
$string['privacy:metadata:local_absence_request:userid'] = 'The ID of the user who submitted the absence request';
$string['privacy:metadata:local_absence_request:faculty'] = 'The faculty associated with the absence request';
$string['privacy:metadata:local_absence_request:circumstance'] = 'The circumstance for the absence request';
$string['privacy:metadata:local_absence_request:starttime'] = 'The start time of the absence';
$string['privacy:metadata:local_absence_request:endtime'] = 'The end time of the absence';
$string['privacy:metadata:local_absence_request:acadyear'] = 'The academic year of the absence request';
$string['privacy:metadata:local_absence_request:termperiod'] = 'The term period of the absence request';
$string['privacy:metadata:local_absence_request:timecreated'] = 'The time the absence request was created';

$string['privacy:metadata:local_absence_req_teacher'] = 'Information about teachers associated with absence requests';
$string['privacy:metadata:local_absence_req_teacher:userid'] = 'The ID of the teacher associated with the absence request';
$string['privacy:metadata:local_absence_req_teacher:absence_req_course_id'] = 'The ID of the absence request course';
$string['privacy:metadata:local_absence_req_teacher:timecreated'] = 'The time the teacher association was created';

$string['privacy:path:absencerequests'] = 'Absence requests';
$string['privacy:path:teacherrequests'] = 'Teacher notifications';

