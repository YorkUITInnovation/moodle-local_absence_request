<?php
// notifications.php - Handles notification logic for absence requests.
// This class provides static methods to send notifications to students and course directors (teachers) when absence requests are submitted.
// It uses Moodle's messaging API to send both email and Moodle notifications.

namespace local_absence_request;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/messagelib.php');

class notifications
{
    /**
     * Notify a student by email and Moodle notification.
     * Sends a message to the student when their absence request is submitted.
     *
     * @param int $userid The user ID of the student.
     * @param int $absence_request_id The ID of the absence request.
     * @return bool|int Message send status.
     */
    public static function notify_student(int $userid, int $absence_request_id)
    {
        global $DB;
        // Get the absence  request details
        $absence_request = $DB->get_record('local_absence_request', ['id' => $absence_request_id]);
        // Calculate days
        $number_of_days = helper::calculate_days($absence_request->starttime, $absence_request->endtime);
        // Get student from the absence request
        $student = $DB->get_record('user', ['id' => $userid]);

        // Force the student's language for the notification
        $current_lang = current_language();
        $student_lang = $student->lang ?? 'en';

        // Only support fr and en languages (with fr_ca mapping to fr)
        if (strpos($student_lang, 'fr') === 0) {
            $student_lang = 'fr';
        } else if ($student_lang !== 'en') {
            // Default to English for any unsupported language
            $student_lang = 'en';
        }
        force_current_language($student_lang);

        $subject = get_string('student_message_subject', 'local_absence_request');
        $message = get_string('student_full_message', 'local_absence_request',
            [
                'firstname' => $student->firstname,
                'circumstance' => get_string($absence_request->circumstance, 'local_absence_request'),
                'startdate' => date('l F d, Y', $absence_request->starttime),
                'enddate' => date('l F d, Y', $absence_request->endtime),
                'numberofdays' => $number_of_days
            ]
        );

        // Restore the original language
        force_current_language($current_lang);

        // Prepare the message data
        $eventdata = new \core\message\message();
        $eventdata->component = 'local_absence_request';
        $eventdata->name = 'absence_notification';
        $eventdata->userfrom = \core_user::get_noreply_user();
        $eventdata->userto = $student;
        $eventdata->subject = $subject;
        $eventdata->fullmessage = $message;
        $eventdata->fullmessageformat = FORMAT_HTML;
        $eventdata->fullmessagehtml = $message;
        $eventdata->smallmessage = html_to_text($message);
        // Send the message
        $notification = message_send($eventdata);
        return $notification;
    }

    /**
     * Notify a teacher (course director) by email and Moodle notification.
     * Sends a message to the course director when a student submits an absence request for their course.
     *
     * @param int $teacher_user_id The user ID of the teacher.
     * @param int $absence_request_count The count of absence requests.
     * @return bool|int Message send status.
     */
    public static function notify_teacher(int $teacher_user_id, int $absence_request_count)
    {
        global $DB;

        // Get the teacher record that will be used in $eventdata->userto
        $teacher = $DB->get_record('user', ['id' => $teacher_user_id]);
        if (!$teacher) {
            return false;
        }

        // No courseid needed - teacher_view.php defaults to courseid=1
        // Editing teachers see all their courses regardless of courseid
        $url = new \moodle_url('/local/absence_request/teacher_view.php');

        // Prepare message parameters with absence count
        $message_params = [
            'url' => $url->out(false),
            'policylink' => 'https://www.yorku.ca/secretariat/policies/policies/academic-consideration-for-missed-course-work-policy-on/',
            'absence_count' => $absence_request_count
        ];

        // Force the teacher's language for the notification
        $current_lang = current_language();
        $teacher_lang = $teacher->lang ?? 'en';

        // Only support fr and en languages (with fr_ca mapping to fr)
        if (strpos($teacher_lang, 'fr') === 0) {
            $teacher_lang = 'fr';
        } else if ($teacher_lang !== 'en') {
            // Default to English for any unsupported language
            $teacher_lang = 'en';
        }
        force_current_language($teacher_lang);

        $subject = get_string('teacher_message_subject', 'local_absence_request');
        $message = get_string('teacher_message', 'local_absence_request', $message_params);

        // Restore the original language
        force_current_language($current_lang);

        // Prepare the message data
        $eventdata = new \core\message\message();
        $eventdata->component = 'local_absence_request';
        $eventdata->name = 'absence_notification';
        $eventdata->userfrom = \core_user::get_noreply_user();
        $eventdata->userto = $teacher;
        $eventdata->subject = $subject;
        $eventdata->fullmessage = $message;
        $eventdata->fullmessageformat = FORMAT_HTML;
        $eventdata->fullmessagehtml = $message;
        $eventdata->smallmessage = html_to_text($message);

        // Send the message
        $notification = message_send($eventdata);
        return $notification;
    }
}
