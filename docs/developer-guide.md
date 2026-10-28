---
exclude: true
---

# Poster — Developer guide

How the poster is built, for people changing it, styling it in a theme or testing it.

## How it works

Block regions usually come from the theme's layouts, but a page may add regions of its own. On its
view page (page type `mod-poster-view`), the poster adds two: `mod_poster-pre` (left column) and
`mod_poster-post` (right column). Its content is nothing but these two regions. Region names are
limited to 16 characters.

| Part | Where | What it does |
|---|---|---|
| Page setup | `classes/poster.php` | `setup_page()` sets URL, title and activity record and adds the two regions |
| View page | `view.php` | Access checks, the edit button for themes without edit switch, the description in the activity header, the view event and completion |
| Rendering | `renderer.php`, `templates/content.mustache` | The regions in a CSS grid (`styles.css`), with an *Add a block* button per column while editing |
| Course page display | `lib.php` | `poster_get_coursemodule_info()`, `poster_cm_info_dynamic()`, `poster_cm_info_view()` |
| Page types for blocks | `lib.php` | `poster_page_type_list()` offers *Poster module main page* (`mod-poster-view`) |
| Index | `index.php` | Moodle 5: redirect to the activities overview (resources); Moodle 4.5: the list of posters |

Moodle adds a custom region to `$SESSION->custom_block_regions` for the page type. That is how block
actions in modal forms, which rebuild the page from its hash without running `view.php`, still know
the poster's regions.

## Data

Table `poster`: `course`, `name`, `intro`, `introformat`, `timecreated`, `timemodified`,
`showdescriptionview` (show the description in the activity header of the view page) and `display`
(`POSTER_DISPLAY_PAGE` = 0, `POSTER_DISPLAY_INLINE` = 1). Version 9.0.0 (2026102800) dropped
`shownameview`; restoring older backups ignores it.

The blocks are ordinary block instances in the module context with page type pattern
`mod-poster-view`. Core backs them up, restores and deletes them with the activity.

## Name and description

Since Moodle 4.0 the activity header shows the activity name and description on every view page.
The poster leaves both to it and only clears the description when `showdescriptionview` is off
(`$PAGE->activityheader->set_description('')` in `view.php`).

## Adding blocks

Themes whose `addblockposition` is `BLOCK_ADDBLOCK_POSITION_FLATNAV` (Boost and its children) get
`core_renderer::addblockbutton($region)` in each column, which adds the block to that region. Other
themes show the *Add a block* block of core in the default region, `mod_poster-pre`.

## Showing the poster on the course page

`poster_get_coursemodule_info()` caches `customdata = ['display' => POSTER_DISPLAY_INLINE]` for
posters shown on the course page; other posters have no custom data and cost no queries in the
callbacks below.

- `poster_cm_info_dynamic()` removes the view link.
- `poster_cm_info_view()` builds a `moodle_page` in the module context, sets it up like the view
  page, loads its blocks and renders `view_page_content()` below the formatted description. The
  content is set as formatted, so it is not filtered again. While the course page is edited, it
  renders `mod_poster/edit_label` instead, with a link to the view page.

The blocks' JavaScript is required on that separate page, not on the course page, so blocks that
need it (such as *Comments*) only work on the view page.

## Theming

The template `mod_poster/content` and these selectors are stable and can be used in themes:

| Selector | Element |
|---|---|
| `#mod_poster-content` | The poster content; `.mod_poster-content-editing` while editing |
| `.mod_poster-columns`, `.mod_poster-columns-1`, `.mod_poster-columns-2` | The grid and the number of columns shown |
| `#mod_poster-content-region-pre`, `#mod_poster-content-region-post` | The columns |
| `.empty-region-mod_poster-pre`, `.empty-region-mod_poster-post` | On `#mod_poster-content` when a column has no blocks |

## Events, privacy

`\mod_poster\event\course_module_viewed` (with `get_objectid_mapping()` for restoring logs) and
`\mod_poster\event\course_module_instance_list_viewed`. The privacy provider is a null provider:
the poster stores no personal data.

## Tests

- PHPUnit (`tests/`): `lib_test` (callbacks, both display modes, no queries for posters on their
  own page), `backup_restore_test` (settings and blocks in their columns), `event/events_test`,
  `privacy/provider_test`, `mod_poster_generator_test`.
- Behat (`tests/behat/`): `add_poster.feature` (description setting), `add_blocks.feature` (adding
  blocks to each column, moving a course block to the poster), `course_page.feature` (display on
  the course page).

Covered classes and functions are declared with both PHPUnit attributes (Moodle 5) and `@covers`
(Moodle 4.5).

## CI and releases

`.github/workflows/moodle-ci.yml` runs moodle-plugin-ci 4 on pull requests and on `main`: Moodle 5.3
first, then 5.2, 5.1 and 4.5. Work happens on `dev`, which reaches `main` through a pull request.

To release, merge `dev` into `main`, then tag the merge commit `v<release>` (e.g. `v9.0.0`, matching
`$plugin->release`). `moodle-release.yml` checks the tag, takes the notes from the section
`## <release> (<version>)` of `CHANGES.md`, waits for CI on the tagged commit, publishes the version on
the Moodle Marketplace (secret `MOODLE_MARKETPLACE_TOKEN`) and creates the GitHub Release with the
ZIP. `.gitattributes` keeps `.github` out of the ZIP.
