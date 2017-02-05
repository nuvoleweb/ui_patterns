@api
Feature: Configuration
  In order to be able to use patterns on my site
  As a developer
  I want to be able to work with patterns-related configuration.

  Background:
    Given I view the site on a "desktop" device
    And I am logged in as a user with the "administrator" role

  @javascript
  @preserveConfiguration
  Scenario: I can create field groups using the Patterns plugin.

    Given I visit "/admin/structure/types/manage/page"
    And I click "Manage display"
    Then I should see the link "Add group"

    When I click "Add group"
    Then I should have the following options for "Add a new group":
      | option  |
      | Pattern |
    And I select "Pattern" from "Add a new group"
    And I wait "2" seconds
    And I fill in "Label" with "My field group"
    And I wait "2" seconds
    And I press the "Save and continue" button
    Then I should see "Attention: you have to add fields to this field group and save the whole entity display before being able to to access the pattern display configuration."
    And I press "Create group"
    And I move the "Tags" row under the "My field group" row
    And I move the "Body" row under the "My field group" row
