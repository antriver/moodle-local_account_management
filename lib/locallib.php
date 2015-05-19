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

function derive_plugin_path_from($stem) {
    return "/local/account_management/{$stem}";
}

function setup_page() {
    global $PAGE;
    global $OUTPUT;

    $PAGE->set_context(context_system::instance());
    $PAGE->set_url(derive_plugin_path_from('index.php'));
    $PAGE->set_title("DragonNet Account Management");
    $PAGE->set_heading("DragonNet Account Management");

    $PAGE->add_body_class('account-management');
    $PAGE->requires->css('/local/account_management/assets/css/account-management.css');
    $PAGE->requires->jquery();
    $PAGE->requires->jquery_plugin('ui');
    $PAGE->requires->jquery_plugin('ui-css');

    echo $OUTPUT->header();
}


/**
 * method masks the username of an email address
 *
 * @param string $email the email address to mask
 * @param string $maskchar the character to use to mask with
 * @param int $percent the percent of the username to mask
 */
function mask_email($email, $maskchar='*', $percent=50) {
    list( $user, $domain ) = preg_split("/@/", $email );
    $len = strlen( $user );
    $maskcount = floor( $len * $percent / 100 );
    $offset = floor( ( $len - $maskcount ) / 2 );
    $masked = substr( $user, 0, $offset )
            .str_repeat( $maskchar, $maskcount )
            .substr( $user, $maskcount + $offset );
    return $masked . '@' . $domain;
}

function get_user_type($user) {
    $kind = "Other";
    if (strpos($user->email, '@student.ssis-suzhou.net') !== false) {
        $kind = "Student, ".$user->department;
    } else if (strpos($user->idnumber, 'P') === 4) {
        // We check for parent before staff, because some parents will use their school email address
        $kind = "Parent";
    } else if (strpos($user->email, "@ssis-suzhou.net") !== false) {
        $kind = "Staff";
    }
    return $kind;
}
