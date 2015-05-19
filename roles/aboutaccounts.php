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
require_once('../lib/output.php');

setup_page();
output_tabs('About Accounts');

?>

<div class="alert alert-block alert-info">
    <h4><i class="fa fa-female"></i> What accounts do parents have?</h4>
    <p>Parents have one, and only one, &quot;family&quot; account; their username is an email address.</p>
</div>

<div class="alert alert-block alert-info">
    <h4><i class="fa fa-user"></i> What accounts do students have?</h4>
    <p>Students have an account for DragonNet, DragonTV, and Student Email. The password is exactly the same as their DragonNet password. They can reset your password for everything by reseting your DragonNet account.</p>
</div>

<div class="alert alert-block alert-info">
    <h4><i class="fa fa-magic"></i> What accounts do teachers have?</h4>
    <p>Teachers have an account for DragonNet and DragonTV. The password for DragonNet is the same for both. Resetting their DragonNet account automatically resets both accounts.</p>
</div>

<?php

echo $OUTPUT->footer();
