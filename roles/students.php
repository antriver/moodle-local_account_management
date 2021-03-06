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
output_tabs('For: Students');

?>

<div class="alert alert-block alert-info">
    <h4><i class="fa fa-lock"></i> Can't login to DragonNet?</h4>
    <p>Please ask a teacher to reset your password for you.</p>
</div>

<div class="alert alert-block alert-info">
    <h4><i class="fa fa-video-camera"></i> Can't login to DragonTV?</h4>
    <p>You must ask a teacher to reset your DragonNet password. You will then need to change your password on DragonNet, and that will be your password for DragonTV and student email as well.</p>
</div>

<div class="alert alert-block alert-info">
    <h4><i class="fa fa-envelope-o"></i> Can't login to student email?</h4>
    <p>You must ask a teacher to reset your DragonNet password. You will then need to change your password on DragonNet, and that will be your password for DragonTV and student email as well.</p>
</div>

<?php

echo $OUTPUT->footer();
