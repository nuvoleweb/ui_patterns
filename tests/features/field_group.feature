@api
Feature: Field group module integration
  In order to be able tu use patterns as field groups templates
  As a developer
  I want to be able to make sure the ui_patterns_field_group module works properly.

  @javascript
  Scenario: I can create field groups using the Patterns plugin.

    Given I am logged in as a user with the "administrator" role
    And I visit "/admin/structure/types/manage/article"
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

    Then I should have the following options for "Pattern":
      | option     |
      | Carousel   |
      | Jumbotron  |
      | Modal      |
      | Metadata   |
      | Blockquote |
    And I should see the field "Title"
    And I should see the field "Body"

    When I select "Metadata" from "Pattern"
    And I wait "2" seconds
    Then I should not see the field "Title"
    Then I should not see the field "Body"
    But I should see the field "Author"
    And I should see the field "Publication date"
    And I should see the field "Categories"
