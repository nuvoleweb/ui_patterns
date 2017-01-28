@api
Feature: Setup
  In order to properly test the UI Patterns module
  As a developer
  I want to be sure that the test environment is properly setup.

  Background:

    Given users:
      | name    | mail              | pass |
      | test    | test@example.com  | pass |

  Scenario: Test that Behat can access a Drupal working copy.

    Given I am not logged in
    When I visit "/user"

    Then I should see "Username"
    When I enter "test" for "Username"
    And I enter "pass" for "Password"
    And I press the "Log in" button

  Scenario: Test that required modules are enabled.
    Then the following modules are enabled:
      | ui_patterns            |
      | ui_patterns_test       |

  @javascript
  Scenario: Test that JavaScript driver is properly setup.

    Given I am logged in as a user with the "administrator" role
    And I am on "admin/modules"

    Then I should see "Actions"

    When I fill in "text" with "Block"
    And I wait 2 seconds

    Then I should not see "Actions"
    But I should see "Block"
