<?php
// Language strings for absence_request plugin (French).
$string['absence_end'] = 'Date et heure de fin de l’absence';
$string['absence_request'] = 'Signaler une absence';
$string['absence_start'] = 'Date et heure de début de l’absence';
$string['all_faculties'] = 'Toutes les facultés';
$string['back_to_my_courses'] = 'Retour à mes cours';
$string['bereavement'] = 'Deuil d’un membre de la famille proche';
$string['circumstance'] = 'Circonstance';
$string['course'] = 'Cours';
$string['duration'] = 'Durée (jours)';
$string['error_academic_year'] = 'L’absence signalée doit se situer dans l’année universitaire en cours.';
$string['error_already_submitted_for_selected_dates'] = 'Vous avez déjà signalé une absence pour les dates sélectionnées.';
$string['error_end_before_start'] = 'La date de fin ne peut pas être antérieure à la date de début.';
$string['error_max_7_days'] = 'L’absence ne peut pas dépasser 7 jours.';
$string['error_term_period'] = 'L’absence doit se situer dans la période du trimestre en cours.';
$string['faculty'] = 'Faculté';
$string['from_date'] = 'Date de début';
$string['max_requests_reached'] = 'Vous avez atteint le nombre maximum d’absences pour ce trimestre.';
$string['nopermission'] = 'Vous n’avez pas la permission de voir cette page.';
$string['nopermissiontoviewpage'] = 'Vous n\'avez pas la permission de voir cette page.';
$string['not_eligible'] = 'Vous n’êtes pas éligible pour signaler une absence.';
$string['not_enrolled_in_courses'] = 'Vous n’êtes inscrit à aucun cours pour ce trimestre. Vous pouvez uniquement signaler des absences pour les cours auxquels vous êtes inscrit.';
$string['notify_instructor_body'] = 'Un étudiant a signalé une absence. Voir le rapport : {$a}';
$string['notify_instructor_subject'] = 'Notification d’absence signalée par un étudiant';
$string['pluginname'] = 'Signalement d’absences';
$string['report_link'] = 'Voir le rapport des absences signalées';
$string['request_submitted'] = 'Votre absence signalée a été soumise. Note : il appartient à l’étudiant de prendre des dispositions avec chaque enseignant pour le travail manqué.';
$string['short_term_health'] = 'Conditions de santé à court terme (maladie, blessure, chirurgie programmée)';
$string['sisid'] = 'Numéro d’étudiant';
$string['student'] = 'Étudiant';
$string['student_firstname'] = 'Prénom de l’étudiant';
$string['student_instructions'] = '<p>Veuillez remplir le formulaire ci-dessous pour signaler une absence. Vous pouvez soumettre au maximum deux absences par trimestre. Assurez-vous que votre absence ne dépasse pas 7 jours. Toute raison autre que celles listées dans le menu déroulant n’est pas éligible pour une absence auto-déclarée. Notez que cette absence signalée sera soumise pour tous les cours auxquels vous êtes inscrit ce trimestre.</p>'
    . '<p>Toute disposition pour le travail manqué doit être prise avec chacun de vos enseignants.</p>'
    . '<p>Pour plus d’informations sur la politique de considération académique pour le travail manqué, veuillez consulter la <a href="https://www.yorku.ca/secretariat/policies/policies/academic-consideration-for-missed-course-work-policy-on/" target="_blank">Politique de considération académique</a>.</p>';
$string['student_lastname'] = 'Nom de famille de l’étudiant';
$string['submitted'] = 'Soumis';
$string['submit_request'] = 'Signaler une absence';
$string['teacher'] = 'Enseignant';
$string['to_date'] = 'Date de fin';
$string['type_of_circumstance'] = 'Type de circonstance';
$string['unforeseen'] = 'Incidents imprévus ou inévitables hors du contrôle de l’étudiant';
$string['view_faculty_report'] = 'Rapport d’absence par faculté';

// Settings page strings
$string['requests_per_term'] = 'Nombre de demandes par trimestre';
$string['requests_per_term_desc'] = 'Définissez le nombre maximum d’absences qu’un étudiant peut signaler par trimestre.';
$string['teacher_message'] = 'Message pour l’enseignant';
$string['teacher_message_desc'] = 'Message par défaut envoyé aux enseignants lorsqu’une absence est signalée.';
$string['student_message'] = 'Message pour l’étudiant';
$string['student_message_desc'] = 'Message par défaut envoyé aux étudiants lorsqu’une absence est signalée.';
$string['use_period'] = 'Utiliser la période';
$string['use_period_desc'] = 'Ce paramètre doit être utilisé uniquement lors des tests du plugin. Il vous permet de sélectionner la période pendant laquelle les absences signalées peuvent être soumises. Note : L’année est toujours disponible en hiver, jamais en automne. Donc, si vous testez pour les cours annuels, assurez-vous de sélectionner une date entre janvier et avril.';
$string['use_period_no'] = 'Non';
$string['use_period_F'] = 'Automne';
$string['use_period_W'] = 'Hiver';
$string['use_period_S'] = 'Été';
$string['use_period_Y'] = 'Année';

// Enrollment methods settings
$string['enrollment_methods'] = 'Méthodes d\'inscription des enseignants pour les notifications';
$string['enrollment_methods_desc'] = 'Sélectionnez quelles méthodes d\'inscription des enseignants doivent recevoir les notifications d\'absence. Seuls les enseignants inscrits par les méthodes sélectionnées seront notifiés lorsque les étudiants signalent des absences.';
$string['enrollment_methods_all'] = 'Toutes';
$string['enrollment_methods_manual'] = 'Manuelle';
$string['enrollment_methods_arms'] = 'ARMS';

// Notifications
$string['absence_request:view_faculty_report'] = 'Voir le rapport d’absence par faculté';
$string['absence_request:view_teacher_report'] = 'Voir le rapport d’absence';
$string['messageprovider:absence_notification'] = 'Notifications de demande d’absence';
$string['student_message_subject'] = 'Absence signalée avec succès';
$string['teacher_message_subject'] = 'Notification d’absence signalée';
$string['student_message'] = 'Bonjour {$a->firstname}, '
    . '<p>Votre absence signalée a été soumise avec succès.</p>'
    . '<p>'
    . '<b>Circconstance :</b> {$a->circumstance}<br>'
    . '<b>Date de début :</b> {$a->startdate}<br>'
    . '<b>Date de fin :</b> {$a->enddate}<br>'
    . '</p>'
    . '<p>Toute disposition pour le travail manqué doit être prise avec chacun de vos enseignants.</p>'
    . '<p>Merci !</p>';
$string['teacher_message'] = 'Bonjour, <p>Vous avez reçu une absence auto-déclarée dans le cadre de la <a href="{$a->policylink}">Politique de considération académique pour le travail manqué</a>. Les détails sont ci-dessous.</p>'
    . '<p>'
    . '<b>Étudiant :</b> {$a->studentname} ({$a->idnumber})<br>'
    . '<b>Circconstance :</b> {$a->circumstance}<br>'
    . '<b>Date de début :</b> {$a->startdate}<br>'
    . '<b>Date de fin :</b> {$a->enddate}<br>'
    . '</p>'
    . '<p>Vous pouvez également consulter toutes les demandes dans le <a href="{$a->url}">rapport d’absence</a>.</p>'
    . '<p>Merci !</p>';

// Privacy API strings
$string['privacy:metadata:local_absence_request'] = 'Informations sur l’absence signalée par l’étudiant';
$string['privacy:metadata:local_absence_request:userid'] = 'L’identifiant de l’utilisateur ayant signalé l’absence';
$string['privacy:metadata:local_absence_request:faculty'] = 'La faculté associée à l’absence signalée';
$string['privacy:metadata:local_absence_request:circumstance'] = 'La circonstance de l’absence signalée';
$string['privacy:metadata:local_absence_request:starttime'] = 'L’heure de début de l’absence';
$string['privacy:metadata:local_absence_request:endtime'] = 'L’heure de fin de l’absence';
$string['privacy:metadata:local_absence_request:acadyear'] = 'L’année universitaire de l’absence signalée';
$string['privacy:metadata:local_absence_request:termperiod'] = 'La période de trimestre de l’absence signalée';
$string['privacy:metadata:local_absence_request:timecreated'] = 'L’heure de création de l’absence signalée';

$string['privacy:metadata:local_absence_req_teacher'] = 'Informations sur les enseignants associés à l’absence signalée';
$string['privacy:metadata:local_absence_req_teacher:userid'] = 'L’identifiant de l’enseignant associé à l’absence signalée';
$string['privacy:metadata:local_absence_req_teacher:absence_req_course_id'] = 'L’identifiant du cours concerné par l’absence signalée';
$string['privacy:metadata:local_absence_req_teacher:timecreated'] = 'L’heure de création de l’association enseignant';

$string['privacy:path:absencerequests'] = 'Absences signalées';
$string['privacy:path:teacherrequests'] = 'Notifications aux enseignants';

$string['teacher_firstname'] = 'Prénom de l’enseignant';
$string['teacher_lastname'] = 'Nom de famille de l’enseignant';
