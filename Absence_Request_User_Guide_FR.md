# Guide d'utilisation du plugin de demande d'absence

**Pour les administrateurs, enseignants et administrateurs de faculté**

*Version 1.0 - Juin 2025*

## Table des matières

1. [Introduction](#introduction)
2. [Pour les administrateurs](#pour-les-administrateurs)
   - [Installation](#installation)
   - [Configuration](#configuration)
   - [Gestion des permissions utilisateur](#gestion-des-permissions-utilisateur)
   - [Maintenance du plugin](#maintenance-du-plugin)
3. [Pour les enseignants](#pour-les-enseignants)
   - [Réception des notifications d'absence](#reception-des-notifications-dabsence)
   - [Visualisation des demandes d'absence](#visualisation-des-demandes-dabsence)
   - [Filtrage et tri des demandes](#filtrage-et-tri-des-demandes)
   - [Bonnes pratiques pour les enseignants](#bonnes-pratiques-pour-les-enseignants)
4. [Pour les administrateurs de faculté](#pour-les-administrateurs-de-faculte)
   - [Accès aux rapports de faculté](#acces-aux-rapports-de-faculte)
   - [Options de filtrage des rapports](#options-de-filtrage-des-rapports)
   - [Analyse et exportation des données](#analyse-et-exportation-des-donnees)
   - [Rôle de soutien administratif](#role-de-soutien-administratif)
5. [Questions fréquentes et dépannage](#questions-frequentes-et-depannage)
6. [Confidentialité et conformité au RGPD](#confidentialite-et-conformite-au-rgpd)
7. [Annexe : Politique de considération académique](#annexe--politique-de-consideration-academique)

## Introduction

Le plugin de demande d'absence est une solution complète pour gérer les notifications d'absence des étudiants dans un environnement Moodle. Il simplifie le processus de déclaration des absences par les étudiants, de notification des enseignants et de suivi des données d'absence à des fins administratives. Le plugin soutient la politique de considération académique pour les travaux de cours manqués en fournissant un mécanisme standardisé et transparent pour soumettre et suivre les demandes d'absence.

Les principales fonctionnalités incluent :
- Demandes d'absence initiées par les étudiants avec prise en charge de différentes circonstances d'absence
- Notifications automatiques aux enseignants des cours
- Rapports complets pour les enseignants et les administrateurs de faculté
- Limites configurables de demandes par trimestre académique
- Gestion des données conforme au RGPD
- Prise en charge multilingue (anglais et français)

Ce guide explique comment utiliser le plugin du point de vue des administrateurs, enseignants et administrateurs de faculté.

## Pour les administrateurs

### Installation

Le plugin de demande d'absence s'installe comme la plupart des plugins Moodle :

1. Téléchargez le fichier zip du plugin depuis le répertoire des plugins Moodle ou la source fournie
2. Extrayez le contenu dans `/path/to/moodle/local/absence_request`
3. Connectez-vous à votre site Moodle en tant qu'administrateur
4. Accédez à Administration du site > Notifications
5. Suivez les instructions à l'écran pour terminer l'installation
6. Après l'installation, vous serez redirigé vers la page des paramètres du plugin

### Configuration

Configurez les paramètres du plugin pour qu'ils soient alignés avec les politiques d'absence de votre institution :

1. Accédez à Administration du site > Plugins > Plugins locaux > Demande d'absence
2. Configurez les paramètres suivants :
   - **Demandes par trimestre** : Définissez le nombre maximum de demandes d'absence que les étudiants peuvent soumettre par trimestre (par défaut : 2)

### Gestion des permissions utilisateur

Le plugin repose sur le système de rôles et de capacités de Moodle pour contrôler l'accès :

1. Accédez à Administration du site > Utilisateurs > Permissions > Définir les rôles
2. Pour chaque rôle pertinent (Administrateur, Enseignant, Enseignant non éditeur, Administrateur de faculté) :
   - Assurez-vous que les capacités appropriées sont attribuées :
     - `local/absence_request:view_teacher_report` : Pour que les enseignants puissent voir les rapports d'absence de leurs cours
     - `local/absence_request:view_faculty_report` : Pour que les administrateurs de faculté puissent voir les rapports de l'ensemble de la faculté

Pour créer un nouveau rôle d'administrateur de faculté :
1. Accédez à Administration du site > Utilisateurs > Permissions > Définir les rôles
2. Cliquez sur "Ajouter un nouveau rôle"
3. Définissez les permissions appropriées, en veillant à ce que `local/absence_request:view_faculty_report` soit activé
4. Attribuez ce rôle aux utilisateurs appropriés au niveau de la faculté/catégorie

### Maintenance du plugin

Une maintenance régulière garantit le bon fonctionnement du plugin :

1. **Gestion de la base de données** :
   - Le plugin stocke les données des demandes d'absence dans des tables dédiées
   - Des sauvegardes régulières sont recommandées dans le cadre de vos procédures normales de sauvegarde Moodle

2. **Mises à jour** :
   - Vérifiez périodiquement les mises à jour du plugin
   - Suivez les procédures standard de mise à jour de Moodle lorsque de nouvelles versions sont disponibles

3. **Surveillance** :
   - Examinez périodiquement les modèles d'utilisation et les volumes de demandes
   - Ajustez les paramètres de configuration si nécessaire en fonction de l'utilisation réelle

## Pour les enseignants

### Réception des notifications d'absence

Lorsqu'un étudiant soumet une demande d'absence pour un cours que vous enseignez :

1. Vous recevrez une notification par e-mail contenant :
   - Les informations de l'étudiant
   - Les dates et la durée de l'absence
   - Le type de circonstance (maladie, deuil, etc.)
   - Un lien pour consulter le rapport complet de la demande d'absence

2. Ces notifications sont envoyées à l'adresse e-mail enregistrée dans votre profil Moodle
   - Assurez-vous que vos paramètres de messagerie sont à jour dans votre profil Moodle
   - Vérifiez votre dossier spam/courrier indésirable si vous ne recevez pas les notifications

### Visualisation des demandes d'absence

Accédez aux demandes d'absence de deux manières :

1. **Via le lien dans l'e-mail** :
   - Cliquez sur le lien "Voir le rapport des demandes d'absence" dans tout e-mail de notification
   - Cela vous amène directement au rapport filtré pour vos cours
   - Dans un cours, cliquez sur l'option du menu Plus et sélectionnez "Voir le rapport d'absence"

2. **Via la navigation Moodle** :
   - Connectez-vous à Moodle
   - Accédez à Accueil du site ou Tableau de bord
   - Cliquez sur "Demande d'absence" dans le menu de navigation
   - Sélectionnez "Voir le rapport des demandes d'absence"

### Filtrage et tri des demandes

Le rapport des demandes d'absence offre plusieurs options de filtrage et de tri :

1. **Filtrer par plage de dates** :
   - Définissez "Date de début" et "Date de fin" pour voir les absences sur une période spécifique
   - Cliquez sur "Appliquer les filtres" pour mettre à jour le rapport

2. **Filtrer par cours** :
   - Sélectionnez un cours spécifique dans le menu déroulant
   - Le rapport affichera uniquement les absences pour ce cours

3. **Tri** :
   - Cliquez sur l'en-tête de n'importe quelle colonne pour trier par ce champ
   - Cliquez à nouveau pour basculer entre l'ordre croissant et décroissant

4. **Recherche** :
   - Utilisez la boîte de recherche pour trouver des étudiants ou des détails spécifiques sur les demandes

### Bonnes pratiques pour les enseignants

Lors de la réponse aux demandes d'absence :

1. **Examinez rapidement** : Vérifiez régulièrement les notifications d'absence et répondez en temps opportun
2. **Documentez les accommodements** : Enregistrez tout accommodement ou prolongation accordé
3. **Soyez cohérent** : Appliquez des normes cohérentes lors de l'examen des impacts des absences
4. **Sensibilisation à la confidentialité** : Préservez la confidentialité des étudiants lors de la discussion des détails des absences
5. **Alignement sur la politique** : Assurez-vous que votre réponse est conforme à la politique de considération académique
6. **Suivi** : Faites un suivi avec les étudiants ayant des absences prolongées

## Pour les administrateurs de faculté

### Accès aux rapports de faculté

Les administrateurs de faculté peuvent consulter des rapports agrégés pour tous les cours de leur faculté :

1. Connectez-vous à Moodle avec votre compte d'administrateur de faculté
2. Accédez à Accueil du site ou Tableau de bord
3. Cliquez sur "Demande d'absence" dans le menu de navigation (peut se trouver dans le menu "Plus")
4. Sélectionnez "Rapport d'absence de faculté"

### Options de filtrage des rapports

Le rapport de faculté inclut des options de filtrage complètes :

1. **Filtre de faculté** :
   - Sélectionnez votre faculté dans le menu déroulant (si vous avez accès à plusieurs facultés)
   - Sélectionnez "Toutes les facultés" pour voir toutes les données auxquelles vous avez accès

2. **Filtre de plage de dates** :
   - Définissez des dates de début et de fin spécifiques pour analyser les absences sur une période donnée
   - Utile pour examiner les modèles pendant des périodes spécifiques du trimestre

3. **Filtre de cours** :
   - Filtrez par cours spécifiques au sein de la faculté
   - Aide à identifier les cours avec des taux d'absence plus élevés

4. **Filtre de type de circonstance** :
   - Filtrez par type de circonstance d'absence
   - Utile pour surveiller les tendances des raisons d'absence

### Analyse et exportation des données

Les rapports de faculté peuvent être utilisés à diverses fins administratives :

1. **Exportation des données** :
   - Utilisez le bouton "Télécharger" pour exporter le rapport dans divers formats (CSV, Excel, PDF)
   - Les données exportées peuvent être utilisées pour des analyses supplémentaires ou des archives

2. **Identification des modèles** :
   - Surveillez les tendances d'absence dans les cours et départements
   - Identifiez les périodes avec des taux d'absence plus élevés
   - Suivez les circonstances d'absence les plus courantes

3. **Rapports administratifs** :
   - Générez des résumés pour les réunions de faculté ou les examens administratifs
   - Soutenez les décisions d'allocation des ressources avec les données d'absence

### Rôle de soutien administratif

Les administrateurs de faculté jouent un rôle clé dans le soutien des étudiants et des enseignants :

1. **Communication des politiques** :
   - Assurez-vous que les membres de la faculté comprennent la politique de considération académique
   - Fournissez des conseils sur la manière dont les demandes d'absence doivent être traitées

2. **Soutien à la faculté** :
   - Aidez les enseignants à interpréter les données d'absence
   - Fournissez des conseils pour des cas complexes ou des circonstances inhabituelles

3. **Soutien aux étudiants** :
   - Orientez les étudiants vers les ressources appropriées si nécessaire
   - Aidez à résoudre les problèmes liés au processus de demande d'absence

4. **Gestion du système** :
   - Surveillez l'efficacité du système de demande d'absence
   - Fournissez des retours aux administrateurs sur les améliorations potentielles

## Questions fréquentes et dépannage

**Q : Un enseignant signale ne pas recevoir les notifications d'absence. Que dois-je vérifier ?**
R : Vérifiez les paramètres de messagerie Moodle de l'enseignant, assurez-vous qu'il est correctement assigné en tant qu'enseignant dans le cours et que son compte utilisateur dispose des permissions correctes.

**Q : Les administrateurs de faculté peuvent-ils modifier ou supprimer des demandes d'absence ?**
R : Non, le système est conçu comme un outil de tenue de registres. Les administrateurs peuvent consulter mais pas modifier les demandes soumises afin de maintenir l'intégrité des données.

**Q : Comment les demandes d'absence sont-elles gérées pour les cours croisés ?**
R : Lorsqu'un étudiant soumet une demande d'absence, des notifications sont envoyées aux enseignants de tous les cours dans lesquels l'étudiant est inscrit pendant la période spécifiée.

**Q : Que se passe-t-il si un étudiant dépasse le nombre maximum de demandes par trimestre ?**
R : Le système empêchera la soumission et affichera un message indiquant qu'il a atteint le nombre maximum de demandes autorisées.

**Q : Le système peut-il être configuré pour autoriser plus de deux demandes par trimestre ?**
R : Oui, les administrateurs peuvent ajuster le paramètre "Demandes par trimestre" dans la configuration du plugin.

## Confidentialité et conformité au RGPD

Le plugin de demande d'absence est conçu dans le respect de la confidentialité :

1. **Collecte de données** :
   - Seules les données personnelles nécessaires sont collectées dans le but légitime de traiter les demandes d'absence
   - La rétention des données suit les politiques institutionnelles et les exigences du RGPD

2. **Capacités RGPD** :
   - Le plugin s'intègre à l'API de confidentialité de Moodle
   - Prend en charge les demandes d'exportation de données des utilisateurs
   - Prend en charge les demandes de suppression de données lorsque cela est approprié

3. **Contrôles d'accès** :
   - Les enseignants ne peuvent consulter que les données d'absence de leurs propres cours
   - Les administrateurs de faculté ne peuvent consulter que les données de leur faculté assignée
   - Tous les accès sont enregistrés via le système de journalisation standard de Moodle

4. **Bonnes pratiques** :
   - Traitez toutes les informations d'absence comme confidentielles
   - Discutez des détails spécifiques des absences uniquement avec le personnel concerné
   - Utilisez les canaux de communication institutionnels pour les discussions sensibles

## Annexe : Politique de considération académique

Le plugin de demande d'absence soutient la politique de considération académique pour les travaux de cours manqués. Pour des détails complets sur cette politique, veuillez consulter le document officiel disponible dans le répertoire du plugin :

`20250227_Senate_approved_Acad Consid_for_Missed_Course_Work_Policy_Final.pdf`

Cette politique décrit :
- Les critères d'éligibilité pour la considération académique
- Les types de circonstances qui qualifient pour une considération
- Les exigences en matière de documentation
- Les responsabilités des étudiants, enseignants et administrateurs
- Les processus d'appel

Tous les utilisateurs du système de demande d'absence doivent se familiariser avec cette politique pour garantir une mise en œuvre et une adhésion appropriées.

---

*Ce guide d'utilisation a été créé pour le plugin Moodle de demande d'absence développé par UIT Innovation © 2025.*
