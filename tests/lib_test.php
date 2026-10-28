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

#[\PHPUnit\Framework\Attributes\CoversFunction('poster_supports')]
#[\PHPUnit\Framework\Attributes\CoversFunction('poster_add_instance')]
#[\PHPUnit\Framework\Attributes\CoversFunction('poster_update_instance')]
#[\PHPUnit\Framework\Attributes\CoversFunction('poster_delete_instance')]
#[\PHPUnit\Framework\Attributes\CoversFunction('poster_page_type_list')]
#[\PHPUnit\Framework\Attributes\CoversFunction('poster_get_coursemodule_info')]
#[\PHPUnit\Framework\Attributes\CoversFunction('poster_cm_info_dynamic')]
#[\PHPUnit\Framework\Attributes\CoversFunction('poster_cm_info_view')]
#[\PHPUnit\Framework\Attributes\CoversClass(poster::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\mod_poster_renderer::class)]
/**
 * Tests for the functions in lib.php and the display of posters.
 *
 * @package    mod_poster
 * @copyright  2026 bdecent gmbh <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers ::poster_supports
 * @covers ::poster_add_instance
 * @covers ::poster_update_instance
 * @covers ::poster_delete_instance
 * @covers ::poster_page_type_list
 * @covers ::poster_get_coursemodule_info
 * @covers ::poster_cm_info_dynamic
 * @covers ::poster_cm_info_view
 * @covers \mod_poster\poster
 * @covers \mod_poster_renderer
 */
final class lib_test extends \advanced_testcase {
    #[\Override]
    public static function setUpBeforeClass(): void {
        global $CFG;
        require_once($CFG->dirroot . '/mod/poster/lib.php');
        parent::setUpBeforeClass();
    }

    /**
     * Adds a Text block to a column of a poster.
     *
     * @param \stdClass $poster The poster as created by the generator
     * @param string $region mod_poster-pre or mod_poster-post
     * @param string $text Content of the block
     */
    private function add_text_block(\stdClass $poster, string $region, string $text): void {
        $this->getDataGenerator()->create_block('html', [
            'parentcontextid' => \context_module::instance($poster->cmid)->id,
            'pagetypepattern' => 'mod-poster-view',
            'defaultregion' => $region,
            'configdata' => base64_encode(serialize((object) ['title' => 'Block', 'text' => $text, 'format' => FORMAT_HTML])),
        ]);
    }

    /**
     * The features the poster supports.
     */
    public function test_supports(): void {
        $this->assertSame(MOD_ARCHETYPE_RESOURCE, poster_supports(FEATURE_MOD_ARCHETYPE));
        $this->assertSame(MOD_PURPOSE_CONTENT, poster_supports(FEATURE_MOD_PURPOSE));
        $this->assertTrue(poster_supports(FEATURE_BACKUP_MOODLE2));
        $this->assertTrue(poster_supports(FEATURE_COMPLETION_TRACKS_VIEWS));
        $this->assertFalse(poster_supports(FEATURE_GRADE_HAS_GRADE));
        $this->assertNull(poster_supports('unknownfeature'));
    }

    /**
     * Adding, updating and deleting a poster.
     */
    public function test_add_update_delete_instance(): void {
        global $DB;
        $this->resetAfterTest();
        $this->setAdminUser();

        $course = $this->getDataGenerator()->create_course();
        $poster = $this->getDataGenerator()->create_module('poster', [
            'course' => $course->id,
            'name' => 'Course wall',
            'display' => POSTER_DISPLAY_INLINE,
            'showdescriptionview' => 1,
        ]);
        $record = $DB->get_record('poster', ['id' => $poster->id], '*', MUST_EXIST);
        $this->assertSame('Course wall', $record->name);
        $this->assertEquals(POSTER_DISPLAY_INLINE, $record->display);
        $this->assertEquals(1, $record->showdescriptionview);
        $this->assertGreaterThan(0, $record->timecreated);

        $this->assertTrue(poster_update_instance((object) [
            'instance' => $poster->id,
            'course' => $course->id,
            'name' => 'Project wall',
            'intro' => '',
            'introformat' => FORMAT_HTML,
            'display' => POSTER_DISPLAY_PAGE,
            'showdescriptionview' => 0,
        ]));
        $record = $DB->get_record('poster', ['id' => $poster->id], '*', MUST_EXIST);
        $this->assertSame('Project wall', $record->name);
        $this->assertEquals(POSTER_DISPLAY_PAGE, $record->display);
        $this->assertEquals(0, $record->showdescriptionview);

        $this->assertTrue(poster_delete_instance($poster->id));
        $this->assertFalse($DB->record_exists('poster', ['id' => $poster->id]));
        $this->assertFalse(poster_delete_instance($poster->id));
    }

    /**
     * Blocks can be added to the poster page, and only there.
     */
    public function test_page_type_list(): void {
        $this->assertSame(['mod-poster-view'], array_keys(poster_page_type_list('mod-poster-view', null, null)));
    }

    /**
     * A poster shown on its own page links there, shows its description if asked to, and costs no DB queries.
     */
    public function test_page_display(): void {
        global $DB;
        $this->resetAfterTest();

        $course = $this->getDataGenerator()->create_course();
        $student = $this->getDataGenerator()->create_and_enrol($course, 'student');
        $poster = $this->getDataGenerator()->create_module('poster', [
            'course' => $course->id,
            'intro' => 'Read me first',
            'showdescription' => 1,
            'display' => POSTER_DISPLAY_PAGE,
        ]);
        $this->add_text_block($poster, 'mod_poster-pre', 'Only on the poster page');
        $this->setUser($student);

        $cm = get_fast_modinfo($course)->get_cm($poster->cmid);
        $this->assertTrue($cm->uservisible);
        $queries = $DB->perf_get_queries();
        $content = $cm->content;
        $this->assertSame($queries, $DB->perf_get_queries());

        $this->assertNotNull($cm->url);
        $this->assertEmpty($cm->customdata);
        $this->assertStringContainsString('Read me first', $content);
        $this->assertStringNotContainsString('Only on the poster page', $cm->get_formatted_content());
    }

    /**
     * A poster shown on the course page shows its description and blocks there, and has no page of its own.
     */
    public function test_course_page_display(): void {
        $this->resetAfterTest();

        $course = $this->getDataGenerator()->create_course();
        $student = $this->getDataGenerator()->create_and_enrol($course, 'student');
        $poster = $this->getDataGenerator()->create_module('poster', [
            'course' => $course->id,
            'intro' => 'Read me first',
            'showdescription' => 1,
            'display' => POSTER_DISPLAY_INLINE,
        ]);
        $this->add_text_block($poster, 'mod_poster-pre', 'Left column block');
        $this->add_text_block($poster, 'mod_poster-post', 'Right column block');
        $this->setUser($student);

        $cm = get_fast_modinfo($course)->get_cm($poster->cmid);
        $this->assertNull($cm->url);
        $content = $cm->get_formatted_content();
        $this->assertStringContainsString('Read me first', $content);
        $this->assertMatchesRegularExpression('~id="mod_poster-content-region-pre".*Left column block~s', $content);
        $this->assertMatchesRegularExpression('~id="mod_poster-content-region-post".*Right column block~s', $content);
        $this->assertStringContainsString('mod_poster-columns-2', $content);
        $this->assert_text_before('Read me first', 'Left column block', $content);
    }

    /**
     * While editing the course page, a poster shown there links to its own page, where its blocks are edited.
     */
    public function test_course_page_display_editing(): void {
        global $PAGE, $USER;
        $this->resetAfterTest();
        $this->setAdminUser();

        $course = $this->getDataGenerator()->create_course();
        $poster = $this->getDataGenerator()->create_module('poster', [
            'course' => $course->id,
            'display' => POSTER_DISPLAY_INLINE,
        ]);
        $this->add_text_block($poster, 'mod_poster-pre', 'Left column block');

        $PAGE->set_course($course);
        $PAGE->set_url('/course/view.php', ['id' => $course->id]);
        $USER->editing = 1;
        $this->assertTrue($PAGE->user_is_editing());

        $content = get_fast_modinfo($course)->get_cm($poster->cmid)->get_formatted_content();
        $this->assertStringContainsString('/mod/poster/view.php?id=' . $poster->cmid, $content);
        $this->assertStringNotContainsString('Left column block', $content);
    }

    /**
     * A poster without blocks shows nothing but its description on the course page.
     */
    public function test_course_page_display_without_blocks(): void {
        $this->resetAfterTest();

        $course = $this->getDataGenerator()->create_course();
        $student = $this->getDataGenerator()->create_and_enrol($course, 'student');
        $poster = $this->getDataGenerator()->create_module('poster', [
            'course' => $course->id,
            'intro' => 'Read me first',
            'display' => POSTER_DISPLAY_INLINE,
        ]);
        $this->setUser($student);

        $content = get_fast_modinfo($course)->get_cm($poster->cmid)->get_formatted_content();
        $this->assertStringNotContainsString('mod_poster-content', $content);
        // The description shows only with "Display description on course page".
        $this->assertStringNotContainsString('Read me first', $content);
    }

    /**
     * Asserts that a text comes before another one.
     *
     * @param string $first
     * @param string $second
     * @param string $haystack
     */
    private function assert_text_before(string $first, string $second, string $haystack): void {
        $this->assertLessThan(strpos($haystack, $second), strpos($haystack, $first));
    }
}
