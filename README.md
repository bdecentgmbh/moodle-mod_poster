# Poster

[![Moodle plugin CI](https://github.com/bdecentgmbh/moodle-mod_poster/actions/workflows/moodle-ci.yml/badge.svg)](https://github.com/bdecentgmbh/moodle-mod_poster/actions/workflows/moodle-ci.yml)

Poster is a Moodle activity that makes a page out of blocks: Text blocks, Comments, Logged in user,
Recent activity or any other block. Teachers use it as a course dashboard, a project wall or a space
where students present their work, and keep the course page itself free of side blocks.

## Features

- **Two columns of blocks**, side by side on wide screens and one below the other on phones. A
  column without blocks leaves the whole width to the other one.
- **"Add a block" in each column** (Boost and the themes based on it), so blocks land where they
  belong. Blocks can be dragged between the columns.
- **On its own page or on the course page**: a poster can show its blocks right on the course
  page, like a text and media area.
- **Description** on the poster page, optional.
- **Activity completion** when students view the poster.
- **Backup and restore**, duplicating and importing, with the blocks of the poster.
- **Activities overview** (Moodle 5): posters are listed with the other resources of the course.

## Requirements

Moodle 4.5 to 5.3.

## Installation

1. Download the plugin or clone it into `mod/poster` (`public/mod/poster` from Moodle 5.1).
2. Go to *Site administration > Notifications* to complete the installation.

The poster has no settings of its own.

## Documentation

- [Teacher guide](docs/teacher-guide.md): adding a poster, its blocks and its display
- [Student guide](docs/student-guide.md)
- [Administrator guide](docs/admin-guide.md): installation, upgrading, capabilities
- [Developer guide](docs/developer-guide.md): how the poster works, tests and releases

With the Documentation Center (`tool_mdocs`) installed, the guides are also offered on the poster
pages.

## Author and maintainer

Poster was written by [David Mudrák](https://github.com/mudrd8mz) in 2015, who maintained it up to
version 6.0.1. Since version 7.0.0 (2023), it is maintained by [bdecent gmbh](https://bdecent.de).
Thanks to David for creating the poster and handing it over, and to everyone who reported issues
and contributed.

Issues and pull requests: https://github.com/bdecentgmbh/moodle-mod_poster

## License

Copyright (C) 2015 David Mudrák <david@moodle.com>
Copyright (C) 2023 onwards bdecent gmbh <https://bdecent.de>

This program is free software: you can redistribute it and/or modify it under the terms of the GNU
General Public License as published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General
Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not,
see <https://www.gnu.org/licenses/>.
