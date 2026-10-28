@mod @mod_poster
Feature: Adding blocks to the poster page
  In order to have some contents displayed at a poster page
  As a teacher
  I need to add blocks to the regions provided by the poster view page

  Background:
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

  @javascript
  Scenario: Add blocks to the columns of the poster page
    Given I am on the "Poster 003" "poster activity" page logged in as "teacher1"
    And I turn editing mode on
    And I should see "Missing a block?"
    When I add the "Text" block to the mod_poster-post region with:
      | Text block title | Created in poster context                           |
      | Content          | This is first HTML block displayed at a poster page |
    And I add the "Logged in user" block to the "mod_poster-pre" region
    Then I should see "This is first HTML block displayed at a poster page" in the "#mod_poster-content-region-post" "css_element"
    And I should see "Logged in user" in the "#mod_poster-content-region-pre" "css_element"
    And I am on the "Poster 003" "poster activity" page logged in as "student1"
    And I should see "This is first HTML block displayed at a poster page" in the "#mod_poster-content-region-post" "css_element"
    And I should see "Logged in user" in the "#mod_poster-content-region-pre" "css_element"
    And I should not see "Missing a block?"
    And I am on "Course 001" course homepage
    And I should not see "This is first HTML block displayed at a poster page"

  @javascript
  Scenario: Move a block of the course page to the poster page
    Given I am on the "Course 001" course page logged in as "teacher1"
    And I turn editing mode on
    And I add the "Text" block to the default region with:
      | Text block title | Created in course context          |
      | Content          | This is a block of the course page |
    And I configure the "Created in course context" block
    And I set the following fields to these values:
      | Display on page types | Any page |
    And I press "Save changes"
    And I am on the "Poster 003" "poster activity" page
    And I configure the "Created in course context" block
    And I set the following fields to these values:
      | Display on page types | Poster module main page |
      | Region                | mod_poster-pre          |
    And I press "Save changes"
    When I am on the "Poster 003" "poster activity" page logged in as "student1"
    Then I should see "This is a block of the course page" in the "#mod_poster-content-region-pre" "css_element"
    And I am on "Course 001" course homepage
    And I should not see "This is a block of the course page"
