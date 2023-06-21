export const mercureUrl = 'http://127.0.0.1:4000/.well-known/mercure?topic=https://card-game.com';
export const gameListTopic = mercureUrl + '/game/list';
export const gameUpdatesTopic = (game_id) => { return mercureUrl + '/game/state/' + game_id};
export const accountUpdatesTopic = (account_id) => { return mercureUrl + '/account/update/' + account_id};
