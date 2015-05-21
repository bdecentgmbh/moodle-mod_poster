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
 * Displays list of all posters in the course.
 *
 * @package     mod_poster
 * @copyright   2015 David Mudrak <david@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../config.php');

$id = required_param('id', PARAM_INT);

$course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);

require_course_login($course, true);

$PAGE->set_pagelayout('incourse');
$PAGE->set_url('/mod/poster/index.php', array('id' => $course->id));
$PAGE->set_title($course->shortname.': '.get_string('modulenameplural', 'mod_poster'));
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add(get_string('modulenameplural', 'mod_poster'));

// Trigger instances list viewed event.
$event = \mod_poster\event\course_module_instance_list_viewed::create(array(
    'context' => context_course::instance($course->id)
));
$event->add_record_snapshot('course', $course);
$event->trigger();

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('modulenameplural', 'mod_poster'));

if (!$posters = get_all_instances_in_course('poster', $course)) {
    notice(get_string('thereareno', 'core', get_string('modulenameplural', 'mod_poster')),
        new moodle_url('/course/view.php', array('id' => $course->id)));
}

$usesections = course_format_uses_sections($course->format);

$table = new html_table();
$table->attributes['class'] = 'generaltable mod_index';

if ($usesections) {
    $table->head = array(
        get_string('sectionname', 'format_'.$course->format),
        get_string('postername', 'mod_poster'),
        get_string('moduleintro', 'core')
    );
    $table->align = array('center', 'left', 'left');

} else {
    $table->head = array(
        get_string('lastmodified', 'core'),
        get_string('postername', 'mod_poster'),
        get_string('moduleintro', 'core')
    );
    $table->align = array('left', 'left', 'left');
}

$modinfo = get_fast_modinfo($course);
$currentsection = '';

foreach ($posters as $poster) {
    $cm = $modinfo->cms[$poster->coursemodule];
    if ($usesections) {
        $printsection = '';
        if ($poster->section !== $currentsection) {
            if ($poster->section) {
                $printsection = get_section_name($course, $poster->section);
            }
            if ($currentsection !== '') {
                $table->data[] = 'hr';
            }
            $currentsection = $poster->section;
        }
    } else {
        $printsection = html_writer::span(userdate($poster->timemodified), 'smallinfo');
    }

    $table->data[] = array(
        $printsection,
        html_writer::link(
            new moodle_url('view.php', array('id' => $cm->id)),
            format_string($poster->name),
            array('class' => $poster->visible ? '' : 'dimmed')
        ),
        format_module_intro('poster', $poster, $cm->id)
    );
}

echo html_writer::table($table);

echo $OUTPUT->footer();
