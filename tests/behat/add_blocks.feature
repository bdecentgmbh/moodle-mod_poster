@mod @mod_poster
Feature: Adding blocks to the poster page
  In order to have some contents displayed at a poster page
  As a teacher
  I need to add blocks to the regions provided by the poster view page

  Scenario: Add multiple blocks to the poster page
    Given the following "users" exist:
      | username    | firstname | lastname  | email                |
      | teacher1    | Teacher   | 1         | teacher1@example.com |
      | student1    | Student   | 1         | student1@example.com |
    And the following "courses" exist:
      | fullname    | shortname | category  |
      | Course 001  | C1        | 0         |
    And the following "course enrolments" exist:
      | user        | course    | role              |
      | teacher1    | C1        | editingteacher    |
      | student1    | C1        | student           |
    And the following "activities" exist:
      | activity    | name                  | intro                         | course  | idnumber  |
      | poster      | Poster 003            | This is a test poster 003.    | C1      | poster003 |
    And I log in as "teacher1"
    And I am on "Course 001" course homepage with editing mode on
    And I am on the "Poster 003" "poster activity" page
    And I select "html" from the "bui_addblock" singleselect
    And I configure the "(new text block)" block
    And I set the field "config_title" to "Created in poster context"
    And I set the field "Content" to "This is first HTML block displayed at a poster page"
    And I set the field "Region" to "mod_poster-pre"
    And I press "Save changes"
    And I am on "Course 001" course homepage with editing mode on
    And I add the "Text" block
    And I configure the "(new text block)" block
    And I set the field "config_title" to "Created in course context"
    And I set the field "Content" to "This is second HTML block displayed at a poster page"
    And I set the field "Display on page types" to "Any page"
    And I press "Save changes"
    And I follow "Poster 003"
    And I configure the "Created in course context" block
    And I set the field "Display on page types" to "Poster module main page"
    And I set the field "Region" to "mod_poster-pre"
    And I press "Save changes"
    And I select "Logged in user" from the "bui_addblock" singleselect
    And I configure the "Logged in user" block
    And I set the field "Region" to "mod_poster-post"
    And I press "Save changes"
    And I log out
    When I log in as "student1"
    And I am on "Course 001" course homepage
    Then I should not see "This is second HTML block displayed at a poster page"
    And I follow "Poster 003"
    And I should see "This is first HTML block displayed at a poster page" in the "#mod_poster-content-region-pre" "css_element"
    And I should see "This is second HTML block displayed at a poster page" in the "#mod_poster-content-region-pre" "css_element"
    And I should see "Logged in user" in the "#mod_poster-content-region-post" "css_element"
