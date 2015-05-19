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

require_once('../../config.php');

require_login();

$userid = required_param('userid', PARAM_RAW);
$changeusernameto = optional_param('change_username_to', '', PARAM_RAW);
$changefirstnameto = optional_param('change_firstname_to', '', PARAM_RAW);
$changelastnameto = optional_param('change_lastname_to', '', PARAM_RAW);

if (!empty($changeusernameto)) {
    // First, determine if this is an account where the last name is the same as the email address
    // This is a legacy thing, might as well keep the system consistent
    // There is no real reason for making the lastname the same as the email address, but because powerschool doesn't have
    // mother or father's names (when I designed dragonnet originally) I had to make due

    $user = $DB->get_record('user', array("id" => $userid));
    if ($user->lastname == $user->email) {
        $alsochangelastname = true;
    }

    // This is the main update here:

    $DB->update_record('user', array(
        "id" => $userid,
        "email" => $changeusernameto,
        "username" => $changeusernameto
        ));

    // Now also update the lastname if we have an account as described above.

    if ($alsochangelastname) {
        $DB->update_record('user', array(
            "id" => $userid,
            "lastname" => $changeusernameto
        ));
    }

    echo 'worked!';
}


if (!empty($changefirstnameto)) {
    // First, determine if this is an account where the last name is the same as the email address
    // This is a legacy thing, might as well keep the system consistent
    // There is no real reason for making the lastname the same as the email address, but because powerschool doesn't have
    // mother or father's names (when I designed dragonnet originally) I had to make due

    $DB->update_record('user', array(
        "id" => $userid,
        "firstname" => $changefirstnameto
        ));
}

if (!empty($changelastnameto)) {
    // First, determine if this is an account where the last name is the same as the email address
    // This is a legacy thing, might as well keep the system consistent
    // There is no real reason for making the lastname the same as the email address, but because powerschool doesn't have
    // mother or father's names (when I designed dragonnet originally) I had to make due

    $DB->update_record('user', array(
        "id" => $userid,
        "lastname" => $changelastnameto
        ));
}
