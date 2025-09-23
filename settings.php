<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

// This file defines settings for the local_absence_request plugin.

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_absence_request', get_string('pluginname', 'local_absence_request'));

    $settings->add(new admin_setting_configcheckbox(
        'local_absence_request/enabled',
        get_string('enabled', 'local_absence_request'),
        get_string('enabled_desc', 'local_absence_request'),
        1
    ));

    $settings->add(new admin_setting_configtext(
        'local_absence_request/requests_per_term',
        get_string('requests_per_term', 'local_absence_request'),
        get_string('requests_per_term_desc', 'local_absence_request'),
        2,
        PARAM_INT
    ));

    $settings->add(new admin_setting_configselect(
        'local_absence_request/use_period',
        get_string('use_period', 'local_absence_request'),
        get_string('use_period_desc', 'local_absence_request'),
        'no',
        [
            'no' => get_string('use_period_no', 'local_absence_request'),
            'F' => get_string('use_period_F', 'local_absence_request'),
            'W' => get_string('use_period_W', 'local_absence_request'),
            'S' => get_string('use_period_S', 'local_absence_request'),
            'Y' => get_string('use_period_Y', 'local_absence_request'),
        ]
    ));

    $settings->add(new admin_setting_configtext(
        'local_absence_request_academic_year',
        get_string('academic_year', 'local_absence_request'),
        get_string('academic_year_desc', 'local_absence_request'),
        '',
        PARAM_RAW
    ));

    $settings->add(new admin_setting_configselect(
        'local_absence_request/enrollment_methods',
        get_string('enrollment_methods', 'local_absence_request'),
        get_string('enrollment_methods_desc', 'local_absence_request'),
        'arms',
        [
            'all' => get_string('enrollment_methods_all', 'local_absence_request'),
            'manual' => get_string('enrollment_methods_manual', 'local_absence_request'),
            'arms' => get_string('enrollment_methods_arms', 'local_absence_request'),
        ]
    ));

    $settings->add(new admin_setting_configpasswordunmask(
        'local_absence_request/passwordsaltmain',
        get_string('passwordsaltmain', 'local_absence_request'),
        get_string('passwordsaltmain_desc', 'local_absence_request'),
        ''
    ));

    $ADMIN->add('localplugins', $settings);
}
