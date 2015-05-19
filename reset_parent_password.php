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
require_once(__DIR__ . '/lib/locallib.php');
require_once(__DIR__ . '/lib/sharedlib.php');

setup_page();

if (isloggedin()) {
    death("That's strange. You are trying to reset a password when you have already logged in? Fail!");
}

$key = required_param('key', PARAM_RAW);
$userid = required_param('userID', PARAM_RAW);
$confirm = optional_param('confirm', '', PARAM_RAW);

// Check key is valid
$select = '
select
    *
from
    ssismdl_acct_mgmt_pw_reset_keys
where
    userid = ? and
    used = ? and
    key = ?';
$params = array($userid, 0, $key);

$row = $DB->get_record_sql($select, $params);

if (!$row) {
    redirect('/');
}

// How long should the link be valid for (in seconds)?
if (time() - $row->time > 86400) {
    die("Sorry, that link has expired");
}

// Get user
$user = $DB->get_record('user', array('id' => $userid));
if (!$user) {
    die("Could not find user!");
}

if ($confirm == "YES") {

    // Password resetting time
    update_internal_user_password($user, 'changeme');
    set_user_preference('auth_forcepasswordchange', 1, $USER);

    // user the previously gotten row to set it
    $row->used = 1;
    $DB->update_record('acct_mgmt_pw_reset_keys', $row);

    echo "Redirecting...";

    // Set the key as used
    redirect($CFG->wwwroot . '/login/');

} else {
    ?>
        <div class="local-alert">
        <i class="icon-4x pull-left icon-user"></i> <p style="font-size:18px;">Your username is <strong><?php echo $user->username ?></strong>.</p>
        <p>&nbsp;</p></div>

        <div class="local-alert">
        <i class="icon-4x pull-left icon-key"></i> <p style="font-size:18px;">Your temporary password is <strong>changeme</strong>.</p>
        <p>&nbsp;</p></div>

        <div class="local-alert">
        <i class="icon-4x pull-left icon-question-sign"></i> <p style="font-weight:bold;font-size:18px;">Now login again with the above credentials.</p>
        <p><a id="confirm" href="#" class="btn" id="reset_button"><i class="icon-hand-right"></i> Login again</a></p></div>

        <div id="dialog" title="Reminder" style="display:none"> Remember, your current password is <b>changeme</b>. You will have to enter it twice.</div>

    <script>

    $('#confirm').on("click", function(e) {
        e.preventDefault();
        $("#dialog").dialog({
            minWidth: 450,
            draggable: false,
            model: true,
            buttons: [
                {
                    text: "OK",
                    click: function() {
                        location.href = "<?php echo derive_plugin_path_from('reset_parent_password.php?confirm=YES&userID='.$userid.'&key='.$key); ?>";
                    }
                },
            ]
        });


    });

    </script>

    <?php

}


echo $OUTPUT->footer();
