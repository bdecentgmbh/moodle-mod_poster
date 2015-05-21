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
 * Provides the restore_poster_activity_task class.
 *
 * @package     mod_poster
 * @category    backup
 * @copyright   2015 David Mudrak <david@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/poster/backup/moodle2/restore_poster_stepslib.php');

/**
 * Defines the steps needed to restore a poster activity.
 *
 * @copyright 2015 David Mudrak <david@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_poster_activity_task extends restore_activity_task {

    /**
     * Defines additional restore settings for this activity.
     */
    protected function define_my_settings() {
    }

    /**
     * Defines the steps to restore the poster activity.
     */
    protected function define_my_steps() {
        $this->add_step(new restore_poster_activity_structure_step('poster_structure', 'poster.xml'));
    }

    /**
     * Defines the contents in the activity that must be processed by the link decoder.
     *
     * @return array of restore_decode_content
     */
    static public function define_decode_contents() {
        return array(new restore_decode_content('poster', array('intro'), 'poster'));
    }

    /**
     * Defines the decoding rules for links belonging to the activity to be executed by the link decoder.
     */
    static public function define_decode_rules() {
        return array(
            new restore_decode_rule('POSTERINDEX', '/mod/poster/index.php?id=$1', 'course'),
            new restore_decode_rule('POSTERVIEWBYID', '/mod/poster/view.php?id=$1', 'course_module'),
        );
    }
}
