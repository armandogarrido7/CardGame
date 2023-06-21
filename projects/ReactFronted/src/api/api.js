import connector from './connector';

export const googleLogin = (token) => {
    return connector.post('login/google', {
        'token' : token
    })
}
export const getAccountData = (accountID) => {
    return connector.post('/account/get', {
        'account_id' : accountID
    })
}
export const gameList = () => {
    return connector.get('game/list');
}

export const newGame = (gameName, playersNum, accountID) => {
    return connector.post('game/new', {
        'name' : gameName,
        'players_num' : playersNum,
        'account_id' : accountID
    })
};

export const gameState = (gameID) => {
    return connector.post('/game/state', {
        'game_id' : gameID
    })
};

export const joinGame = (accountID, gameID) => {
    return connector.post('player/new', {
        'account_id' : accountID,
        'game_id' : gameID,
    })
};

export const removePlayerFromGame = (accountID, gameID) => {
    return connector.post('/player/remove', {
        'account_id' : accountID,
        'game_id' : gameID
    })
}

export const deleteGame = (gameID) => {
    return connector.post('/game/delete', {
        'game_id' : gameID
    })
}

export const startGame = (gameID) => {
    return connector.post('game/start', {
        'game_id' : gameID
    })
}

export const makePrediction = (prediction, playerID) => {
    return connector.post('game/prediction/new', {
        'prediction' : prediction,
        'player_id' : playerID,
    })
}

export const makePlay = (gameID, playerID, cardID) => {
    return connector.post('game/play/new', {
        'game_id' : gameID,
        'card_id' : cardID,
        'player_id' : playerID
    });
}


