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

    Then I should see the heading "Pattern 1"
    And I should see "Title example for Pattern 1" in the "Title 1" row
    And I should see "Subtitle example for Pattern 1" in the "Subtitle 1" row

    Then I should see the heading "Pattern 2"
    And I should see "Title example for Pattern 2" in the "Title 2" row
    And I should see "Subtitle example for Pattern 2" in the "Subtitle 2" row

    Then I should see the heading "Custom theme pattern"
    And I should see "This template is defined only in the theme and not provided by the declaring module."

    And I click "View Pattern 1"

    Then I should see the heading "Pattern 1"
    And I should see "Title example for Pattern 1" in the "Title 1" row
    And I should see "Subtitle example for Pattern 1" in the "Subtitle 1" row

    But I should not see the heading "Pattern 2"
    And I should not see "Title example for Pattern 2"
    And I should not see "Subtitle example for Pattern 2"
