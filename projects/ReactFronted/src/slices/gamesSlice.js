import {createSlice} from "@reduxjs/toolkit";

export const gamesSlice = createSlice({
    name: 'games',
    initialState: {
        value: {
            gameList: [],
            selectedGame: '',
            currentGame: {},
            APIError: false,
            newGameName: '',
            newGamePlayersNum: '',
            showNewGameModal: false,
            showFinishedGameModal: false
        }
    },
    reducers: {
        setGames: (state, games) => {
            // Redux Toolkit allows us to write "mutating" logic in reducers. It
            // doesn't actually mutate the state because it uses the Immer library,
            // which detects changes to a "draft state" and produces a brand new
            // immutable state based off those changes
            state.value.gameList = games.payload;
        },
        setSelectedGame: (state, selected_game) => {
            state.value.selectedGame = selected_game.payload;
        },
        setCurrentGame: (state, game) => {
            state.value.currentGame = game.payload;
        },
        setAPIError: (state, APIError) => {
            state.value.APIError = APIError.payload;
        },
        setNewGameName: (state, name) => {
            state.value.newGameName = name.payload;
        },
        setNewGameNamePlayersNum: (state, players_num) => {
            state.value.newGamePlayersNum = players_num.payload;
        },
        setShowNewGameModal: (state, show) => {
            state.value.showNewGameModal = show.payload;
        },
        setShowFinishedGameModal: (state, show) => {
            state.value.showFinishedGameModal = show.payload;
        }
    }
})

// Action creators are generated for each case reducer function
export const {
    setGames,
    setSelectedGame,
    setCurrentGame,
    setAPIError,
    setNewGameName,
    setNewGameNamePlayersNum,
    setShowNewGameModal,
    setShowFinishedGameModal
} = gamesSlice.actions

export const selectGamesList = (state) => state.games.value.gameList;
export const selectSelectedGame = (state) => state.games.value.selectedGame;
export const selectCurrentGame = (state) => state.games.value.currentGame;
export const selectAPIError = (state) => state.games.value.APIError;
export const selectNewGameName = (state) => state.games.value.newGameName;
export const selectNewGamePlayersNum = (state) => state.games.value.newGamePlayersNum;
export const selectShowNewGameModal = (state) => state.games.value.showNewGameModal;
export const selectFinishedGameModal = (state) => state.games.value.showFinishedGameModal;
export default gamesSlice.reducer
