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
 * Provides the restore_poster_activity_structure_step class.
 *
 * @package     mod_poster
 * @category    backup
 * @copyright   2015 David Mudrak <david@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Structure step to restore poster activity instance.
 *
 * @copyright 2015 David Mudrak <david@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_poster_activity_structure_step extends restore_activity_structure_step {

    /**
     * Defines the structure of the backup data to be processed.
     *
     * @return array of restore_path_element
     */
    protected function define_structure() {
        return $this->prepare_activity_structure(array(new restore_path_element('poster', '/activity/poster')));
    }

    /**
     * Process the poster element data.
     *
     * @param array $data
     */
    protected function process_poster($data) {
        global $DB;

        $data = (object)$data;
        $data->course = $this->get_courseid();
        $data->timemodified = time();

        $newid = $DB->insert_record('poster', $data);

        $this->apply_activity_instance($newid);
    }

    /**
     * Define additional things to do after the steps are executed.
     */
    protected function after_execute() {
        $this->add_related_files('mod_poster', 'intro', null);
    }
}
