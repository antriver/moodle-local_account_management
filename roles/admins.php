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
require_once('../lib/sharedlib.php');
require_once('../lib/locallib.php');
require_once('../lib/output.php');

setup_page();

output_tabs('Admin');

if (!is_admin()) {
    death("This section is for DragonNet administrators only.");
}

$table = new html_table();
$table->attributes['class'] = 'table table-striped userinfotable';
$table->data = array();
$table->head = array("User", "Date requested", "Email link clicked?");

echo '<h2>Parent Password Self-Reset Information</h2>';
echo '<p>Sorted by latest activity on top.</p>';

foreach (array_reverse($DB->get_records('acct_mgmt_pw_reset_keys', array(), $sort = 'time')) as $dbrow) {

    $user = $DB->get_record('user', array("id" => $dbrow->userid));

    $row = new html_table_row();

    $row->cells[0] = new html_table_cell();
    $row->cells[0]->text .= $user->idnumber. ': '. $user->firstname . ' ' . $user->lastname;

    $row->cells[1] = new html_table_cell();
    $row->cells[1]->text .= date('F d, Y', $timestamp = $dbrow->time);

    $row->cells[2] = new html_table_cell();
    $row->cells[2]->text .= $dbrow->used ? '<span class="label label-success">Yes</span>' : '<span class="label label-important">No</span>';

    $table->data[] = $row;

}

echo html_writer::table($table);

echo $OUTPUT->footer();
