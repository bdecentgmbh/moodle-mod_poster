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

namespace mod_poster;

#[\PHPUnit\Framework\Attributes\CoversClass(\backup_poster_activity_task::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\backup_poster_activity_structure_step::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\restore_poster_activity_task::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\restore_poster_activity_structure_step::class)]
/**
 * Tests for the backup and restore of posters.
 *
 * @package    mod_poster
 * @copyright  2026 bdecent gmbh <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers \backup_poster_activity_task
 * @covers \backup_poster_activity_structure_step
 * @covers \restore_poster_activity_task
 * @covers \restore_poster_activity_structure_step
 */
final class backup_restore_test extends \advanced_testcase {
    #[\Override]
    public static function setUpBeforeClass(): void {
        global $CFG;
        require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
        require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');
        parent::setUpBeforeClass();
    }

    /**
     * A copied course has the poster with its settings and its blocks in the same columns.
     */
    public function test_backup_restore(): void {
        global $DB, $USER;
        $this->resetAfterTest();
        $this->setAdminUser();

        $course = $this->getDataGenerator()->create_course();
        $poster = $this->getDataGenerator()->create_module('poster', [
            'course' => $course->id,
            'name' => 'Course wall',
            'intro' => 'See <a href="https://www.example.com/mod/poster/view.php?id=1">the poster</a>.',
            'display' => POSTER_DISPLAY_INLINE,
            'showdescriptionview' => 1,
        ]);
        $this->getDataGenerator()->create_block('html', [
            'parentcontextid' => \context_module::instance($poster->cmid)->id,
            'pagetypepattern' => 'mod-poster-view',
            'defaultregion' => 'mod_poster-post',
            'configdata' => base64_encode(serialize((object) ['title' => 'Notes', 'text' => 'Hello', 'format' => FORMAT_HTML])),
        ]);

        $bc = new \backup_controller(
            \backup::TYPE_1COURSE,
            $course->id,
            \backup::FORMAT_MOODLE,
            \backup::INTERACTIVE_NO,
            \backup::MODE_IMPORT,
            $USER->id
        );
        $backupid = $bc->get_backupid();
        $bc->execute_plan();
        $bc->destroy();

        $newcourseid = \restore_dbops::create_new_course('Copy', 'copy', $course->category);
        $rc = new \restore_controller(
            $backupid,
            $newcourseid,
            \backup::INTERACTIVE_NO,
            \backup::MODE_IMPORT,
            $USER->id,
            \backup::TARGET_NEW_COURSE
        );
        $this->assertTrue($rc->execute_precheck());
        $rc->execute_plan();
        $rc->destroy();

        $restored = $DB->get_record('poster', ['course' => $newcourseid], '*', MUST_EXIST);
        $this->assertSame('Course wall', $restored->name);
        $this->assertEquals(POSTER_DISPLAY_INLINE, $restored->display);
        $this->assertEquals(1, $restored->showdescriptionview);

        $cm = get_coursemodule_from_instance('poster', $restored->id, $newcourseid, false, MUST_EXIST);
        $blocks = $DB->get_records('block_instances', ['parentcontextid' => \context_module::instance($cm->id)->id]);
        $this->assertCount(1, $blocks);
        $block = reset($blocks);
        $this->assertSame('html', $block->blockname);
        $this->assertSame('mod-poster-view', $block->pagetypepattern);
        $this->assertSame('mod_poster-post', $block->defaultregion);
    }
}
