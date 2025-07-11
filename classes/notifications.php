<?php
// notifications.php - Handles notification logic for absence requests.
// This class provides static methods to send notifications to students and course directors (teachers) when absence requests are submitted.
// It uses Moodle's messaging API to send both email and Moodle notifications.

namespace local_absence_request;
include_once('../../config.php');
include_once($CFG->libdir . '/messagelib.php');

defined('MOODLE_INTERNAL') || die();

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
        // Get student from the absence request
        $student = $DB->get_record('user', ['id' => $userid]);
        $subject = get_string('student_message_subject', 'local_absence_request');
        $message = get_string('student_message', 'local_absence_request',
            [
                'firstname' => $student->firstname,
                'circumstance' => get_string($absence_request->circumstance, 'local_absence_request'),
                'startdate' => userdate($absence_request->starttime),
                'enddate' => userdate($absence_request->endtime),
            ]
        );

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
     * @param int $userid The user ID of the teacher.
     * @param int $absence_request_id The ID of the absence request.
     * @return bool|int Message send status.
     */
    public static function notify_teacher(int $userid, int $absence_request_id)
    {
        global $DB;
        // Get the absence  request details
        $absence_request = $DB->get_record('local_absence_request', ['id' => $absence_request_id]);
        // Get student from the absence request
        $student = $DB->get_record('user', ['id' => $absence_request->userid]);

        $url = new \moodle_url('/local/absence_request/teacher_view.php', ['id' => $absence_request_id]);
        $subject = get_string('teacher_message_subject', 'local_absence_request');
        $message = get_string('teacher_message', 'local_absence_request', [
                'url' => $url->out(false),
                'studentname' => fullname($student),
                'idnumber' => $student->idnumber,
                'circumstance' => get_string($absence_request->circumstance, 'local_absence_request'),
                'startdate' => userdate($absence_request->starttime),
                'enddate' => userdate($absence_request->endtime),
            ]
        );
        $user = $DB->get_record('user', ['id' => $userid]);
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
