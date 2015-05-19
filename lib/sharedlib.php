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

function death($message) {
    global $OUTPUT;
    echo '<div class="alert alert-danger">' . $message . '</div>';
    echo $OUTPUT->footer();
    die();
}

// This stuff basically manages the permissions and redirecting.
function is_admin() {
    if (has_capability('moodle/site:config', context_system::instance())) {
        return true;
    }
}

function cohort_is_member_by_idnumber($cohortidnumber, $userid) {
    global $DB;
    if ($cohort = $DB->get_record('cohort', array('idnumber' => $cohortidnumber))) {
        return $DB->record_exists('cohort_members', array('cohortid' => $cohort->id, 'userid' => $userid));
    }
    return false;
}

function is_activities_head() {
    global $USER;
    return cohort_is_member_by_idnumber('activitiesHEAD', $USER->id);
}

function is_secretary() {
    global $USER;
    if (is_admin()) {
        return true;
    }
    return cohort_is_member_by_idnumber('secretariesALL', $USER->id);
}

function is_teacher() {
    global $USER;
    if (is_admin()) {
        return true;
    }
    return cohort_is_member_by_idnumber('teachersALL', $USER->id);
}

function is_student() {
    global $USER;
    if (is_admin()) {
        return true;
    }
    return cohort_is_member_by_idnumber('studentsALL', $USER->id);
}

function is_parent() {
    global $USER;
    if (is_admin()) {
        return true;
    }
    return cohort_is_member_by_idnumber('parentsALL', $USER->id);
}

function sign($icon, $bigtext, $littletext) {
    echo '<div class="local-alert">';
        echo '<i class="fa fa-4x fa-' . $icon . ' pull-left"></i>';
        echo '<p style="font-size:18px;font-weight:bold;">' . $bigtext  .'</p>';
        echo $littletext;
    echo '</div>';
}
