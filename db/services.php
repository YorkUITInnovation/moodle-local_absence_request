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
 * Web service external functions and service definitions.
 *
 * @package    local_absence_request
 * @copyright  2025
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// We defined the web service functions to install.
$functions = array(
    'local_absence_request_acknowledge' => array(
        'classname'   => 'local_absence_request_teacher',
        'methodname'  => 'acknowledge',
        'classpath'   => 'local/absence_request/classes/external/teacher_ws.php',
        'description' => 'Toggle acknowledged field for teacher absence request',
        'type'        => 'write',
        'ajax'        => true,
        'capabilities' => 'local/absence_request:acknowledge'
    )
);
