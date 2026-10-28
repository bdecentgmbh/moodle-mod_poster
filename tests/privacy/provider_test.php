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

namespace mod_poster\privacy;

#[\PHPUnit\Framework\Attributes\CoversClass(provider::class)]
/**
 * Tests for the privacy provider of the poster.
 *
 * @package    mod_poster
 * @category   test
 * @copyright  2026 bdecent gmbh <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers \mod_poster\privacy\provider
 */
final class provider_test extends \core_privacy\tests\provider_testcase {
    /**
     * The poster stores no personal data, and says so.
     */
    public function test_get_reason(): void {
        $this->assertInstanceOf(\core_privacy\local\metadata\null_provider::class, new provider());
        $this->assertSame('privacy:metadata', provider::get_reason());
        $this->assertTrue(get_string_manager()->string_exists(provider::get_reason(), 'mod_poster'));
    }
}
