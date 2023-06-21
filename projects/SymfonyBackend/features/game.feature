Feature:
  A player should be able to create, join, and view all existing Games

  Background:
    When I request "/account/new" with method "POST" and parameters
      | email             | name         | profile_pic                |
      | account1@test.com | TestAccount1 | http://www.imgur.com/image |
    When I request "/account/new" with method "POST" and parameters
      | email             | name         | profile_pic                |
      | account2@test.com | TestAccount2 | http://www.imgur.com/image |

  Scenario: Player creates new Game
    When I request "/game/new" with method "POST" and parameters
      | name | players_num | account_id |
      | Test | 2           | 1          |
    Then the response code should be "201"
    And the response body should have the following values
    """
    {
      "game_id" : 1,
      "name" : "Test",
      "players_number" : 1,
      "max_players" : 2
    }
    """

  Scenario: Player want to see all existing Games
    When I request "/game/new" with method "POST" and parameters
      | name  | players_num | account_id |
      | Test1 | 2           | 1          |
    And I request "/game/new" with method "POST" and parameters
      | name  | players_num | account_id |
      | Test2 | 2           | 2          |
    And I request "/game/list" with method "GET"
    Then the response code should be "200"
    And the response body should contain
      """

          {
            "game_id" : 1,
            "name" : "Test1",
            "created_by": 1,
            "max_players": 2,
            "players_number": 1,
            "status_name": "waiting_players",
            "status_description": "Waiting for Players..."
          }

      """
    And the response body should contain
      """
          {
            "game_id" : 2,
            "name" : "Test2",
            "created_by": 2,
            "max_players": 2,
            "players_number": 1,
            "status_name": "waiting_players",
            "status_description": "Waiting for Players..."
          }

      """

  Scenario: Player want to know the state of the game
    Given There is a Game with params
      | game_id | account_id | name     | players_num |
      | 1       | 1          | GameTest | 2           |
    When I request "/game/state" with method "POST" and parameters
      | game_id |
      | 1       |
    Then the response code should be "200"
    And the response body should have the following values
    """
    {
      "game_id" : 1,
      "name" : "GameTest",
      "max_players" : 2,
      "players_number" : 0
    }
    """

  Scenario: Player delete a game
    Given There is a Game with params
      | game_id | account_id | name     | players_num |
      | 1       | 1          | GameTest | 2           |
    When I request "/game/delete" with method "POST" and parameters
      | game_id |
      | 1       |
    Then the response code should be "200"
    When I request "/game/list" with method "GET"
    And the response body should have the following values
    """
      []
    """
