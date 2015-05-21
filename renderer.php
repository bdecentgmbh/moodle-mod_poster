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
 * Class mod_poster_renderer is defined here.
 *
 * @package     mod_poster
 * @category    output
 * @copyright   2015 David Mudrak <david@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * The renderer for poster module
 *
 * @copyright 2015 David Mudrak <david@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_poster_renderer extends plugin_renderer_base {

    /**
     * Render the poster main view page (view.php)
     *
     * @param stdClass $poster The poster instance record
     * @return string
     */
    public function view_page($poster) {

        if ($this->page->user_allowed_editing()) {
            $this->page->set_button($this->edit_button($this->page->url));
        }

        $out = $this->header();

        if ($poster->shownameview) {
            $out .= $this->view_page_heading($poster);
        }

        if ($poster->showdescriptionview) {
            $out .= $this->view_page_description($poster);
        }

        $out .= $this->view_page_content($poster);
        $out .= $this->footer();

        return $out;
    }

    /**
     * Render the page title at the view.php page
     *
     * @param stdClass $poster The poster instance record
     * @return string
     */
    protected function view_page_heading($poster) {
        return $this->heading(format_string($poster->name), 2, null, 'mod_poster-heading');
    }

    /**
     * Render the poster description at the view.php page
     *
     * @param stdClass $poster The poster instance record
     * @return string
     */
    protected function view_page_description($poster) {

        if (html_is_blank($poster->intro)) {
            return '';
        }

        return $this->box(format_module_intro('poster', $poster, $this->page->cm->id), 'generalbox', 'mod_poster-description');
    }

    /**
     * Render the poster content at the view.php page
     *
     * @param stdClass $poster The poster instance record
     * @return string
     */
    protected function view_page_content($poster) {

        $out = '';

        if ($this->page->user_can_edit_blocks() and $this->page->user_is_editing()) {
            $haspre = true;
            $haspost = true;
        } else {
            $haspre = $this->page->blocks->region_has_content('mod_poster-pre', $this);
            $haspost = $this->page->blocks->region_has_content('mod_poster-post', $this);
        }

        if (!$haspre and !$haspost) {
            return $out;
        }

        $cssclassmain = 'container-fluid';
        $cssclassmain .= $haspre ? '' : ' empty-region-mod_poster-pre';
        $cssclassmain .= $haspost ? '' : ' empty-region-mod_poster-post';

        $out .= html_writer::start_div($cssclassmain, array('id' => 'mod_poster-content'));

        // The bootstrap3 based themes should use the class .row here.
        // But that would have different meaning in the bootstrap2 based themes.
        // However, .row-fluid seems to work in bootstrap3 too so leaving it here for now.
        $out .= html_writer::start_div('row-fluid');

        $cssclassgrid = 'col-md-6 span6';
        $cssclasssingle = 'col-md-12 span12';

        if ($haspre) {
            $out .= html_writer::start_div($haspost ? $cssclassgrid : $cssclasssingle);
            $out .= html_writer::start_div('mod_poster-content-region', array('id' => 'mod_poster-content-region-pre'));
            $out .= $this->custom_block_region('mod_poster-pre');
            $out .= html_writer::end_div();
            $out .= html_writer::end_div();
        }

        if ($haspost) {
            $out .= html_writer::start_div($haspre ? $cssclassgrid : $cssclasssingle);
            $out .= html_writer::start_div('mod_poster-content-region', array('id' => 'mod_poster-content-region-post'));
            $out .= $this->custom_block_region('mod_poster-post');
            $out .= html_writer::end_div();
            $out .= html_writer::end_div();
        }

        $out .= html_writer::end_div();
        $out .= html_writer::end_div();

        return $out;
    }
}
