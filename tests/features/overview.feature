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

    Then I should see the heading "Metadata"
    And I should see "Display a content metadata consisting of categories, publication date and author."
    And I should see "Author of the content item." in the "Author" row
    And I should see "The date the content item was published." in the "Publication date" row
    And I should see "Categories the content item is tagged with." in the "Categories" row

    Then I should see the heading "Blockquote"
    And I should see "Life is like riding a bicycle."
    And I should see "Albert Einstein (overridden in theme)"

    And I click "View Metadata"

    Then I should see the heading "Metadata"
    And I should see "Display a content metadata consisting of categories, publication date and author."
    And I should see "Author of the content item." in the "Author" row
    And I should see "The date the content item was published." in the "Publication date" row
    And I should see "Categories the content item is tagged with." in the "Categories" row

    But I should not see the heading "Blockquote"
    And I should not see "Life is like riding a bicycle."
    And I should not see "Albert Einstein (overridden in theme)"
