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
 * @package    local_account_management
 * @copyright  Adam Morris <www.mistermorris.com> and Anthony Kuske <www.anthonykuske.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../../config.php');
header('Content-type: application/json');

$term = optional_param('term', false, PARAM_RAW);

if ($term) {

    // Query is being performed
    $term = str_replace(' ', '', strtolower($term));
    $results = array();
    $params = array();

    // query that gets any match of firstname, lastname, or homeroom
    // and ensures that everything returned is a teacher
    $where = "
(
    deleted = 0 AND
    idnumber != '' AND
    email LIKE '%@ssis-suzhou.net' AND
        (
            LOWER(department) = ? OR
            REPLACE(CONCAT(LOWER(firstname), LOWER(lastname)),  ' ', '') LIKE ? OR
            REPLACE(CONCAT(LOWER(lastname),  LOWER(firstname)), ' ', '') LIKE ? OR
            REPLACE(LOWER(firstname), ' ', '') LIKE ? OR
            LOWER(lastname) LIKE ?
        )
)
";
    $params[] = $term;
    $params[] = $term.'%';
    $params[] = $term.'%';
    $params[] = $term.'%';
    $params[] = $term.'%';

    $sort = 'firstname, lastname, department';
    $fields = 'id, idnumber, lastname, firstname, department';

    // execute the query, and step through them
    $teachers = $DB->get_records_select("user", $where, $params, $sort, $fields);
    foreach ($teachers as $row) {
        $results[] = array(
            "label" => "{$row->firstname} {$row->lastname}",
            "value" => $row->idnumber
            );

    }

    echo json_encode($results);
}
