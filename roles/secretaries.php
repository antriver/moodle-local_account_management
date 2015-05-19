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

// setup_page();
global $PAGE;
global $OUTPUT;

setup_page();

//require_login();

output_tabs('For: Secretaries');

if (!is_secretary()) {
    death('Only designated administrators can access this section.');
}

$powerschoolid = optional_param('powerschool', '', PARAM_RAW);
if (!empty($powerschoolid)) {
    $user = $DB->get_record('user', array('idnumber' => $powerschoolid));
    if (!$user) {
        death('Something is wrong with the accounts associated with powerschool ID '.$powerschoolid.' you need to contact the DragonNet administrator.');
    }
    $familyid = substr($powerschoolid, 0, 4);
}
$resetpassword = optional_param('reset_password', '', PARAM_RAW);

if (empty($powerschoolid)) {
    ?>

    <div class="alert alert-success">
        <h4><i class="fa fa-info-circle"></i> About Resetting DragonNet accounts</h4>
        <p>You can reset anyone's DragonNet account. You can reset parent accounts by looking up their children first. After resetting, they will need to login to DragonNet with their login and the password <strong>changeme</strong>.</p>
    </div>

    <?php

    output_forms(null, 'Start typing anyone\'s name', 'all');

} else {

    if ($resetpassword == "YES") {
        $newpassword = 'changeme';
        $authplugin = get_auth_plugin($user->auth);

        if ( $result = $authplugin->user_update_password($user, $newpassword) ) {
            echo $OUTPUT->heading('Password for '.$user->firstname. ' '.$user->lastname.' Changed Successfully to "changeme"');
        } else {
            echo $user->firstname. ' '. $user->lastname. ' could not be changed, probably because they do not have an activated account. Contact the DragonNet administrator.';
        }
        echo '<ul class="buttons"><li><a class="btn" href="'.derive_plugin_path_from('').'"><i class="icon-backward"></i> Return</a></li></ul>';
        echo '<ul class="buttons"><li><a id="email_user" class="btn" href="'.derive_plugin_path_from('roles/secretaries.php').'?powerschoolid='.$powerschoolid.'&email=YES"><i class="icon-envelope"></i> Email</a></li></ul>';

        // Dialogs and scripts used in the box output
        $defaultbody = "Dear ".$user->firstname.' '. $user->lastname.",\n\nAs requested, your DragonNet account has been reset.\n\nYour username is:\n".$user->username."\n\nYour password is:\nchangeme\n\nThank you.\n\nRegards,\nSSIS DragonNet";

        $dialog = '<div id="dialog_email_user" title="Edit and click OK to submit email" style="display:none;"><b>To:</b>
        <form id="email_to_user" action="'.derive_plugin_path_from('profile_mods.php').'">
        <input name="email" id="email" readonly style="width:100%;margin-top:5px;padding:5px;" value="'.$user->email.'"/ onclick="alert(\'Cannot edit the To field, because it must go to the registered email address...!\');">
        <input name="userid" id="userid" type="hidden" value="'.$user->id.'" />
        <p>&nbsp;</p><b>Subject:</b>
        <input name="subject" type="text" id="subject" style="width:100%;margin-top:5px;padding:5px;" value="Your DragonNet account has been reset"/>
        <p>&nbsp;</p><b>Body:</b>
        <textarea id="text" name="text" style="width:100%;margin-top:5px;padding:5px;" rows="14"/>'.$defaultbody.'</textarea>
        <input type="submit" style="display:none;" />
        </form>
        </div>';
        $script = "<script>

        $('#email_to_user').on('submit', function(e) {
            var formURL = \"".derive_plugin_path_from('email_user.php')."\";
            var formData = {
                \"userid\": $('#userid').val(),
                \"text\": $('#text').val(),
                \"subject\": $('#subject').val()
            };
            $.ajax(
                {
                    url : formURL,
                    data: formData,
                    modal: true,
                    async: true,
                    type: \"GET\",
                    success: function(data, textStatus, jqXHR)
                    {
                        $('#dialog_email_user').dialog('close');
                        alert('Email to '.concat($('#email').val()).concat(' successfully sent.'));
                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        alert('Something happened that resulted in a failure. You will have to email them manually.');
                    }

                }

            );
            return false;
        })

        $('#email_user').on(\"click\", function(e) {
            e.preventDefault();
            $(\"#dialog_email_user\").dialog({
                minWidth: 600,
                draggable: false,
                modal: true,
                show: { effect: \"drop\", duration: 400 },
                buttons: [
                    {
                        id: 'ok_button_dialog_email_user',
                        text: \"OK\",
                        click: function() {
                            $('#email_to_user').submit();
                        }
                    },
                ],
                open: function () {
                    $('#ok_button_dialog_email_user').focus();
                }
            });

        });
        </script>";

        echo $dialog.$script;

    } else {
        output_forms($user, '', 'all');

        $table = new html_table();
        $table->attributes['class'] = 'userinfobox';

        $row = new html_table_row();

        $row->cells[0] = new html_table_cell();
        $row->cells[0]->attributes['class'] = 'left side';
        $row->cells[0]->text = $OUTPUT->user_picture($user, array('size' => 100, 'courseid' => 1));

        // print the username area, and then the table that allows the user to click
        $row->cells[1] = new html_table_cell();
        $row->cells[1]->attributes['class'] = 'username';
        $row->cells[1]->text = $user->firstname . ' ' . $user->lastname;
        $row->cells[1]->text .= '<table class="userinfotable">';
        foreach (array('idnumber', 'username', 'email') as $field) {
            $row->cells[1]->text .= '<tr>
                <td>'.get_user_field_name($field).':</td>
                <td>'.s($user->{$field}).'</td>
            </tr>';
        }

        // if this is a parent account, secretaries need to be able to edit certain things
        if (strpos($user->idnumber, 'P') == 4) {
            $row->cells[1]->text .= '<tr>
                <td>Edit:</td>
                <td><a id="edit_username" href="#">'.'Username & Email'.'</a></td>
            </tr>';
            // $row->cells[1]->text .= '<tr>
            //     <td>Edit:</td>
            //     <td><a id="edit_fullname" href="#">First & Last name</a></td>
            // </tr>';
        }


        $row->cells[1]->text .= '</table>';

        $table->data = array($row);
        echo html_writer::table($table);

        echo '<br/>';

        echo '<form id="reset_password" action="" method="get" class="text-center">';
        echo '<input name="powerschool" type="hidden" value="'.$user->idnumber.'"/>';
        echo '<input name="reset_password" type="hidden" id="reset_password" value="YES"/>';
        if (strpos($user->email, '@student.ssis-suzhou.net') !== false) {
            echo '<a href="'.derive_plugin_path_from('roles/secretaries.php?powerschool=').$familyid.'P'.'" class="btn" id="parent_instead"><i class="icon-male"></i> Get Parent Account Instead</a>';
        }
        echo '<a id="reset_button" href="#" class="btn btn-success"><i class="fa fa-key"></i> Reset '.$user->firstname.' '.$user->lastname.'\'s password</a>';
        echo '</form>';
        echo '</ul>';

        // Now output the dialogs and scripts used in the box output

        $dialog = '<div id="dialog_edit_username" title="Edit username" style="display:none;">
        In DragonNet, parents\' username should be their main email address. You can edit that here:
        <form id="change_username" action="'.derive_plugin_path_from('profile_mods.php').' method="get">
        <input id="change_username_to" name="change_username_to" style="width:100%;margin-top:5px;" autofocus="autofocus" size="100" onclick="this.select()" type="text" value="'.$user->email.'" />
        </form>
        </div>';
        $script = "<script>

        $('#edit_username').on(\"click\", function(e) {
            e.preventDefault();
            $(\"#dialog_edit_username\").dialog({
                minWidth: 450,
                draggable: false,
                model: true,
                show: { effect: \"drop\", duration: 400 },
                buttons: [
                    {
                        id: 'ok_button_dialog_edit_username',
                        text: \"OK\",
                        click: function() {
                            var formURL = \"".derive_plugin_path_from('profile_mods.php')."?userid=".$user->id."&change_username_to=\".concat($('#change_username_to').val());
                            var formData = {};
                            $.ajax(
                            {
                                url : formURL,
                                async: true,
                                type: \"GET\",
                                success: function(data, textStatus, jqXHR)
                                {
                                    $('#dialog_edit_username').dialog('close');
                                    window.location.reload();
                                },
                                error: function(jqXHR, textStatus, errorThrown)
                                {
                                    alert('fail');
                                }
                            });
                        }
                    },
                ],
                open: function () {
                    $('#ok_button_dialog_edit_username').focus();
            }

            });

        });
        </script>";

        echo $dialog.$script;

        $dialog = '<div id="dialog_edit_fullname" title="Edit username" style="display:none;">
        You can edit their fullname here:
        <form id="change_fullname" action="'.derive_plugin_path_from('profile_mods.php').' method="get">
        <input id="change_firstname_to" name="change_firstname_to" style="width:100%;margin-top:5px;" autofocus="autofocus" size="100" onclick="this.select()" type="text" value="'.$user->firstname.'" />
        <input id="change_lastname_to" name="change_lastname_to" style="width:100%;margin-top:5px;" autofocus="autofocus" size="100" onclick="this.select()" type="text" value="'.$user->lastname.'" />
        </form>
        </div>';
        $script = "<script>

        $('#edit_fullname').on(\"click\", function(e) {
            e.preventDefault();
            $(\"#dialog_edit_fullname\").dialog({
                minWidth: 450,
                draggable: false,
                model: true,
                show: { effect: \"drop\", duration: 400 },
                buttons: [
                    {
                        id: 'ok_button_dialog_edit_fullname',
                        text: \"OK\",
                        click: function() {
                            var formURL = \"".derive_plugin_path_from('profile_mods.php')."\"
                            var formData = {
                                \"userid\": ".$user->id.",
                                \"change_firstname_to\": $('#change_firstname_to').val(),
                                \"change_lastname_to\": $('#change_lastname_to').val()
                            };
                            $.ajax(
                            {
                                url : formURL,
                                data: formData,
                                async: true,
                                type: \"GET\",
                                success: function(data, textStatus, jqXHR)
                                {
                                    $('#dialog_edit_fullname').dialog('close');
                                    window.location.reload();
                                },
                                error: function(jqXHR, textStatus, errorThrown)
                                {
                                    alert('fail');
                                }
                            });
                        }
                    },
                ],
                open: function () {
                    $('#ok_button_dialog_edit_fullname').focus();
            }

            });

        });
        </script>";

        echo $dialog.$script;

        // now output the button for the password reset itself

        echo '<div id="dialog" title="Confirm Reset" style="display:none"> Are you sure you want to reset '.$user->firstname.' '.$user->lastname.'\'s password?</div>';
        echo '
<script>
$("#reset_button").on("click", function(e) {
    e.preventDefault();
    $("#dialog").dialog({
        minWidth: 450,
        draggable: false,
        dialogClass: "no-close",
        model: true,
        buttons: [
            {
                text: "OK",
                click: function() {
                    $("#reset_password").submit();
                }
            },
        ]
    });
});
</script>';
    }
}

echo $OUTPUT->footer();
