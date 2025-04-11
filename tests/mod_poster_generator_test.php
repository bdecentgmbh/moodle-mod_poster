<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Poster activity testcases.
 *
 * @package    mod_poster
 * @copyright  2021 bdecent gmbh <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_poster;

/**
 * Poster module generator tests.
 *
 * @package     mod_poster
 * @category    test
 * @copyright   2021 bdecent gmbh <https://bdecent.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class mod_poster_generator_test extends \advanced_testcase {

    /**
     * Test {@see mod_poster_generator::create_instance()}.
     * @covers ::create_instance
     * @return void
     */
    public function test_create_instance(): void {
        global $DB;

        $this->resetAfterTest();
        $this->setAdminUser();

        $course = $this->getDataGenerator()->create_course();

        $params = [
            'course' => $course->id,
            'name' => 'Test poster!',
        ];

        $this->assertFalse($DB->record_exists('poster', $params));

        $poster = $this->getDataGenerator()->create_module('poster', $params);

        $this->assertEquals(1, $DB->count_records('poster', $params));
    }
}
