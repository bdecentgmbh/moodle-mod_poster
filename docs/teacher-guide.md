---
audience: teacher, manager
---

# Poster — Teacher guide

A poster is a page made of blocks: Text blocks, Comments, Logged in user, Recent activity or any
other block you can add. Use it as a course dashboard that collects what students need in one
place, as a project wall, or as a space where students present their work, and keep the course page
itself free of side blocks.

## Adding a poster

1. Turn editing on in your course.
2. Choose *Add an activity or resource* and pick **Poster**.
3. Enter a **Name** and, optionally, a **Description**.
4. Under **Choose where to display the poster contents**, keep *Display poster on separate page*,
   or choose *Display poster on course page* (see [below](#showing-the-poster-on-the-course-page)).
5. Tick **Display description on view page** to show the description on the poster page.
6. Choose *Save and display*. The poster is empty until you add blocks.

The poster page always shows the name of the poster at the top, like every activity page.

## Adding blocks

Open the poster and turn **Edit mode** on. The poster has two columns, each framed by a dashed
line with its own **Add a block** button:

1. Choose **Add a block** in the column where the block should go.
2. Pick a block. Blocks with settings, such as *Text*, open their settings first.
3. Save. The block is added at the end of the column.

Each block has its usual menu for configuring, hiding and deleting it. To move a block to the
other column, drag it by its move icon, or configure it and change **Region** in the *On this
page* section: `mod_poster-pre` is the left column, `mod_poster-post` the right one.

On wide screens the columns are side by side; on phones the right column follows the left one. A
column without blocks leaves the whole width to the other one, so for one wide column put all
blocks into the same column.

In themes without a block drawer, such as Classic in Moodle 4.5, there is one *Add a block* block
in the left column instead of the buttons; move the new block to the right column if needed.

### Missing a block?

Some blocks, such as *Upcoming events*, *Calendar*, *Online users*, *Latest announcements* or
*Random glossary entry*, are only offered on course pages, not on activity pages like the poster.
To show one on a poster:

1. On the course page, add the block and configure it: under *Where this block appears*, set
   **Display on page types** to *Any page*.
2. Open the poster and configure the block again: set **Display on page types** to *Poster module
   main page* and **Region** to `mod_poster-pre` (left column) or `mod_poster-post` (right column).

The block then belongs to the course and appears on every poster of the course, but no longer on
the course page itself.

## Showing the poster on the course page

With **Choose where to display the poster contents** set to *Display poster on course page*, the
blocks of the poster appear right on the course page, in the place of the activity, and there is no
link to a poster page. The description shows above the blocks when *Display description on course
page* is ticked.

While you edit the course page, the poster shows **Go to view page** instead of its blocks. Edit
the blocks there, as described above.

Keep in mind:

- Blocks that need JavaScript in the browser, such as *Comments*, only work on the poster's own
  page. Use *Display poster on separate page* for them.
- Students do not open the poster page, so do not use *Students must view this activity* for
  completion. Let students mark the poster as done instead, or use no completion.
- Course formats without a course page of their own, such as *Single activity*, always show the
  poster on its own page.

## Completion

With *Display poster on separate page*, a poster can be completed when students view it: in the
poster settings, under *Completion conditions*, choose *Students must view this activity*.

## Copying posters

Backup and restore, importing into another course and duplicating a poster copy it with its blocks,
which keep their columns. Blocks added on the course page and moved to the poster (see [Missing a
block?](#missing-a-block)) belong to the course: they are copied with the course, not with the
poster.
