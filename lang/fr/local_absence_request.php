<?php
// Language strings for absence_request plugin - French version.
$string['absence_end'] = 'Date de fin';
$string['absence_request'] = 'Signaler une absence';
$string['absence_start'] = 'Date de début';
$string['academic_year'] = 'Année académique';
$string['academic_year_desc'] = 'Définir l\'année académique (exemple: 2024) pour les absences signalées. Laisser vide pour utiliser l\'année académique actuelle basée sur le mois actuel.';
$string['acknowledged'] = 'Accusé de réception';
$string['acknowledge'] = 'Accuser réception';
$string['all_faculties'] = 'Toutes les facultés';
$string['back_to_my_courses'] = 'Retour à mes cours';
$string['bereavement'] = 'Deuil d\'un membre de la famille immédiate';
$string['circumstance'] = 'Circonstance';
$string['course'] = 'Cours';
$string['duration'] = 'Durée (jours)';
$string['error_academic_year'] = 'L\'absence signalée doit être dans l\'année académique actuelle.';
$string['error_already_submitted_for_selected_dates'] = 'Vous avez déjà soumis une absence pour les dates sélectionnées.';
$string['error_end_before_start'] = 'La date de fin ne peut pas être avant la date de début.';
$string['error_max_7_days'] = 'L\'absence ne peut pas durer plus de 7 jours.';
$string['error_term_period'] = 'L\'absence doit être dans la période de terme actuelle.';
$string['faculty'] = 'Faculté';
$string['from_date'] = 'Date de début';
$string['max_requests_reached'] = 'Vous avez atteint le nombre maximum d\'absences pour ce terme.';
$string['nopermission'] = 'Vous n\'avez pas la permission de voir cette page.';
$string['nopermissiontoviewpage'] = 'Vous n\'avez pas la permission de voir cette page.';
$string['not_eligible'] = 'Vous n\'êtes pas éligible pour signaler une absence.';
$string['not_enrolled_in_courses'] = 'Vous n\'êtes inscrit à aucun cours pour ce terme. Vous ne pouvez signaler des absences que pour les cours auxquels vous êtes inscrit.';
$string['notify_instructor_body'] = 'Un étudiant a signalé une absence. Voir le rapport : {$a}';
$string['notify_instructor_subject'] = 'Notification d\'absence signalée par un étudiant';
$string['pluginname'] = 'Signalement d\'absence';
$string['report_link'] = 'Voir le rapport d\'absence';
$string['request_submitted'] = 'Votre absence signalée a été soumise. Note : Il est de la responsabilité de l\'étudiant d\'organiser les accommodements pour le travail de cours manqué avec vos instructeurs individuels';
$string['short_term_health'] = 'Conditions de santé à court terme (maladie, blessure physique, chirurgie programmée)';
$string['sisid'] = 'ID étudiant';
$string['student'] = 'Étudiant';
$string['student_firstname'] = 'Prénom';
$string['student_instructions'] = '<p>Veuillez remplir le formulaire ci-dessous pour signaler une absence. Vous ne pouvez soumettre que jusqu\'à deux absences par terme. '
    . 'Assurez-vous que votre absence ne dépasse pas 7 jours de durée. Toute raison au-delà de celles listées dans le menu déroulant '
    . 'n\'est pas éligible pour une absence auto-signalée. Notez que cette absence signalée sera soumise pour tous les cours auxquels vous êtes inscrit, dans ce terme.</p>'
    . '<p>Tous les accommodements pour le travail de cours manqué doivent être organisés avec vos instructeurs individuels</p>'
    . '<p>Pour plus d\'informations sur la politique de Considération académique pour le travail manqué, veuillez vous référer à la '
    . '<a href="https://www.yorku.ca/secretariat/policies/policies/academic-consideration-for-missed-course-work-policy-on/" target="_blank">Politique de considération académique</a>.</p>';
$string['student_lastname'] = 'Nom de famille';
$string['submitted'] = 'Soumis';
$string['submit_request'] = 'Signaler une absence';
$string['teacher'] = 'Instructeur';
$string['to_date'] = 'Date de fin';
$string['type_of_circumstance'] = 'Type de circonstance';
$string['unforeseen'] = 'Incidents imprévus ou inévitables hors du contrôle de l\'étudiant';
$string['view_faculty_report'] = 'Rapport d\'absence de la faculté';

// Settings strings
$string['requests_per_term'] = 'Demandes maximales par terme';
$string['requests_per_term_desc'] = 'Nombre maximum de demandes d\'absence qu\'un étudiant peut soumettre par terme';
$string['use_period'] = 'Utiliser une période spécifique';
$string['use_period_desc'] = 'Remplacer la détection automatique de période à des fins de test';
$string['use_period_no'] = 'Utiliser la détection automatique';
$string['use_period_F'] = 'Période d\'automne';
$string['use_period_W'] = 'Période d\'hiver';
$string['use_period_S'] = 'Période d\'été';
$string['use_period_Y'] = 'Période d\'année complète';
$string['enrollment_methods'] = 'Méthodes d\'inscription';
$string['enrollment_methods_desc'] = 'Quelles méthodes d\'inscription inclure lors de la vérification des inscriptions d\'étudiants';
$string['enrollment_methods_all'] = 'Toutes les méthodes d\'inscription';
$string['enrollment_methods_manual'] = 'Inscription manuelle seulement';
$string['enrollment_methods_arms'] = 'Inscription ARMS seulement';
$string['passwordsaltmain'] = 'Sel de mot de passe pour le chiffrement';
$string['passwordsaltmain_desc'] = 'Un sel de mot de passe secret utilisé pour chiffrer les URLs d\'accusé de réception dans les courriels des enseignants. Cela empêche les utilisateurs de pirater le système d\'accusé de réception en manipulant les paramètres d\'URL. Laisser vide pour utiliser le sel de mot de passe Moodle par défaut.';

// Notifications
$string['absence_request:acknowledge'] = 'Accuser réception de la demande d\'absence';
$string['absence_request:view_faculty_report'] = 'Voir le rapport d\'absence de la faculté';
$string['absence_request:view_teacher_report'] = 'Voir le rapport d\'absence';
$string['messageprovider:absence_notification'] = 'Notifications d\'absence';
$string['student_message_subject'] = 'Absence signalée avec succès';
$string['teacher_message_subject'] = 'Notification d\'absence signalée';
$string['student_full_message'] = 'Bonjour {$a->firstname}, '
    .'<p>Votre absence signalée a été soumise avec succès.</p>'
    . '<p>'
    . '<b>Circonstance :</b> {$a->circumstance}<br>'
    . '<b>Date de début :</b> {$a->startdate}<br>'
    . '<b>Date de fin :</b> {$a->enddate}<br>'
    . '<b>Durée de l\'absence en jours :</b> {$a->numberofdays}<br>'
    . '</p>'
    . '<p>Tous les accommodements pour le travail de cours manqué doivent être organisés avec vos instructeurs individuels</p>'
    . '<p>Merci !</p>';
$string['teacher_message'] = 'Bonjour, <p>Vous avez reçu une absence auto-signalée sous la Politique de '
    . '<a href="{$a->policylink}">Considération académique pour le travail de cours manqué</a>. Les détails sont ci-dessous.</p>'
    . '<p>'
    . '<b>Étudiant :</b> {$a->studentname} ({$a->idnumber})<br>'
    . '<b>Circonstance :</b> {$a->circumstance}<br>'
    . '<b>Date de début :</b> {$a->startdate}<br>'
    . '<b>Date de fin :</b> {$a->enddate}<br>'
    . '<b>Durée de l\'absence en jours :</b> {$a->numberofdays}<br>'
    . '<b>Cours :</b> {$a->course}<br>'
    . '</p>'
    . '<p>Vous pouvez également consulter toutes les demandes dans le <a href="{$a->url}">rapport d\'absence.</a></p>'
    . '<p><a href="{$a->acknowledgeurl}" style="display: inline-block; padding: 10px 20px; background-color: #007cba; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; text-align: center; border: none; cursor: pointer;">Accuser réception de cette demande</a></p>'
    . '<p>Merci !</p>';

// Privacy API strings
$string['privacy:metadata:local_absence_request'] = 'Informations sur l\'absence signalée par l\'étudiant';
$string['privacy:metadata:local_absence_request:userid'] = 'L\'ID de l\'utilisateur qui a signalé l\'absence';
$string['privacy:metadata:local_absence_request:faculty'] = 'La faculté associée à l\'absence signalée';
$string['privacy:metadata:local_absence_request:circumstance'] = 'La circonstance de l\'absence signalée';
$string['privacy:metadata:local_absence_request:starttime'] = 'L\'heure de début de l\'absence';
$string['privacy:metadata:local_absence_request:endtime'] = 'L\'heure de fin de l\'absence';
$string['privacy:metadata:local_absence_request:acadyear'] = 'L\'année académique de l\'absence signalée';
$string['privacy:metadata:local_absence_request:termperiod'] = 'La période de terme de l\'absence signalée';
$string['privacy:metadata:local_absence_request:timecreated'] = 'L\'heure à laquelle l\'absence signalée a été créée';

$string['privacy:metadata:local_absence_req_teacher'] = 'Informations sur les enseignants associés à l\'absence signalée';
$string['privacy:metadata:local_absence_req_teacher:userid'] = 'L\'ID de l\'enseignant associé à l\'absence signalée';
$string['privacy:metadata:local_absence_req_teacher:absence_req_course_id'] = 'L\'ID du cours d\'absence signalée';
$string['privacy:metadata:local_absence_req_teacher:timecreated'] = 'L\'heure à laquelle l\'association enseignant a été créée';

$string['privacy:path:absencerequests'] = 'Absences signalées';
$string['privacy:path:teacherrequests'] = 'Notifications des enseignants';

$string['teacher_firstname'] = 'Prénom de l\'enseignant';
$string['teacher_lastname'] = 'Nom de famille de l\'enseignant';
