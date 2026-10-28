# Poster — Administrator guide

Poster (`mod_poster`) is an activity that makes a page out of blocks. Teachers add blocks to its two
columns and show the poster on a page of its own or right on the course page. This guide covers
installing, upgrading and permissions. For using it in a course, see the
[teacher guide](teacher-guide.md).

## Requirements

Moodle 4.5 to 5.3. No other plugins are needed.

## Installation

1. Download the plugin or clone it into `mod/poster` (`public/mod/poster` from Moodle 5.1).
2. Go to *Site administration > Notifications* to complete the installation.

The poster has no settings of its own.

## Upgrading to 9.0.0

- Moodle 4.5 is now the minimum.
- The setting *Display name on view page* is removed, and its database field with it: Moodle shows
  the activity name on every activity page, so posters showed it twice. Nothing else changes for
  existing posters; their blocks stay where they are.
- On sites with Boost or a theme based on it, each poster column gets its own *Add a block*
  button.

## Permissions

| Capability | Default roles | Allows |
|---|---|---|
| `mod/poster:addinstance` | Editing teacher, Manager | Adding a poster to a course |
| `mod/poster:view` | Guest, Student, Teacher, Editing teacher, Manager | Viewing a poster |

Adding, configuring and moving blocks on a poster needs the usual block capabilities in the poster:
`moodle/site:manageblocks` (editing teachers have it) and the `block/<name>:addinstance` capability
of each block.

## Which blocks can be added

Each block decides on which pages it can be added. Blocks that are only meant for course pages,
such as *Upcoming events*, *Calendar*, *Online users* or *Latest announcements*, are not offered on
the poster. Teachers can still move them from the course page to the posters of the course; the
teacher guide and *Missing a block?* on the poster page explain how. Blocks disabled under *Site
administration > Plugins > Blocks > Manage blocks* are not offered anywhere.

## Privacy

The poster stores no personal data. Blocks on a poster store their own data, such as comments, and
handle it with their own privacy providers.

## Backup and restore

Posters are backed up and restored with their settings and blocks, also when importing or
duplicating them. Restoring course logs includes the views of posters.

## Activities overview (Moodle 5)

From Moodle 5.0, the list of posters of a course (`mod/poster/index.php`) leads to the course's
activities overview page, where posters are listed with the other resources.
