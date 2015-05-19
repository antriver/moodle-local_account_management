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

$results = array();

if ($term) {
    // Query is being performed
    $term = str_replace(' ', '', strtolower($term));
    $params = array();

    $sql = "
SELECT
    cat.name, cat.id
FROM
    {course_categories} cat
WHERE
    REPLACE(LOWER(cat.name), ' ', '') LIKE ?
";

    // query that gets any match of an activity by its fullname
    $params[] = '%'.$term.'%';

    $sort = 'name';
    $fields = 'name, id';

    // execute the query, and step through them
    $activities = $DB->get_records_sql($sql, $params);
    foreach ($activities as $row) {
        $results[] = array(
            "label" => $row->name,
            "value" => $row->id
            );

    }

}

echo json_encode($results);
