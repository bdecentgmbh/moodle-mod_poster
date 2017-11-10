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
 * Provide the {@link behat_mod_poster} class.
 *
 * @package     mod_poster
 * @category    test
 * @copyright   2017 David Mudrák <david@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// NOTE: no MOODLE_INTERNAL test here, this file may be required by behat before including /config.php.

require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');

/**
 * Define the Poster module's behat steps.
 *
 * The poster module changes the default look & behaviour of the "add-block" widget so that it looks like the drop down
 * selector even in Boost based theme (where it would normally be displayed as a link in the flat navigation). As a
 * consequence, we can't use the default step {@link i_add_the_block()} because it would fail in Boost (the expected
 * link is not in the flat navigation).
 *
 * Instead, we define a custom step as a copy of the {@link behat_blocks::i_add_the_block()} that expects the default
 * drop down selector.
 *
 * @copyright 2017 David Mudrak <david@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_mod_poster extends behat_base {

    /**
     * Adds the selected block. Editing mode must be previously enabled.
     *
     * @Given /^I add the "(?P<block_name_string>(?:[^"]|\\")*)" poster block$/
     * @param string $blockname
     */
    public function i_add_the_poster_block($blockname) {
        $this->execute('behat_forms::i_set_the_field_to',
            array("bui_addblock", $this->escape($blockname))
        );

        // If we are running without javascript we need to submit the form.
        if (!$this->running_javascript()) {
            $this->execute('behat_general::i_click_on_in_the',
                array(get_string('go'), "button", "#add_block", "css_element")
            );
        }
    }
}
