Feature: Manage Accounts

  Scenario: Create a new Account
    When I request "/account/new" with method "POST" and parameters
      | email           | name       | profile_pic                |
      | player@test.com | PlayerTest | http://www.imgur.com/image |
    Then the response code should be "201"
    And the response body should have the following values
    """
    {
      "account_id" : 1,
      "email" : "player@test.com",
      "name" : "PlayerTest",
      "profile_pic" : "http://www.imgur.com/image"
    }
    """
