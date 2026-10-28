@mod @mod_poster
Feature: Showing a poster on the course page
  In order to show blocks right in the course
  As a teacher
  I need to display the poster on the course page

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
      | activity | name        | intro         | course | idnumber | display | showdescription |
      | poster   | Course wall | Read me first | C1     | poster1  | 1       | 1               |
    And the following "blocks" exist:
      | blockname | contextlevel    | reference | pagetypepattern | defaultregion  |
      | myprofile | Activity module | poster1   | mod-poster-view | mod_poster-pre |

  Scenario: Students see the description and the blocks of the poster on the course page
    When I am on the "Course 001" course page logged in as "student1"
    Then I should see "Read me first"
    And I should see "Logged in user" in the "#mod_poster-content-region-pre" "css_element"
    And "Course wall" "link" should not exist in the "region-main" "region"

  @javascript
  Scenario: Teachers edit the blocks of the poster on its own page
    Given I am on the "Course 001" course page logged in as "teacher1"
    And I turn editing mode on
    And I should see "These blocks should be edited on Poster view page"
    And "#mod_poster-content" "css_element" should not exist
    When I click on "Go to view page" "link"
    Then I should see "Logged in user" in the "#mod_poster-content-region-pre" "css_element"
    And I should see "Add a block" in the "#mod_poster-content-region-post" "css_element"
