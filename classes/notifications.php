<?php
// notifications.php - Handles notification logic for absence requests.
namespace local_absence_request;
include_once('../../config.php');
include_once($CFG->libdir . '/messagelib.php');

defined('MOODLE_INTERNAL') || die();

class notifications
{
    /**
     * Notify a student by email and Moodle notification.
     * @param int $userid The user ID of the student.
     * @param string $subject The subject of the message.
     * @param string $message The message body.
     */
    public static function notify_student($userid)
    {
        global $DB;
        $subject = get_string('student_message_subject', 'local_absence_request');
        $message = get_string('student_message', 'local_absence_request');
        $user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);
        // Prepare the message data
        $eventdata = new \core\message\message();
        $eventdata->component = 'local_absence_request';
        $eventdata->name = 'absence_notification';
        $eventdata->userfrom = \core_user::get_noreply_user();
        $eventdata->userto = $user;
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
     * Notify a teacher by email and Moodle notification.
     * @param int $userid The user ID of the teacher.
     * @param string $subject The subject of the message.
     * @param string $message The message body.
     */
    public static function notify_teacher($userid, $absence_request)
    {
        global $DB;
        $url = new \moodle_url('/local/absence_request/teacher_view.php', ['id' => $absence_request]);
        $subject = get_string('teacher_message_subject', 'local_absence_request');
        $message = get_string('teacher_message', 'local_absence_request', ['url' => $url->out(false)]);
        $user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);
        // Prepare the message data
        $eventdata = new \core\message\message();
        $eventdata->component = 'local_absence_request';
        $eventdata->name = 'absence_notification';
        $eventdata->userfrom = \core_user::get_noreply_user();
        $eventdata->userto = $user;
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

