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
 * Activity module interface functions are defined here
 *
 * @package     mod_poster
 * @copyright   2015 David Mudrak <david@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_poster\poster;

defined('MOODLE_INTERNAL') || die();

define('POSTER_DISPLAY_PAGE', 0);
define('POSTER_DISPLAY_INLINE', 1);
/**
 * Returns the information if the module supports a feature
 *
 * @see plugin_supports() in lib/moodlelib.php
 * @param string $feature FEATURE_xx constant for requested feature
 * @return bool true if the feature is supported, null if unknown
 */
function poster_supports($feature) {

    switch($feature) {
        case FEATURE_MOD_ARCHETYPE:
            return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_GRADE_HAS_GRADE:
            return false;
        case FEATURE_GROUPS:
            return false;
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        default:
            return null;
    }
}

/**
 * Adds a new instance of the poster into the database
 *
 * Given an object containing all the settings form data, this function will
 * save a new instance and return the id of the new instance.
 *
 * @param stdClass $poster An object from the form in mod_form.php
 * @return int The id of the newly inserted poster record
 */
function poster_add_instance(stdClass $poster) {
    global $DB;

    $poster->timecreated = time();
    $poster->timemodified = $poster->timecreated;

    $poster->id = $DB->insert_record('poster', $poster);

    return $poster->id;
}

/**
 * Updates the existing instance of the poster in the database
 *
 * Given an object containing all the settings form data, this function will
 * update the instance record with the new form data.
 *
 * @param stdClass $poster An object from the form in mod_form.php
 * @return bool true
 */
function poster_update_instance(stdClass $poster) {
    global $DB;

    $poster->timemodified = time();
    $poster->id = $poster->instance;

    $DB->update_record('poster', $poster);

    return true;
}

/**
 * Deletes the poster instance
 *
 * @param int $id ID of the poster instance
 * @return bool Success indicator
 */
function poster_delete_instance($id) {
    global $DB;

    if (! $poster = $DB->get_record('poster', array('id' => $id))) {
        return false;
    }

    $DB->delete_records('poster', array('id' => $poster->id));

    return true;
}

/**
 * Adds items into the poster administration block
 *
 * @param settings_navigation $settingsnav The settings navigation object
 * @param navigation_node $node The node to add module settings to
 */
function poster_extend_settings_navigation(settings_navigation $settingsnav, navigation_node $node) {
    global $PAGE;

    if ($PAGE->user_allowed_editing()) {
        $url = $PAGE->url;
        $url->param('sesskey', sesskey());

        if ($PAGE->user_is_editing()) {
            $url->param('edit', 'off');
            $editstring = get_string('turneditingoff', 'core');
        } else {
            $url->param('edit', 'on');
            $editstring = get_string('turneditingon', 'core');
        }

        $node->add($editstring, $url, navigation_node::TYPE_SETTING);
    }
}

/**
 * Return the page type patterns that can be used by blocks
 *
 * @param string $pagetype Current page type
 * @param stdClass $parentcontext Block's parent context
 * @param stdClass $currentcontext Current context of block
 */
function poster_page_type_list($pagetype, $parentcontext, $currentcontext) {
    return array(
        'mod-poster-view' => get_string('page-mod-poster-view', 'mod_poster'),
    );
}

/**
 * Given a coursemodule object, this function returns the extra
 * information needed to print this activity in various places.
 *
 * If poster needs to be displayed inline we store additional information
 * in customdata, so functions {@link poster_cm_info_dynamic()} and
 * {@link poster_cm_info_view()} do not need to do DB queries
 *
 * @param cm_info $cm
 * @return cached_cm_info info
 */
function poster_get_coursemodule_info($cm) {
    global $DB;
    if (!($poster = $DB->get_record('poster', array('id' => $cm->instance),
        'id, name, display, showdescriptionview, intro, introformat'))) {
        return null;
    }
    $cminfo = new cached_cm_info();
    $cminfo->name = $poster->name;
    if ($poster->display == POSTER_DISPLAY_INLINE) {
        // Prepare poster object to store in customdata.
        $fdata = new stdClass();
        $fdata->showdescriptionview = $poster->showdescriptionview;
        if ($cm->showdescription && !html_is_blank($poster->intro)) {
            $fdata->intro = $poster->intro;
            if ($poster->introformat != FORMAT_MOODLE) {
                $fdata->introformat = $poster->introformat;
            }
        }
        $cminfo->customdata = $fdata;
    } else {
        if ($cm->showdescription) {
            // Convert intro to html. Do not filter cached version, filters run at display time.
            $cminfo->content = format_module_intro('poster', $poster, $cm->id, false);
        }
    }
    return $cminfo;
}

/**
 * Sets dynamic information about a course module
 *
 * This function is called from cm_info when displaying the module
 * mod_poster can be displayed inline on course page and therefore have no course link
 *
 * @param cm_info $cm
 */
function poster_cm_info_dynamic(cm_info $cm) {
    if ($cm->customdata) {
        // The field 'customdata' is not empty IF AND ONLY IF we display contents inline.
        $cm->set_no_view_link();
    }
}

/**
 * Overwrites the content in the course-module object with the poster files list
 * if poster.display == POSTER_DISPLAY_INLINE
 *
 * @param cm_info $cminfo
 */
function poster_cm_info_view(cm_info $cminfo) {
    $poster = new poster($cminfo);

    if ($cminfo->uservisible && $cminfo->customdata && has_capability('mod/poster:view', $cminfo->context)) {
        // Restore poster object from customdata.
        // Note the field 'customdata' is not empty IF AND ONLY IF we display contents on course page.
        // Otherwise the content is default.
        $context = context_module::instance($cminfo->id);
        $page = new moodle_page();
        $page->set_context($context);
        $page->set_cm($cminfo);
        $poster->setup_page($page);
        $output = $page->get_renderer('mod_poster');
        if ($page->user_allowed_editing()) {
            $page->blocks->set_default_region('mod_poster-pre');
            $page->theme->addblockposition = BLOCK_ADDBLOCK_POSITION_DEFAULT;
        }
        $page->blocks->load_blocks();
        $content = $output->view_page_content($poster);
        $cminfo->set_content($content);
    }
}