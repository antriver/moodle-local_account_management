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

defined('MOODLE_INTERNAL') || die();
require_once(__DIR__ . '/locallib.php');

// Some display stuff
function output_begin_table($message) {
    echo '<div>$message</div><br />';
    echo '<table class="table table-striped userinfotable htmltable" width="100%"><thead></thead><tbody>';
}

function output_end_table() {
    echo '</tbody></table>';
}

function output_tabs($current) {

    $tabs = array(
        "For: Parents",
        "For: New Students",
        "For: Students",
        "For: Teachers",
        "For: Secretaries",
        "For: Admins",
        "About Accounts",
        "About DragonNet Passwords",
    );

    $t = '<div class="tabs text-center">';
    $t .= '<div class="btn-group">';

    foreach ($tabs as $label) {

        $labellower = str_replace(" ", "", strtolower($label));
        $labellower = str_replace("for:", "", $labellower);

        $href = derive_plugin_path_from("roles/{$labellower}". '.php');

        $t .= '<a class="btn btn-sm btn-small ' . ($label == $current ? ' active' : '') . '" href="' . $href . '">';
        $t .= $label;
        $t .= '</a>';
    }

    $t .= '</div></div>';

    echo $t;
}

function output_forms($user=null, $placeholder="Look up by lastname, firstname, or homeroom...", $kind="students") {

    if (!$user) {
        // user hasn't chosen anybody yet
        $extraattrs = 'placeholder="'.$placeholder.'"';
        $powerschoolid = "";
    } else {
        // make sure the the text box displays the right thing, depending on context
        $extraattrs = 'value="'.$user->firstname.' '.$user->lastname.' (' . get_user_type($user) . ')" ';
        $powerschoolid = $user->idnumber;
    }

    $pathtoquery = "/local/account_management/query/{$kind}.php";

    ?>

<form id="user_entry" action="" method="get">
    <input name="" autofocus="autofocus" onclick="this.select()" type="text" id="person" <?php echo $extraattrs; ?>/>
    <br/>
    <input name="powerschool" type="hidden" id="powerschool" value="<?php echo $powerschoolid; ?>" />
</form>
<br/>
<script>
$("#person").autocomplete({
    autoFocus: true,
    source: "<?php echo $pathtoquery; ?>",
    minLength: 1,
    select: function (event, ui) {
        event.preventDefault();
        $("#person").val(ui.item.label);
        $("#powerschool").val(ui.item.value);
        $("#user_entry").submit();
    }
});
</script>
    <?php
}
