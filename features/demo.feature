Feature: Add Agent Page Works As Intended

  Scenario: I am redirected to login if not logged in
    Given I go to "/admin/agent/new"
    Then I should be on "/login"

  Scenario: I can log in
    Given I go to "/login"
    When I fill in "email" with "admin@goodlord.co"
    When I fill in "password" with "goodpasslord"
    When I press "Sign in"
    Then I should see "Hello Admin" in the "h1" element

#  Scenario: I can submit the form
#    Given I am on "admin/agents/new"
#    When I fill in "name" with "BehatAgent"
#    And I select "Chestertons" from "Company"
#    And I press "Add Agent"
#    Then I should see "Agent Submitted!"
#    And an agent should exist on the "Chestertons" company page with name "BehatAgent"
