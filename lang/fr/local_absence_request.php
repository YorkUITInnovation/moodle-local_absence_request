<?php
// Fichier de langue pour le plugin absence_request (français)
$string['absence_end'] = "Date et heure de fin d'absence";
$string['absence_request'] = "Demande d'absence";
$string['absence_start'] = "Date et heure de début d'absence";
$string['all_faculties'] = "Toutes les facultés";
$string['back_to_my_courses'] = "Retour à mes cours";
$string['bereavement'] = "Décès d'un membre de la famille immédiate";
$string['course'] = "Cours";
$string['duration'] = "Durée (jours)";
$string['error_academic_year'] = "La demande d'absence doit être dans l'année académique en cours.";
$string['error_already_submitted_for_selected_dates'] = "Vous avez déjà soumis une demande d'absence pour les dates sélectionnées.";
$string['error_end_before_start'] = "La date de fin ne peut pas être antérieure à la date de début.";
$string['error_max_7_days'] = "L'absence ne peut pas dépasser 7 jours.";
$string['error_term_period'] = "La demande d'absence doit être dans la période de session en cours.";
$string['faculty'] = "Faculté";
$string['from_date'] = "Date de début";
$string['max_requests_reached'] = "Vous avez atteint le nombre maximum de demandes d'absence pour cette session.";
$string['nopermission'] = "Vous n'avez pas la permission de voir cette page.";
$string['not_eligible'] = "Vous n'êtes pas éligible pour soumettre une demande d'absence.";
$string['not_enrolled_in_courses'] = "Vous n'êtes inscrit à aucun cours pour cette session. Vous ne pouvez soumettre des demandes d'absence que pour les cours auxquels vous êtes inscrit.";
$string['notify_instructor_body'] = "Un étudiant a soumis une demande d'absence. Voir le rapport : {$a}";
$string['notify_instructor_subject'] = "Notification de demande d'absence d'un étudiant";
$string['pluginname'] = "Demande d'absence";
$string['report_link'] = "Voir le rapport des demandes d'absence";
$string['request_submitted'] = "Votre demande d'absence a été soumise.";
$string['short_term_health'] = "Problèmes de santé à court terme (maladie, blessure physique, chirurgie programmée)";
$string['sisid'] = 'ID étudiant';
$string['student_firstname'] = 'Prénom de l\'étudiant';
$string['student_instructions'] = "<p>Veuillez remplir le formulaire ci-dessous pour soumettre votre demande d'absence. Vous ne pouvez soumettre que deux demandes par session. Assurez-vous que votre demande ne dépasse pas 7 jours. Cette demande sera soumise pour tous les cours auxquels vous êtes inscrit cette session.</p><p>Pour plus d'informations sur la politique de considération académique pour le travail de cours manqué, veuillez consulter la <a href='20250227_Senate_approved_Acad Consid_for_Missed_Course_Work_Policy_Final.pdf' target='_blank'>politique de considération académique</a>.</p>";
$string['student_lastname'] = 'Nom de famille de l\'étudiant';
$string['submit_request'] = "Soumettre une demande d'absence";
$string['to_date'] = "Date de fin";
$string['type_of_circumstance'] = "Type de circonstance";
$string['unforeseen'] = "Incidents imprévus ou inévitables hors du contrôle de l'étudiant";
$string['view_faculty_report'] = 'Rapport d\'absence par faculté';

// Paramètres de la page de configuration
$string['requests_per_term'] = 'Nombre de demandes par session';
$string['requests_per_term_desc'] = 'Définissez le nombre maximum de demandes d\'absence qu\'un étudiant peut soumettre par session.';
$string['teacher_message'] = 'Message enseignant';
$string['teacher_message_desc'] = 'Message par défaut à envoyer aux enseignants lorsqu\'une demande d\'absence est soumise.';
$string['student_message'] = 'Message étudiant';
$string['student_message_desc'] = 'Message par défaut à envoyer aux étudiants lorsqu\'une demande d\'absence est soumise.';

// Notifications
$string['absence_request:view_faculty_report'] = 'Voir le rapport d\'absence par faculté';
$string['absence_request:view_teacher_report'] = 'Voir le rapport d\'absence';
$string['messageprovider:absence_notification'] = 'Notifications de demande d\'absence';
$string['student_message_subject'] = 'Demande d\'absence soumise';
$string['teacher_message_subject'] = 'Notification de demande d\'absence';
$string['student_message'] = 'Votre demande d\'absence a été soumise avec succès.';
$string['teacher_message'] = 'Un étudiant a soumis une demande d\'absence. Veuillez consulter la demande dans le <a href="{$a->url}">rapport des absences</a>.';

// Chaînes de l'API de confidentialité
$string['privacy:metadata:local_absence_request'] = 'Informations sur les demandes d\'absence des étudiants';
$string['privacy:metadata:local_absence_request:userid'] = 'L\'identifiant de l\'utilisateur qui a soumis la demande d\'absence';
$string['privacy:metadata:local_absence_request:faculty'] = 'La faculté associée à la demande d\'absence';
$string['privacy:metadata:local_absence_request:circumstance'] = 'La circonstance de la demande d\'absence';
$string['privacy:metadata:local_absence_request:starttime'] = 'L\'heure de début de l\'absence';
$string['privacy:metadata:local_absence_request:endtime'] = 'L\'heure de fin de l\'absence';
$string['privacy:metadata:local_absence_request:acadyear'] = 'L\'année académique de la demande d\'absence';
$string['privacy:metadata:local_absence_request:termperiod'] = 'La période de session de la demande d\'absence';
$string['privacy:metadata:local_absence_request:timecreated'] = 'L\'heure à laquelle la demande d\'absence a été créée';

$string['privacy:metadata:local_absence_req_teacher'] = 'Informations sur les enseignants associés aux demandes d\'absence';
$string['privacy:metadata:local_absence_req_teacher:userid'] = 'L\'identifiant de l\'enseignant associé à la demande d\'absence';
$string['privacy:metadata:local_absence_req_teacher:absence_req_course_id'] = 'L\'identifiant du cours de la demande d\'absence';
$string['privacy:metadata:local_absence_req_teacher:timecreated'] = 'L\'heure à laquelle l\'association de l\'enseignant a été créée';

$string['privacy:path:absencerequests'] = 'Demandes d\'absence';
$string['privacy:path:teacherrequests'] = 'Notifications aux enseignants';
