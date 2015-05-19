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
output_tabs('About DragonNet Passwords');

?>

<div class="alert alert-block alert-info">
    <h4><i class="fa fa-lock"></i> DragonNet passwords MUST have a symbol character, such as ! or @ or #?</h4>
    <p>This is the most common problem when attempting to login to DragonNet. The use of symbol characters is highly recommended for DragonNet and all online websites that you use. It does make it significantly more secure.</p>
</div>

<div class="alert alert-block alert-info">
    <h4><i class="fa fa-key"></i> How many passwords do I need for SSIS?</h4>
    <p>You only need one. The most common online tools at SSIS (DragonNet, DragonTV, and Student Email) all share the same password. Changing the DragonNet password automatically changes the password on the other two.</p>
</div>

<div class="alert alert-block alert-info">
    <h4><i class="fa fa-unlock"></i> Who can reset passwords?</h4>
    <p>Teachers can only reset students' passwords, and secretaries can reset everyone's password. DragonNet site administrators can also reset everyone's passwords.</p>
</div>

<?php

echo $OUTPUT->footer();
