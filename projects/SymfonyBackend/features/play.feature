Feature: A player can make plays

  Background:
    Given There is a Card with params
      | card_id | number | suit  |
      | 1       | 1      | golds |
    And There is an Account with params
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
    And I request "/game/prediction/new" with method "POST" and parameters
      | player_id | prediction |
      | 1         | 3          |
    And I request "/game/prediction/new" with method "POST" and parameters
      | player_id | prediction |
      | 2         | 1          |


  Scenario: Make a new play
    When I request "/game/play/new" with method "POST" and parameters
      | player_id | card_id |
      | 1         | 1       |
    Then the response code should be "201"
    And the JSON response body should contain
    """
    "plays":{"data":[{"play_id":1,"card":{"data":{"id":1,"number":1,"suit":"golds"}},"card_flipped":true}]}}
    """
