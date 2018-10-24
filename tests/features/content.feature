@api
Feature: Content
  In order to display my content using patterns
  As a developer
  I want to be able to integrate patterns with other Drupal rendering systems.

  Background:
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
      field_links:
        -
          uri: http://example.com
          title: My first button
        -
          uri: http://example.com
          title: My second button
      """

  Scenario: Patterns can be used to style paragraphs.
    Given I am visiting the "article" content "Article title"
    Then I should see "Jumbotron title" in the "jumbotron"
    And I should see "Jumbotron subtitle" in the "jumbotron"

  Scenario: Patterns can be used to style views.
    Given I am on "/articles"
    Then I should see the link "Article title" in the "media_heading"
    And I should see "Article body" in the "media_text"

  Scenario: Developers can render patterns by using Twig functions.
    Given I am on "/patterns-test"
    Then I should see "Here is your modal title" in the "modal"
    And I should see the button "Save changes" in the "modal"
    And I should see the link "Link title"

  Scenario: Patterns render as Panels layouts.
    Given I am on "/panels-test-page"
    Then I should see "Here is your jumbotron title" in the "jumbotron"
