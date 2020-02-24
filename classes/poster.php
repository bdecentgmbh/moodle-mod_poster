<?php
// This file is part of mod_datalynx for Moodle - http://moodle.org/
//
// It is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// It is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * @package mod_poster
 * @copyright based on the work by 2015 David Mudrak <david@moodle.com>{
 *
 * @copyright 2019 by Harald, David und Nicklas @devcamp19
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_poster;

use mod_poster\event\course_module_viewed;
use completion_info;

defined('MOODLE_INTERNAL') or die();

/**
 * Class poster
 *
 * @package mod_datalynx
 */
class poster {

    /**
     * @var mixed|object|false fetched from get_record.
     */
    protected $settings;

    /**
     * @var mixed course fetched from  DB.
     */
    protected $course;

    /**
     * @var \cm_info
     */
    protected $cm;

    /**
     * poster constructor.
     *
     * @param \cm_info|\stdClass $cm
     * @throws \dml_exception
     */
    public function __construct($cm) {
        global $DB;
        $this->settings = $DB->get_record('poster', array('id' => $cm->instance), '*', MUST_EXIST);
        $this->course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
        $this->cm = $cm;
    }

    /**
     * Get settings saved in DB for the poster module.
     *
     * @return false|mixed|object
     */
    public function get_settings(){
        return $this->settings;
    }

    /**
     * Get course settings.
     *
     * @return false|mixed|object
     */
    public function get_course(){
        return $this->course;
    }

    /**
     * Set up the page for displaying the content.
     *
     * @param \moodle_page $page
     */
    public function setup_page(\moodle_page &$page){
        $page->set_url('/mod/poster/view.php', array('id' => $this->cm->id));
        $page->set_title($this->course->shortname.': '.$this->settings->name);
        $page->set_heading($this->course->fullname);
        $page->set_activity_record($this->settings);
        // Define the custom block regions we want to use at the poster view page.
        // Region names are limited to 16 characters.
        $page->blocks->add_region('mod_poster-pre', true);
        $page->blocks->add_region('mod_poster-post', true);
    }

    public function trigger_module_viewed(\context $context) {
        // Trigger module viewed event.
        $event = course_module_viewed::create(array(
            'objectid' => $this->settings->id,
            'context' => $context,
        ));
        $event->add_record_snapshot('course_modules', $this->cm);
        $event->add_record_snapshot('course', $this->course);
        $event->add_record_snapshot('poster', $this->settings);
        $event->trigger();

        // Mark the module instance as viewed by the current user.
        $completion = new completion_info($this->course);
        $completion->set_module_viewed($this->cm);
    }

}