# Changes

## 9.0.0 (2026102800)

- Moodle 4.5 to 5.3; Moodle 4.5 is now the minimum.
- The activity header shows the name and the description of the poster, so the poster page no longer
  shows them twice. "Display description on view page" now hides the description there, too. The
  setting "Display name on view page" is removed: Moodle shows the activity name on every activity
  page.
- In Boost and the themes based on it, each poster column has its own "Add a block" button, which
  adds the block to that column, instead of the old "Add a block" drop-down in the first column.
  While editing, each column is framed together with its button.
- While editing, "Missing a block?" explains how to show blocks such as Upcoming events, which Moodle
  only offers on course pages (#12).
- The columns no longer use Bootstrap 4 classes, which Moodle 5 marks as deprecated.
- Posters shown on the course page show their description above the blocks when "Display
  description on course page" is on, and their blocks are no longer filtered twice. Posters shown on
  their own page no longer cost DB queries on the course page.
- Moodle 5: the list of posters of a course leads to the course's activities overview page.
- Restoring course logs no longer reports the poster's view events (#13).
- The activity has the purpose "Content", which colours its icon.
- Removed code for Moodle versions before 4.0, such as the privacy API polyfill of Moodle 3.3.
- Tests and CI for Moodle 4.5 to 5.3; releases are published on the Moodle Marketplace.
- Documentation for teachers, students, administrators and developers in `docs/`, shown in the
  Documentation Center (tool_mdocs).

## 8.0.0 (2025040100)

- Moodle 4.4 and 4.5 compatibility.

## 7.0.0 (2023120100)

* New maintainer: bdecent gmbh.
* Behat and PHPUnit tests updated and tested on Moodle 4.1 - 4.3.
* Fixed minor coding style issues.
* Added monologo for Moodle 4.x.
* Fixed improper use of require_login method instead of require_course_login (resolves #10).
* New feature: Display block content on course page (based on work by Harald, David und Nicklas @devcamp19 – Thanks!).

## 6.0.1 (2021022101)

* Behat and PHPUnit tests updated and tested on Moodle 3.9 - 3.11.
* Fixed minor coding style issues reported by phpcs.
* Git `master` branch renamed to `main`.

## 6.0.0 (2021022100)

* Improved styling of the "Add a block" widget to distinguish it from other existing
  blocks on the page.
* Dropped support for legacy  Bootstrap 2.x grid and CSS classes. Supported are
  Bootstrap 4.x based themes such as Boost or Classic.
* Fixed minor coding style issues.
* Travis-CI replaced with Github actions.
* Supported Moodle versions 3.9 LTS and higher.

## 5.2.2 (2020081200)

* Behat tests updated and tested on Moodle 3.5 - 3.9

## 5.2.1 (2018120200)

* Behat tests updated to run on Moodle 3.6

## 5.2.0 (2018090500)

* [Privacy API](https://docs.moodle.org/dev/Privacy_API) implemented. The Poster
  plugin does not store any personal data.
* Moodle 3.5 added as a supported version.

## 5.1.0 (2017111100)

* In editing mode, the "Add a block" drop down selector is now displayed
  in the Boost based themes, too - making it easier and more intuitive
  to add blocks to the poster regions.

## 5.0.0 (2017051200)

* Improved the styling of the poster editing page
* Tested with Moodle 3.2 and 3.3 under Boost, Clean and More themes

## 4.0.1 (2015102601)

* Added travis-ci support
* Coding style fixes

## v4 (2015102600)

* Added behat tests for the main features (adding instance, adding contents)
* Checked compatibility with Moodle 3.0 version
* Switching over to the stable maturity level

## v3 (2015071400)

* Fixed the required Moodle 2.7 version, credit goes to Howard Miller for the report.

## v2 (2015061100)

* Fixed the missing module help string (displayed when adding the poster into the course).

## v1 (2015052100)

* Added support for backup & restore.
* Fixed usage of deprecated `add_intro_editor()` method in Moodle 2.9 and higher.
* The blocks docking widget is now hidden by CSS.
* Reaching the beta maturity as all planned features are implemented now. Staring to support proper upgrade procedures from now.

## v0

* Initial release of the module, still in alpha version.
* Basic functionality works.
* Needs to add backup & restore support yet.
