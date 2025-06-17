# Moodle Absence Request Plugin

## Overview
The Absence Request plugin is a local Moodle plugin that allows students to submit absence requests for courses they are enrolled in. Teachers are notified of these requests and can view them in a consolidated report. Faculty administrators can also view reports of absence requests for their faculty.

## Features
- Students can submit absence requests for all courses they are enrolled in
- Supports multiple types of absence circumstances (health issues, bereavement, unforeseen circumstances)
- Limit on the number of requests per term (configurable)
- Automatic notifications to course teachers when an absence is submitted
- Reports for teachers to view absences for their courses
- Faculty-level reports for administrative staff
- Full multilingual support (English and French included)
- GDPR compliant with privacy API implementation

## Requirements
- Moodle 4.0 or higher
- PHP 7.4 or higher

## Installation
### Manual Installation
1. Download the plugin zip file
2. Extract the contents to `/path/to/moodle/local/absence_request`
3. Log in to your Moodle site as an admin
4. Go to Site administration > Notifications
5. Follow the on-screen instructions to complete the installation

## Configuration
After installation, you can configure the plugin settings:

1. Navigate to Site administration > Plugins > Local plugins > Absence Request
2. Configure the following settings:
   - Maximum number of requests per term
   - Default messages for notifications to students and teachers

## Usage
### For Students
1. Log in to Moodle
2. Navigate to the Absence Request link in the navigation menu
3. Fill out the absence request form with:
   - Type of circumstance
   - Start and end dates of absence
4. Submit the request

### For Teachers
1. Teachers will receive notifications when a student in their course submits an absence request
2. They can view all absence requests for their courses through the "View Absence Requests Report" link found in the email they received
### For Faculty Administrators
1. Faculty administrators can view all absence requests for their faculty through the "Faculty Absence Report" link found on the home page. Note: the link may be in the more menu.
2. They can filter requests by various criteria such as date range, faculty, and course

## Privacy
This plugin stores personal data and is compliant with the GDPR through Moodle's Privacy API. The plugin:
- Stores student absence request data
- Records teacher associations with absence requests
- Allows for data export and deletion in compliance with GDPR requirements

## License
This plugin is licensed under the GNU GPL v3 or later. See the LICENSE file for details.

## Support
For support or to report issues, please visit the plugin's GitHub repository or contact the plugin maintainer.

## Credits
Developed by UIT Innovation © 2025

