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
 * Provides meta-information about the plugin.
 *
 * @package     mod_poster
 * @copyright bdecent gmbh 2023 <info@bdecent.de>
 * based on the work by David Mudrak <david@moodle.com>
 *
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'mod_poster';
$plugin->release = '8.0.0';
$plugin->version = 2025040100;
$plugin->requires = 2020061500;
$plugin->maturity = MATURITY_STABLE;
$plugin->supported = [401, 405];
