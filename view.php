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
 * View the poster instance
 *
 * @package     mod_poster
 * @copyright   2015 David Mudrak <david@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_poster\poster;

require(__DIR__.'/../../config.php');
require_once($CFG->libdir.'/completionlib.php');

$cmid = required_param('id', PARAM_INT);
$edit = optional_param('edit', null, PARAM_BOOL);

$cm = get_coursemodule_from_id('poster', $cmid, 0, false, MUST_EXIST);
$poster = new poster($cm);
$course = $poster->get_course();
$posterdata = $poster->get_settings();

require_login($course, true, $cm);
require_capability('mod/poster:view', $PAGE->context);

$poster->setup_page($PAGE);

if ($edit !== null and confirm_sesskey() and $PAGE->user_allowed_editing()) {
    $USER->editing = $edit;
    redirect($PAGE->url);
}

$poster->trigger_module_viewed($PAGE->context);
$output = $PAGE->get_renderer('mod_poster');

echo $output->view_page($posterdata);
