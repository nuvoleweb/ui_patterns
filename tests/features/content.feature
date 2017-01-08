@api
Feature: Content
  In order to have use patterns to theme my content
  As a developer
  I want to be able to use patterns on field groups and view modes.

  Scenario: Patterns overview page is only accessible to users with proper permissions.

    Given "tags" terms:
      | name  |
      | Tag 1 |

    And the following content:
      """
      title: Article title
      type: article
      langcode: en
      field_tags: Tag 1
      body: Article body

      field_paragraphs:
        -
          type: jumbotron
          field_title: Jumbotron title
          field_subtitle: Jumbotron subtitle
      """
    And I am visiting the "article" content "Article title"

    Then I should see "Article title"
    And I should see "Article body"
    And I should see "Jumbotron title"
    And I should see "Jumbotron subtitle"
    And I should see "Tag 1" in the "Categories" row
