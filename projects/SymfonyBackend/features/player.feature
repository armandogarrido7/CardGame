Feature: Player related functionalities

  Scenario: An account want to join existing Game
    Given There is an Account with params
      | account_id | email         | name | profile_pic |
      | 1          | acc1@test.com | acc1 | acc1_img    |
    Given There is an Account with params
      | account_id | email         | name | profile_pic |
      | 2          | acc2@test.com | acc2 | acc2_img    |
    When I request "/game/new" with method "POST" and parameters
      | name | players_num | account_id |
      | Test | 2           | 1          |
    When I request "/player/new" with method "POST" and parameters
      | game_id | account_id |
      | 1       | 2          |
    Then the response code should be "201"
    And the JSON response body should contain
    """
    {"player_id":2,"account":{"data":{"account_id":2,"player_id":2,"email":"acc2@test.com","name":"acc2","profile_pic":"acc2_img"}
    """
