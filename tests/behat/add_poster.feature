@mod @mod_poster @add_poster
Feature: Adding poster activity
  In order to display blocks not only on the course page side bars
  As a teacher
  I need to add poster activity module to a course

  Background:
    Given the following "users" exist:
      | username    | firstname | lastname  | email                |
      | student1    | Student   | 1         | student1@example.com |
    And the following "courses" exist:
      | fullname    | shortname | category  |
      | Course 001  | C1        | 0         |
    And the following "course enrolments" exist:
      | user        | course    | role              |
      | student1    | C1        | student           |

  Scenario: Description can be shown or hidden depending on the setting "Display description on view page"
    Given the following "activities" exist:
      | activity    | name                  | intro                         | course  | idnumber  | showdescriptionview |
      | poster      | Poster 001            | This is a test poster 001.    | C1      | poster001 | 1                   |
      | poster      | Poster 002            | This is a test poster 002.    | C1      | poster002 | 0                   |
    When I log in as "student1"
    And I am on "Course 001" course homepage
    And I follow "Poster 001"
    Then I should see "This is a test poster 001." in the "#mod_poster-description" "css_element"
    And I am on "Course 001" course homepage
    And I follow "Poster 002"
    And "#mod_poster-description" "css_element" should not exist
