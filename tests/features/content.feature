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

  Scenario: Patterns can be used to style field groups.
    Given I am visiting the "article" content "Article title"
    And I should see "Tag 1" in the "Categories" row

  Scenario: Patterns can be used to style paragraphs.
    Given I am visiting the "article" content "Article title"
    Then I should see "Jumbotron title" in the "jumbotron"
    And I should see "Jumbotron subtitle" in the "jumbotron"

  Scenario: Patterns can be used as Display Suite field templates.
    Given I am visiting the "article" content "Article title"
    Then I should see "Article body" in the "quote"
    And I should see the link "My first button"
    And I should see the link "My second button"

  Scenario: Patterns can be used to style views.
    Given I am on "/articles"
    Then I should see the link "Article title" in the "media_heading"
    And I should see "Article body" in the "media_text"

  Scenario: Developers can modify patterns output in specific context thanks to pattern suggestions.

    Given I am visiting the "article" content "Article title"
    Then I should see "Running: ui_patterns_test_theme_preprocess_blockquote_custom__ds_field_template__body__node__article__full"
    And I should see "Running: ui_patterns_test_theme_preprocess_blockquote_custom__ds_field_template__body__node__article"
    And I should see "Running: ui_patterns_test_theme_preprocess_blockquote_custom__ds_field_template__body__node"
    And I should see "Running: ui_patterns_test_theme_preprocess_blockquote_custom__ds_field_template__body"
    And I should see "Running: ui_patterns_test_theme_preprocess_blockquote_custom"
    And I should see "Running: ui_patterns_test_theme_preprocess_pattern_metadata"
    And I should see "Running: ui_patterns_test_theme_preprocess_pattern_metadata__field_group__group_metadata__node__article__full"
    And I should see "Running: ui_patterns_test_theme_preprocess_pattern_metadata__field_group__group_metadata__node__article"
    And I should see "Running: ui_patterns_test_theme_preprocess_pattern_metadata__field_group__group_metadata__node"
    And I should see "Running: ui_patterns_test_theme_preprocess_pattern_metadata__field_group__group_metadata"
    And I should see "Running: ui_patterns_test_theme_preprocess_pattern_button"
    And I should see "Running: ui_patterns_test_theme_preprocess_pattern_button__ds_field_template__field_links__node__article__full"
    And I should see "Running: ui_patterns_test_theme_preprocess_pattern_button__ds_field_template__field_links__node__article"
    And I should see "Running: ui_patterns_test_theme_preprocess_pattern_button__ds_field_template__field_links__node"
    And I should see "Running: ui_patterns_test_theme_preprocess_pattern_button__ds_field_template__field_links"
    And I should see "Running: ui_patterns_test_theme_preprocess_patterns_destination__field_group__group_metadata__node__article__full__metadata__date"

    Given I am on "/articles"
    Then I should see "Running: ui_patterns_test_theme_preprocess_pattern_media__views_row__articles__page_1"
    And I should see "Running: ui_patterns_test_theme_preprocess_pattern_media__views_row__articles"
    And I should see "Running: ui_patterns_test_theme_preprocess_pattern_media"

  Scenario: Developers can render patterns by using Twig functions.
    Given I am on "/patterns-test"
    Then I should see "Here is your modal title" in the "modal"
    And I should see the button "Save changes" in the "modal"
    And I should see the link "Link title"

