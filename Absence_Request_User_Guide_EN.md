# Absence Request Plugin User Guide

**For Administrators, Teachers, and Faculty Administrators**

*Version 1.0 - June 2025*

## Table of Contents

1. [Introduction](#introduction)
2. [For Administrators](#for-administrators)
   - [Installation](#installation)
   - [Configuration](#configuration)
   - [Managing User Permissions](#managing-user-permissions)
   - [Plugin Maintenance](#plugin-maintenance)
3. [For Teachers](#for-teachers)
   - [Receiving Absence Notifications](#receiving-absence-notifications)
   - [Viewing Absence Requests](#viewing-absence-requests)
   - [Filtering and Sorting Requests](#filtering-and-sorting-requests)
   - [Best Practices for Teachers](#best-practices-for-teachers)
4. [For Faculty Administrators](#for-faculty-administrators)
   - [Accessing Faculty Reports](#accessing-faculty-reports)
   - [Report Filtering Options](#report-filtering-options)
   - [Data Analysis and Export](#data-analysis-and-export)
   - [Administrative Support Role](#administrative-support-role)
5. [Common Questions and Troubleshooting](#common-questions-and-troubleshooting)
6. [Privacy and GDPR Compliance](#privacy-and-gdpr-compliance)
7. [Appendix: Academic Consideration Policy](#appendix-academic-consideration-policy)

## Introduction

The Absence Request plugin is a comprehensive solution for managing student absence notifications in a Moodle environment. It streamlines the process of students reporting absences, notifying instructors, and tracking absence data for administrative purposes. The plugin supports the Academic Consideration for Missed Course Work Policy by providing a standardized and transparent mechanism for submitting and tracking absence requests.

Key features include:
- Student-initiated absence requests with support for different absence circumstances
- Automatic notifications to course instructors
- Comprehensive reporting for teachers and faculty administrators
- Configurable request limits per academic term
- GDPR-compliant data management
- Multi-language support (English and French)

This guide explains how to use the plugin from the perspective of Administrators, Teachers, and Faculty Administrators.

## For Administrators

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
   - Navigate to Site Home or Dashboard
   - Click on "Absence Request" in the navigation menu
   - Select "View Absence Requests Report"

### Filtering and Sorting Requests

The absence requests report offers several filtering and sorting options:

1. **Filter by Date Range**:
   - Set "From Date" and "To Date" to view absences within a specific period
   - Click "Apply Filters" to update the report

2. **Filter by Course**:
   - Select a specific course from the dropdown menu
   - The report will show only absences for that course

3. **Sorting**:
   - Click on any column header to sort by that field
   - Click again to toggle between ascending and descending order

4. **Searching**:
   - Use the search box to find specific students or request details

### Best Practices for Teachers

When responding to absence requests:

1. **Review promptly**: Check absence notifications regularly and respond in a timely manner
2. **Document accommodations**: Record any accommodations or extensions granted
3. **Be consistent**: Apply consistent standards when considering absence impacts
4. **Privacy awareness**: Maintain student privacy when discussing absence details
5. **Policy alignment**: Ensure your response aligns with the Academic Consideration Policy
6. **Follow up**: Check in with students who have extended absences

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

3. **Course Filter**:
   - Filter by specific courses within the faculty
   - Helps identify courses with higher absence rates

4. **Circumstance Type Filter**:
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
