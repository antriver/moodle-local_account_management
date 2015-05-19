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
require_once('../lib/sharedlib.php');

setup_page();
output_tabs('For: New Students');

?>

<div class="alert alert-block alert-info">
    <h4><i class="fa fa-clock-o"></i> How and when can I get my DragonNet account?</h4>
    <p>They are created automatically the first day that you attend SSIS. Your teachers are emailed your details.</p>
</div>

<div class="alert alert-block alert-info">
    <h4><i class="fa fa-female"></i> How and when can I get my DragonNet Parent account?</h4>
    <p>They are created automatically, along with new student accounts, on the first day that your child attend SSIS. Parents should receive an email with the subject "Your SSIS DragonNet Parent Account".</p>
</div>

<div class="alert alert-block alert-info">
    <h4><i class="fa fa-user"></i> What is my username?</h4>
    <p>Your username is created according to your passport name and the year you graduate. For example, if your family name is "Student" and your given name is "Happy", and you will graduate from high school in the year 2020, your username is happystudent20.</p>
</div>

<div class="alert alert-block alert-info">
    <h4><i class="fa fa-envelope-o"></i> What is my email address?</h4>
    <p>Your username@student.ssis-suzhou.net</p>
</div>

<?php

echo $OUTPUT->footer();
