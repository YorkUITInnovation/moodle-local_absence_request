# Absence Request Plugin User Guide

**For Students, Administrators, Teachers, and Faculty Administrators**

*Version 1.0 - June 2025*

## Table of Contents

1. [Introduction](#introduction)
2. [For Students](#for-students)
   - [Eligibility Requirements](#eligibility-requirements)
   - [Submitting an Absence Request](#submitting-an-absence-request)
   - [Understanding Request Limits](#understanding-request-limits)
   - [After Submission](#after-submission)
   - [Best Practices for Students](#best-practices-for-students)
3. [For Administrators](#for-administrators)
   - [Installation](#installation)
   - [Configuration](#configuration)
   - [Managing User Permissions](#managing-user-permissions)
   - [Plugin Maintenance](#plugin-maintenance)
4. [For Teachers](#for-teachers)
   - [Receiving Absence Notifications](#receiving-absence-notifications)
   - [Viewing Absence Requests](#viewing-absence-requests)
   - [Filtering and Sorting Requests](#filtering-and-sorting-requests)
   - [Best Practices for Teachers](#best-practices-for-teachers)
5. [For Faculty Administrators](#for-faculty-administrators)
   - [Accessing Faculty Reports](#accessing-faculty-reports)
   - [Report Filtering Options](#report-filtering-options)
   - [Data Analysis and Export](#data-analysis-and-export)
   - [Administrative Support Role](#administrative-support-role)
6. [Common Questions and Troubleshooting](#common-questions-and-troubleshooting)
7. [Privacy and GDPR Compliance](#privacy-and-gdpr-compliance)
8. [Appendix: Academic Consideration Policy](#appendix-academic-consideration-policy)

## Introduction

The Absence Request plugin is a comprehensive solution for managing student absence notifications in a Moodle environment. It streamlines the process of students reporting absences, notifying instructors, and tracking absence data for administrative purposes. The plugin supports the Academic Consideration for Missed Course Work Policy by providing a standardized and transparent mechanism for submitting and tracking absence requests.

Key features include:
- Student-initiated absence requests with support for different absence circumstances
- Automatic notifications to course instructors
- Comprehensive reporting for teachers and faculty administrators
- Configurable request limits per academic term
- GDPR-compliant data management
- Multi-language support (English and French)

This guide explains how to use the plugin from the perspective of Students, Administrators, Teachers, and Faculty Administrators.

## For Students

### Eligibility Requirements

To be eligible to submit an absence request:

1. **Enrollment Status**:
   - You must be currently enrolled in at least one course for the current academic term
   - The system will automatically check your enrollment status

2. **Request Timing**:
   - Requests must be submitted during the current academic term
   - Requests cannot be submitted for past terms or future terms
   - Both the start and end dates of your absence must fall within the current term

3. **Faculty Affiliation**:
   - Your faculty affiliation is automatically determined from your student profile
   - No additional action is required to specify your faculty

### Submitting an Absence Request

To submit an absence request:

1. **Access the Request Form**:
   - Log in to Moodle
   - Navigate to Site Home
   - Click on "Absence Request" in the navigation menu
   - Alternatively, you can access the form from within any of your courses by clicking on "Absence Request" in the course navigation

2. **Complete the Request Form**:
   - **Type of Circumstance**: Select the appropriate reason for your absence:
     - Short-term health conditions (illness, physical injury, scheduled surgery)
     - Bereavement of an immediate family member
     - Unforeseen or unavoidable incidents beyond your control
   - **Absence Start Date and Time**: Select the date and time when your absence begins
   - **Absence End Date and Time**: Select the date and time when your absence ends

3. **Form Validation**:
   - The system will automatically validate your request against the following criteria:
     - The end date cannot be before the start date
     - The absence duration cannot exceed 7 days
     - The dates must fall within the current academic term
     - You cannot submit duplicate requests for the same date range

4. **Submit the Request**:
   - Review your information for accuracy
   - Click the "Submit Absence Request" button
   - You will receive a confirmation message upon successful submission

### Understanding Request Limits

The Absence Request system has specific limitations:

1. **Requests Per Term**:
   - You can submit a maximum of two (2) absence requests per academic term
   - The system will prevent submission of additional requests once you reach this limit
   - This limit is set by institutional policy and cannot be overridden

2. **Duration Limits**:
   - Each absence request cannot exceed 7 consecutive days
   - For absences longer than 7 days, please contact your academic advisor for guidance

3. **Academic Year Boundaries**:
   - Requests cannot span across different academic terms
   - Both the start and end dates must be within the same term period

### After Submission

Once you've submitted your absence request:

1. **Automatic Notifications**:
   - You will receive an email confirmation of your submission
   - All instructors for courses you are enrolled in during the specified absence period will be automatically notified

2. **Course Accommodations**:
   - Instructors will review your absence notification
   - Follow up with individual instructors regarding specific missed assignments or assessments
   - Be prepared to discuss make-up work or alternative arrangements upon your return

3. **Record Keeping**:
   - Your absence request is stored in the system for the current academic year
   - You can view your submitted requests by clicking on the "Alerts" icon (bell) in the top right corner of the main navigation bar.
   - The system maintains a record of all notifications sent to instructors

### Best Practices for Students

To effectively use the Absence Request system:

1. **Submit Promptly**: Submit your request as soon as you know you'll be absent
2. **Be Accurate**: Ensure your absence dates are accurate and include all affected days
3. **Follow Up**: Contact instructors directly about specific missed work after they receive the notification
4. **Documentation**: Keep any supporting documentation (medical notes, etc.) for your records
5. **Plan Ahead**: Be mindful of the two-request limit per term
6. **Communication**: Use the absence request system in conjunction with direct communication with your instructors
7. **Policy Awareness**: Familiarize yourself with the Academic Consideration Policy to understand your rights and responsibilities

## For Moodle Administrators

### Installation

The Absence Request plugin is installed like most Moodle plugins:

1. Download the plugin zip file from the Moodle plugins directory or provided source
2. Extract the contents to `/path/to/moodle/local/absence_request`
3. Log in to your Moodle site as an administrator
4. Navigate to Site administration > Notifications
5. Follow the on-screen instructions to complete the installation
6. After installation, you will be directed to the plugin settings page

### Configuration

Configure the plugin settings to align with your institution's absence policies:

1. Navigate to Site administration > Plugins > Local plugins > Absence Request
2. Configure the following settings:
   - **Requests per term**: Set the maximum number of absence requests students can submit per term (default: 2)

### Managing User Permissions

The plugin relies on Moodle's role and capability system to control access:

1. Navigate to Site administration > Users > Permissions > Define roles
2. For each relevant role (Administrator, Teacher, Non-editing teacher, Faculty Administrator):
   - Ensure appropriate capabilities are assigned:
     - `local/absence_request:view_teacher_report`: For teachers to view absence reports for their courses
     - `local/absence_request:view_faculty_report`: For faculty administrators to view faculty-wide reports

To create a new Faculty Administrator role:
1. Navigate to Site administration > Users > Permissions > Define roles
2. Click "Add a new role"
3. Set appropriate permissions, ensuring `local/absence_request:view_faculty_report` is enabled
4. Assign this role to appropriate users at the faculty/category level

### Plugin Maintenance

Regular maintenance ensures the plugin continues to function effectively:

1. **Database Management**:
   - The plugin stores absence request data in dedicated tables
   - Regular backups are recommended as part of your normal Moodle backup procedures

2. **Updates**:
   - Check for plugin updates periodically
   - Follow standard Moodle update procedures when new versions are available

3. **Monitoring**:
   - Review usage patterns and request volumes periodically
   - Adjust configuration settings if needed based on actual usage

## For Teachers

### Receiving Absence Notifications

When a student submits an absence request for a course you teach:

1. You will receive an email notification with:
   - Student information
   - Absence dates and duration
   - Type of circumstance (illness, bereavement, etc.)
   - A link to view the full absence request report

2. These notifications are sent to your Moodle registered email address
   - Ensure your email settings are up to date in your Moodle profile
   - Check your spam/junk folder if you're missing notifications

### Viewing Absence Requests

Access absence requests in two ways:

1. **Via Email Link**:
   - Click the "View Absence Requests Report" link in any notification email
   - This takes you directly to the report filtered for your courses
   - Wihtin a course, click on the More menu option and select "View Absence Report"

2. **Via Moodle Navigation**:
   - Log in to Moodle
   - Navigate to Site Home
   - Click on "Absence Request" in the navigation menu
   - Select "View Absence Requests Report"

### Filtering and Sorting Requests

The absence requests report offers several filtering and sorting options:

1. **Filter by Date Range**:
   - Set "From Date" and "To Date" to view absences within a specific period
   - Click "Apply Filters" to update the report

2. **Sorting**:
   - Click on any column header to sort by that field
   - Click again to toggle between ascending and descending order

3. **Searching**:
   - Use the search box to find specific students or request details

## For Faculty Administrators

### Accessing Faculty Reports

Faculty Administrators can view aggregated reports for all courses within their faculty:

1. Log in to Moodle with your Faculty Administrator account
2. Navigate to Site Home or Dashboard
3. Click on "Absence Request" in the navigation menu (may be in the "More" menu)
4. Select "Faculty Absence Report"

### Report Filtering Options

The Faculty Report includes comprehensive filtering options:

1. **Faculty Filter**:
   - Select your faculty from the dropdown (if you have access to multiple faculties)
   - Select "All Faculties" to view all data you have permission to access

2. **Date Range Filter**:
   - Set specific start and end dates to analyze absences within a timeframe
   - Useful for examining patterns during specific term periods

3. **Circumstance Type Filter**:
   - Filter by the type of absence circumstance
   - Useful for monitoring trends in absence reasons

### Data Analysis and Export

Faculty reports can be used for various administrative purposes:

1. **Exporting Data**:
   - Use the "Download" button to export the report in various formats (CSV, Excel, PDF)
   - Exported data can be used for further analysis or record-keeping

2. **Identifying Patterns**:
   - Monitor absence trends across courses and departments
   - Identify periods with higher absence rates
   - Track the most common absence circumstances

3. **Administrative Reports**:
   - Generate summaries for faculty meetings or administrative reviews
   - Support resource allocation decisions with absence data

### Administrative Support Role

Faculty Administrators play a key role in supporting both students and faculty:

1. **Policy Communication**:
   - Ensure faculty members understand the Academic Consideration Policy
   - Provide guidance on how absence requests should be handled

2. **Faculty Support**:
   - Assist teachers with interpreting absence data
   - Provide guidance for complex cases or unusual circumstances

3. **Student Support**:
   - Direct students to appropriate resources when needed
   - Help resolve issues with the absence request process

4. **System Management**:
   - Monitor the effectiveness of the absence request system
   - Provide feedback to administrators on potential improvements

## Common Questions and Troubleshooting

### For Students

**Q: I need to be absent for more than 7 days. What should I do?**
A: For absences longer than 7 days, you should contact your academic advisor or faculty office directly. They can provide guidance on the appropriate documentation and process for extended absences.

**Q: I've already submitted two absence requests this term, but I need to submit another one. Is this possible?**
A: The system is limited to two requests per term as per institutional policy. For exceptional circumstances, please contact your faculty office to discuss alternative arrangements.

**Q: Will my instructors automatically grant extensions for missed assignments?**
A: The absence request system only notifies instructors of your absence. You should follow up directly with each instructor to discuss specific accommodations for missed work.

**Q: I submitted a request with incorrect dates. Can I edit or delete it?**
A: Once submitted, requests cannot be edited or deleted. If you need to correct information, contact your instructors directly to explain the error.

**Q: Do I need to submit medical documentation with my absence request?**
A: No, the system does not require or accept documentation uploads. However, you should keep any supporting documentation for your records in case it's requested later.

**Q: How do I access the Absence Report from Schulich Canvas?**
A: From within Schulich Canvas, you can add a link with the query string `?r=sb` to the end of the Absence Request plugin URL (for example: `https://eclass.yorku.ca/local/absence_request/index.php?r=sb`).
When a student completes the Absence Request form, a button "Back to my courses" is available. Clicking this button will redirect the student to Schulich Canvas, allowing them to continue their work seamlessly.

### For Teachers and Administrators

**Q: A teacher reports not receiving absence notifications. What should I check?**
A: Verify their Moodle email settings, check that they are correctly assigned as a teacher in the course, and ensure their user account has the correct permissions.

**Q: Can faculty administrators modify or delete absence requests?**
A: No, the system is designed as a record-keeping tool. Administrators can view but not modify submitted requests to maintain data integrity.

**Q: How are absence requests handled for cross-listed courses?**
A: When a student submits an absence request, notifications are sent to teachers in all courses the student is enrolled in during the specified period.

**Q: What happens if a student exceeds the maximum number of requests per term?**
A: The system will prevent submission and display a message indicating they've reached the maximum allowed requests.

**Q: Can the system be configured to allow more than two requests per term?**
A: Yes, administrators can adjust the "Requests per term" setting in the plugin configuration.

## Privacy and GDPR Compliance

The Absence Request plugin is designed with privacy in mind:

1. **Data Collection**:
   - Only necessary personal data is collected for the legitimate purpose of processing absence requests
   - Data retention follows institutional policies and GDPR requirements

2. **GDPR Capabilities**:
   - The plugin integrates with Moodle's Privacy API
   - Supports data export requests from users
   - Supports data deletion requests where appropriate

3. **Access Controls**:
   - Teachers can only view absence data for their own courses
   - Faculty administrators can only view data for their assigned faculty
   - All access is logged through Moodle's standard logging system

4. **Best Practices**:
   - Treat all absence information as confidential
   - Discuss specific absence details only with relevant personnel
   - Use institutional communication channels for sensitive discussions

## Appendix: Academic Consideration Policy

The Absence Request plugin supports the Academic Consideration for Missed Course Work Policy. For full details on this policy, please refer to the official policy document available in the plugin directory:

`20250227_Senate_approved_Acad Consid_for_Missed_Course_Work_Policy_Final.pdf`

This policy outlines:
- Eligibility criteria for academic consideration
- Types of circumstances that qualify for consideration
- Documentation requirements
- Responsibilities of students, faculty, and administrators
- Appeals processes

All users of the Absence Request system should familiarize themselves with this policy to ensure proper implementation and adherence.

---

*This user guide was created for the Moodle Absence Request Plugin developed by UIT Innovation © 2025.*
