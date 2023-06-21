Feature: A player can make a prediction

  Background:
    Given There is an Account with params
      | account_id | email         | name | profile_pic |
      | 1          | acc1@test.com | acc1 | acc1_img    |
    And There is an Account with params
      | account_id | email         | name | profile_pic |
      | 2          | acc2@test.com | acc2 | acc2_img    |
    When I request "/game/new" with method "POST" and parameters
      | name | players_num | account_id |
      | Test | 2           | 1          |
    And I request "/player/new" with method "POST" and parameters
      | game_id | account_id |
      | 1       | 2          |
    And I request "/game/start" with method "POST" and parameters
      | game_id |
      | 1       |

  Scenario: Make a new prediction about how many rounds he will win
    When I request "/game/prediction/new" with method "POST" and parameters
      | player_id | prediction |
      | 1         | 3          |
    Then the response code should be "201"
    And the JSON response body should contain
    """
    "predictions":{"data":[{"id":1,"round_id":1,"rounds_won":3,"will_win":null}]}
    """

  Scenario: Make a new prediction about if he will win or not
    When I request "/game/prediction/new" with method "POST" and parameters
      | player_id | prediction |
      | 1         | true       |
    Then the response code should be "201"
    And the JSON response body should contain
    """
    "predictions":{"data":[{"id":1,"round_id":1,"rounds_won":null,"will_win":true}]}
    """
