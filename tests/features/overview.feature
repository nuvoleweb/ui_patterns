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
