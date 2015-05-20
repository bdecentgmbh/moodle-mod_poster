Moodle Poster activity module
=============================

Poster is a resource activity module for Moodle. It allows teachers to create a page for their students. What makes this module
unique is that the contents of the poster page is composed of Moodle blocks (such as HTML blocks, Calendar block, Latest news block
etc.). It provides yet another place within the course where blocks can be put without polluting the course front page. The module
natively supports responsive layout in bootstrap2 and bootstrap3 based themes.

Usage
-----

To use the module, you should understand how Moodle sticky blocks work. See [Block
settings](https://docs.moodle.org/en/Block_settings) page for more details.

1. Add the module instance into the course.
2. Turn editing mode on.
3. Add the Moodle blocks you want to display on the poster and configure them so that they are displayed in the context of the
   poster, on page type `mod-poster-view`, inside region `mod_poster-pre` or `mod_poster-post`.

The poster can be used as for example:

* Course wall/dashboard (contact teachers, detailed outline of the course, latest news, comments, ...).
* Project dashboard (project goals, calendar, comments, people, ...)
* Research report (goals, methods, results, comments, ...)

Implementation
--------------

The Poster module uses not so well known feature of the Moodle blocks architecture. In almost all cases, it is the theme that
defines regions where plugins can be added to. However in special cases, such as this one, any Moodle plugin can define its custom
block regions.  Within the context of the Poster module instance, when displaying its view.php page, two extra block regions are
defined - `mod_poster-pre` and `mod_poster-post`. The Poster module itself is just a tiny wrapper for displaying these two regions
as its content. Simple and clever.

Author
------

The Poster module has been written and is currently maintained by David Mudrák <david@moodle.com>

Licence
-------

Copyright (C) 2015 David Mudrák <david@moodle.com>

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as
published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see
<http://www.gnu.org/licenses/>.
