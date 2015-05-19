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

$powerschoolid = optional_param('powerschool', '', PARAM_RAW);
if (!empty($powerschoolid)) {
    $user = $DB->get_record('user', array('idnumber' => $powerschoolid));
    if (!$user) {
        death("Sorry, it seems like there is a problem with your account. Please contact help@ssis-suzhou.net with the name of your child(ren).");
    }
    $familyid = substr($powerschoolid, 0, 4);
}
$resetpassword = optional_param('reset_password', '', PARAM_RAW);
$email = optional_param('email', '', PARAM_RAW);

output_tabs('For: Parents');

if (isloggedin()) {
    echo '<div class="alert alert-danger">This section is intended for parents to look up their username and to reset their passwords. You have to be logged out to use it.</div>';
    die();
}

if (empty($powerschoolid)) {

    output_forms(null, $placeholder = "Start typing your child's name, at least two characters is needed.");

} else {

    if ($email == "YES") {
        global $CFG;

        $key = uniqid();

        $row = new stdClass();
        $row->userid = $user->id;
        $row->key = $key;
        $row->time = time();
        $row->used = 0;
        $DB->insert_record('acct_mgmt_pw_reset_keys', $row);
        $url = $CFG->wwwroot . derive_plugin_path_from("reset_parent_password.php?userID={$user->id}&key={$key}");

        $messageheader = get_string('email_msg_parent_body', 'local_account_management');
        $messagefooter = get_string('email_msg_parent_footer', 'local_account_management');
        $message = $messageheader. $url . $messagefooter;

        $from = $DB->get_record('user', array('username' => 'lcssisadmin'));

        email_to_user($user, $from, "DragonNet Password Reset Link", $message);

        ?>
        <div class="alert alert-success">
            <h4><i class="fa fa-envelope-o"></i> An email has been sent to <?php echo mask_email($user->email); ?></h4>
            <p>Please check and click the link to reset your password. The subject is <strong>"DragonNet Password Reset Link"</strong>. Be sure to check your spam inboxes. If you have any further difficulties, please <a href="http://help.ssis-suzhou.net">open a help ticket</a> with your child(ren)'s name.</p>
        </div>

        <div class="text-center">
            <a href="/" class="btn"><i class="fa fa-home"></i> DragonNet Home</a>
        </div>
        <?php

    } else if ($email == "NO") {

        ?>
        <div class="alert alert-danger">
            <h4><i class="fa fa-phone"></i> Please contact a school secretary</h4>
            <p>We need to change your username, and only secretaries can do that manually. Please <a href="http://www.ssis-suzhou.net/contact-us/index.aspx">go to the school website</a> for contact information. You will simply need to tell them the name of your children who attend SSIS.</p>
        </div>
        <?php


    } else {
        $user = $DB->get_record('user', array('idnumber' => $familyid.'P'));
        if (!$user) {
            death("Something wrong with your account. Please contact help@ssis-suzhou.net with the name of your child(ren).");
        }

        $table = new html_table();
        $table->attributes['class'] = 'userinfobox';

        $row = new html_table_row();

        $row->cells[0] = new html_table_cell();
        $row->cells[0]->attributes['class'] = 'left side';
        $row->cells[0]->text = $OUTPUT->user_picture($user, array('size' => 100, 'courseid' => 1));

        $row->cells[1] = new html_table_cell();
        $row->cells[1]->attributes['class'] = 'content';
        $row->cells[1]->text = '<div class="username">Is this your email address?</div>';
        $row->cells[1]->text .= '<table class="userinfotable">';

        foreach (array('email') as $field) {
            $row->cells[1]->text .= '<tr>
                <td>'.get_user_field_name($field).'</td>
                <td>'.mask_email(s($user->{$field})).'</td>
            </tr>';
        }

        $row->cells[1]->text .= '</table>';

        $table->data = array($row);
        echo html_writer::table($table);

        echo '<br/>';

        echo '<div class="text-center">';

        echo '<a href="'.derive_plugin_path_from('roles/parents.php').'" class="btn" id="reset_button"><i class="fa fa-backward "></i> Back</a> ';

        echo '<a href="'.derive_plugin_path_from('roles/parents.php?email=YES&powerschool='.$user->idnumber).'" class="btn btn-success" id="reset_button"><i class="fa fa-thumbs-up"></i> Yes, that is my email address</a> ';

        echo '<a href="'.derive_plugin_path_from('roles/parents.php?email=NO&powerschool='.$user->idnumber).'" class="btn btn-danger" id="reset_button"><i class="fa fa-thumbs-down"></i> No, that is not my email address</a>';

        echo '</div>';
    }
}

echo $OUTPUT->footer();


