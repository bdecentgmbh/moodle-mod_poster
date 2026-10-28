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

namespace mod_poster\event;

use mod_poster\poster;

#[\PHPUnit\Framework\Attributes\CoversClass(course_module_viewed::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(course_module_instance_list_viewed::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(poster::class)]
/**
 * Tests for the events of the poster.
 *
 * @package    mod_poster
 * @category   test
 * @copyright  2026 bdecent gmbh <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers \mod_poster\event\course_module_viewed
 * @covers \mod_poster\event\course_module_instance_list_viewed
 * @covers \mod_poster\poster
 */
final class events_test extends \advanced_testcase {
    /**
     * Viewing a poster logs the view and completes the activity for "Students must view this activity".
     */
    public function test_course_module_viewed(): void {
        global $CFG;
        require_once($CFG->libdir . '/completionlib.php');
        $this->resetAfterTest();

        $course = $this->getDataGenerator()->create_course(['enablecompletion' => 1]);
        $student = $this->getDataGenerator()->create_and_enrol($course, 'student');
        $poster = $this->getDataGenerator()->create_module('poster', [
            'course' => $course->id,
            'completion' => COMPLETION_TRACKING_AUTOMATIC,
            'completionview' => COMPLETION_VIEW_REQUIRED,
        ]);
        $this->setUser($student);

        $cm = get_coursemodule_from_id('poster', $poster->cmid, 0, false, MUST_EXIST);
        $context = \context_module::instance($cm->id);
        $sink = $this->redirectEvents();
        (new poster($cm))->trigger_module_viewed($context);
        $events = array_values(array_filter($sink->get_events(), fn($e) => $e instanceof course_module_viewed));
        $sink->close();

        $this->assertCount(1, $events);
        $this->assertEquals($poster->id, $events[0]->objectid);
        $this->assertEquals($context->id, $events[0]->contextid);
        $this->assertSame('r', $events[0]->crud);
        $this->assertEventContextNotUsed($events[0]);

        $completion = new \completion_info($course);
        $this->assertEquals(COMPLETION_COMPLETE, $completion->get_data($cm, false, $student->id)->completionstate);
    }

    /**
     * The view of a poster is restored with the course logs (GitHub issue #13).
     */
    public function test_course_module_viewed_objectid_mapping(): void {
        $this->assertSame(['db' => 'poster', 'restore' => 'poster'], course_module_viewed::get_objectid_mapping());
    }

    /**
     * Viewing the list of posters of a course is logged.
     */
    public function test_course_module_instance_list_viewed(): void {
        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course();
        $context = \context_course::instance($course->id);

        $sink = $this->redirectEvents();
        course_module_instance_list_viewed::create(['context' => $context])->trigger();
        $events = $sink->get_events();
        $sink->close();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(course_module_instance_list_viewed::class, $events[0]);
        $this->assertEquals($context->id, $events[0]->contextid);
    }
}
