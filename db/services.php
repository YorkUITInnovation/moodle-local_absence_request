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

/**
 * Web service function definitions for absence request plugin.
 *
 * @package    local_absence_request
 * @copyright  2025 Your Institution
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_absence_request_acknowledge_request' => [
        'classname'   => 'local_absence_request\external\teacher_ws',
        'methodname'  => 'acknowledge_request',
        'classpath'   => '',
        'description' => 'Acknowledge or unacknowledge a single absence request',
        'type'        => 'write',
        'ajax'        => true,
        'loginrequired' => true,
    ],
    'local_absence_request_bulk_acknowledge' => [
        'classname'   => 'local_absence_request\external\teacher_ws',
        'methodname'  => 'bulk_acknowledge',
        'classpath'   => '',
        'description' => 'Bulk acknowledge multiple absence requests',
        'type'        => 'write',
        'ajax'        => true,
        'loginrequired' => true,
    ],
];
