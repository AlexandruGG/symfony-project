Feature:
  In order to prove that the Behat Symfony extension is correctly installed
  As a user
  I want to have a demo scenario

  Scenario: I see the page
    Given I go to "/agents/new"
    Then I should see "Add Agent Below" in the "h1" element


  Scenario: I can submit the form
    Given I am on "/agents/new"
    When I fill in "Name" with "BehatAgent"
    And I select "Chestertons" from "Company"
    And I press "Add Agent"
    Then I should see "Agent Submitted!"
#    And an agent should exist on the company page with name "BehatAgent"
