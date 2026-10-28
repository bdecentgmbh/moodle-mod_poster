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
            // The theme's own edit switch replaces this button, but themes without one still need it.
            $this->page->set_button($this->edit_button($this->page->url));
            $this->page->blocks->set_default_region('mod_poster-pre');
        }

        // The activity header shows the name and, if enabled, the description (see view.php).
        $out = $this->header();
        $out .= $this->view_page_content($poster);
        $out .= $this->footer();

        return $out;
    }

    /**
     * Render the poster content: its block regions, side by side
     *
     * Also used to show the poster on the course page.
     *
     * @param stdClass $poster The poster instance record
     * @return string
     */
    public function view_page_content($poster) {
        $editing = $this->page->user_can_edit_blocks() && $this->page->user_is_editing();
        // Themes with the "Add a block" button in the block drawer (Boost and its children) get one in each column,
        // so blocks are added where they belong. Other themes show their own "Add a block" block in the first column.
        $addblockbuttons = $editing && ($this->page->theme->addblockposition ?? null) == BLOCK_ADDBLOCK_POSITION_FLATNAV;

        $regions = [];
        $classes = [];
        foreach (['pre', 'post'] as $suffix) {
            $region = 'mod_poster-' . $suffix;
            if (!$editing && !$this->page->blocks->region_has_content($region, $this)) {
                $classes[] = 'empty-region-' . $region;
                continue;
            }
            $regions[] = [
                'suffix' => $suffix,
                'addblockbutton' => $addblockbuttons ? $this->addblockbutton($region) : '',
                'blocks' => $this->custom_block_region($region),
            ];
        }

        if (!$regions) {
            return '';
        }

        return $this->render_from_template('mod_poster/content', [
            'classes' => implode(' ', $classes),
            'columns' => count($regions),
            'regions' => $regions,
            'missingblockshelp' => $editing ? $this->help_icon('missingblocks', 'mod_poster', true) : '',
        ]);
    }
}
