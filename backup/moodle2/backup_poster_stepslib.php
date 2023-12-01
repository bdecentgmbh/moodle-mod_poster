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
 * Provides the backup_poster_activity_structure_step class.
 *
 * @package     mod_poster
 * @category    backup
 * @copyright   2015 David Mudrak <david@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * Provides the definition of the backup structure
 *
 * @copyright 2015 David Mudrak <david@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_poster_activity_structure_step extends backup_activity_structure_step {

    /**
     * Defines the structure of the backup
     *
     * The poster activity does not contain user data and not additional nodes
     * but the instances itself.
     *
     * @return backup_nested_element
     */
    protected function define_structure() {

        // Define the poster root element.
        $poster = new backup_nested_element('poster', ['id'], ['name', 'intro',
            'introformat', 'timecreated', 'timemodified', 'shownameview', 'showdescriptionview', 'display', ]);

        // Define the data source.
        $poster->set_source_table('poster', ['id' => backup::VAR_ACTIVITYID]);

        // Define file annotations.
        $poster->annotate_files('mod_poster', 'intro', null);

        return $this->prepare_activity_structure($poster);
    }
}
