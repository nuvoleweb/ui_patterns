@api
Feature: Overview
  In order to have an overview of all available patterns
  As a developer
  I want to be able to access a patters overview page.

  Scenario: Patterns overview page is only accessible to users with proper permissions.

    Given I am logged in as a user with the "access patterns page" permission
    And I am on "/patterns"
    Then I should get a "200" HTTP response

    When I am logged in as a user with the "authenticated" role
    And I am on "/patterns"
    Then I should get a "403" HTTP response

  Scenario: Patterns overview page displays all available patterns.

    Given I am logged in as a user with the "access patterns page" permission
    And I am on "/patterns"

    Then I should see the heading "Jumbotron"
    And I should see "A lightweight, flexible component that can optionally extend the entire viewport to showcase key content on your site."
    And I should see "Hello, world!" in the "jumbotron"
    And I should see "This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information." in the "jumbotron"
    And I should see "Learn more" in the "jumbotron"

    Then I should see the heading "Blockquote"
    And I should see "Life is like riding a bicycle."
    And I should see "Albert Einstein (overridden in theme)"

    And I click "View Jumbotron"

    Then I should see the heading "Jumbotron"
    And I should see "A lightweight, flexible component that can optionally extend the entire viewport to showcase key content on your site."
    And I should see "Hello, world!" in the "jumbotron"
    And I should see "This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information." in the "jumbotron"
    And I should see "Learn more" in the "jumbotron"

    But I should not see the heading "Blockquote"
    And I should not see "Life is like riding a bicycle."
    And I should not see "Albert Einstein (overridden in theme)"

  Scenario: Pattern template can be customized by setting the 'template' definition property.

    Given I am logged in as a user with the "access patterns page" permission
    And I am on "/patterns"

    Then I should see the heading "Metadata"
    And I should see "Display a content metadata consisting of categories, publication date and author."
    And I should see "Author of the content item." in the "Author" row
    And I should see "The date the content item was published." in the "Publication date" row
    And I should see "Categories the content item is tagged with." in the "Categories" row

  Scenario: Pattern template can be customized by setting the 'use' definition property.

    Given I am logged in as a user with the "access patterns page" permission
    And I am on "/patterns"

    Then I should see the heading "Modal"
    And I should see "Here is your modal title"

  @disableCompression
  Scenario: Libraries defined in the pattern definition should be loaded correctly.

    Given I am logged in as a user with the "access patterns page" permission
    And I am on "/patterns/media"
    Then the response should contain "/ui_patterns_test_theme/templates/patterns/media/css/media1.css"
    And the response should contain "/ui_patterns_test_theme/templates/patterns/media/css/media2.css"
    And the response should contain "/ui_patterns_test_theme/templates/patterns/media/js/media1.js"
    And the response should contain "/ui_patterns_test_theme/templates/patterns/media/js/media2.js"
    And the response should contain "/misc/tabledrag.js"

  Scenario: Patterns overview page will show validation errors after clearing the cache.
    Given I am logged in as a user with the "access patterns page" permission
    And the cache has been cleared

    When I am on "/patterns"
    Then I should see the following error messages:
      | error messages                                                                 |
      | Pattern 'bad_pattern' is skipped because of the following validation error(s): |
      | Validation error on "bad_pattern.label": This value should not be null.        |
      | Validation error on "bad_pattern.fields": This value should not be null.       |

    And I reload the page
    Then I should not see the following error messages:
      | error messages                                                                 |
      | Pattern 'bad_pattern' is skipped because of the following validation error(s): |
      | Validation error on "bad_pattern.label": This value should not be null.        |
      | Validation error on "bad_pattern.fields": This value should not be null.       |
