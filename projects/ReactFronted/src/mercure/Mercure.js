import * as Topics from "./MercureTopics.js";
import store from "../store/store";
import {setCurrentGame, setGames} from "../slices/gamesSlice";
import {setCurrentGameID} from "../slices/accountSlice";
import {setOthersPlayersData, setOwnPlayerData} from "../slices/playersSlice";

function arrayRotate(arr, reverse) {
    if (reverse) arr.unshift(arr.pop());
    else arr.push(arr.shift());
    return arr;
}

export function subscribeToGameList(){
    const eventSource = new EventSource(Topics.gameListTopic);
    eventSource.onmessage = (event) => {
        store.dispatch(setGames(JSON.parse(event.data).data));
        console.log('Game list updated');
    }
    console.log('Subscribe To Topic:' + Topics.gameListTopic);
}

export function subscribeToGame(game_id){
    const eventSource = new EventSource(Topics.gameUpdatesTopic(game_id));
    eventSource.onmessage = (event) => {
        let game = JSON.parse(event.data).data;
        store.dispatch(setCurrentGame(game));
        let me = store.getState().games.value.currentGame.players.data.filter(p => p.account.data.account_id === store.getState().account.value.accountID)[0];
        let players_array = game.players.data.slice();
        console.log(players_array);
        while (players_array[0] !== me){
            arrayRotate(players_array, false);
        }
        console.log(players_array);
        store.dispatch(setOthersPlayersData(players_array.filter(p => p.account.data.account_id !== store.getState().account.value.accountID)));
        store.dispatch(setOwnPlayerData(players_array.filter(p => p.account.data.account_id === store.getState().account.value.accountID)[0]));
        console.log('Game ' + game_id + ' updated');
    }
    console.log('Subscribe To Topic:' + Topics.gameUpdatesTopic(game_id));
}

export function subscribeToAccountUpdates(account_id){
    const eventSource = new EventSource(Topics.accountUpdatesTopic(account_id));
    eventSource.onmessage = (event) => {
        store.dispatch(setCurrentGame(null));
        store.dispatch(setCurrentGameID(null));
        console.log('Account ' + account_id + ' updated');
    }
    console.log('Subscribe To Topic:' + Topics.accountUpdatesTopic(account_id));
}
