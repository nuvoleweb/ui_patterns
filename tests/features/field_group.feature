@api
Feature: Field group module integration
  In order to be able tu use patterns as field groups templates
  As a developer
  I want to be able to make sure the ui_patterns_field_group module works properly.

  @javascript
  Scenario: I can create field groups using the Patterns plugin.
    Given I am logged in as a user with the "administrator" role

    When I visit "/admin/structure/types/manage/article"
    And I click "Manage display"
    Then I should see the link "Add group"

    When I click "Add group"
    Then I should have the following options for "Add a new group":
      | option  |
      | Pattern |

    When I select "Pattern" from "Add a new group"
    And I wait "2" seconds
    And I fill in "Label" with "My field group"
    And I wait "2" seconds
    And I press the "Save and continue" button
    Then I should see "Attention: you have to add fields to this field group and save the whole entity display before being able to to access the pattern display configuration."

    When I click "Create group"
    Then I should see "New group My field group successfully created."

  @javascript @restoreContentTypes
  Scenario: I can create a content type and a content using the Patterns plugin.
    Given I am logged in as a user with the "administrator" role

    # Create a content type
    When I visit "/admin/structure/types"
    And I click "Add content type"
    And I fill in "Name" with "Content type test"
    And I wait "2" seconds
    And I press the "Save and manage fields" button
    Then I should see "The content type Content type test has been added."
    # Add a button field to the content type
    When I click "Add field"
    And I select "Link" from "Add a new field"
    And I wait "2" seconds
    And I fill in "Label" with "My Primary button"
    And I wait "2" seconds
    And I press the "Save and continue" button
    Then I should see "These settings apply to the My Primary Button field everywhere it is used. These settings impact the way that data is stored in the database and cannot be changed once data has been created."

    When I press the "Save field settings" button
    Then I should see "Updated field My Primary Button field settings."

    # Configure the layout of the content type
    When I visit "/admin/structure/types/manage/content_type_test/display"
    And I select "One column layout" from "Select a layout"
    And I wait "2" seconds
    And I press the "Save" button
    Then I should see "Your settings have been saved."

    # Configure the button of the content type
    When I visit "/admin/structure/types/manage/content_type_test/display"
    And I press "field_my_primary_button_plugin_settings_edit"
    And I wait "2" seconds
    And I select "Pattern" from "Choose a Field Template"
    And I wait "2" seconds
    And I select "Button" from "patterns-select"
    And I wait "2" seconds
    And I select "Primary" from "Variant"
    And I select "Label" from "fields[field_my_primary_button][settings_edit_form][third_party_settings][ds][ft][settings][pattern_mapping][button][settings][ds_field_template:field_my_primary_button__title][destination]"
    And I select "URL" from "fields[field_my_primary_button][settings_edit_form][third_party_settings][ds][ft][settings][pattern_mapping][button][settings][ds_field_template:field_my_primary_button__uri][destination]"
    And I press the "Update" button
    And I wait "2" seconds
    And I press the "Save" button
    Then I should see "Your settings have been saved."

    # Create a content
    When I visit "/node/add/content_type_test"
    And I fill in "Title" with "My test content"
    And I fill in "URL" with "<front>"
    And I fill in "Link text" with "Go Home"
    And I press the "Save" button
    Then I should see "Content type test My test content has been created."
    And I should see "Running: ui_patterns_test_theme_preprocess_pattern_button"
    And the ".btn.btn-primary" element should contain "Go home"
