@api
Feature: Configuration
  In order to be able to use patterns on my site
  As a developer
  I want to be able to work with patterns-related configuration.

  @javascript @preserveConfiguration
  Scenario: I can create field groups using the Patterns plugin.

    Given I view the site on a "desktop" device
    And I am logged in as a user with the "administrator" role
    And I visit "/admin/structure/types/manage/page/display/full"
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

    And I move the "Author" row under the "My field group" row
    And I move the "Title" row under the "My field group" row
    And I move the "Post date" row under the "My field group" row
    And I press "Save"

    Then the configuration item "core.entity_view_display.node.article.full" should contain:
    """
    third_party_settings:
      field_group:
        group_metadata:
          format_type: pattern_formatter
          format_settings:
            label: Metadata
            pattern: metadata
            pattern_mapping:
              'fields:field_tags':
                destination: categories
                weight: 0
                plugin: fields
                source: field_tags
              'ds_field:node_author':
                destination: author
                weight: 1
                plugin: ds_field
                source: node_author
              'ds_field:node_post_date':
                destination: date
                weight: 2
                plugin: ds_field
                source: node_post_date
    """
