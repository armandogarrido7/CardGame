import {createSlice} from "@reduxjs/toolkit";

export const playersSlice = createSlice({
    name: 'players',
    initialState: {
        value: {
            ownPlayerData: {},
            othersPlayersData: [],
            playerID: null
        }
    },
    reducers: {
        setPlayerID: (state, player_id) => {
            // Redux Toolkit allows us to write "mutating" logic in reducers. It
            // doesn't actually mutate the state because it uses the Immer library,
            // which detects changes to a "draft state" and produces a brand new
            // immutable state based off those changes
            state.value.playerID = player_id.payload;
        },
        setOwnPlayerData: (state, ownPlayerData) => {
            state.value.ownPlayerData = ownPlayerData.payload;
        },
        setOthersPlayersData: (state, othersPlayersData) => {
            state.value.othersPlayersData = othersPlayersData.payload;
        }
    }
})

// Action creators are generated for each case reducer function
export const { setPlayerID, setOwnPlayerData, setOthersPlayersData } = playersSlice.actions;

export const selectPlayerID = (state) => state.players.value.playerID;
export const selectOwnPlayerData = (state) => state.players.value.ownPlayerData;
export const selectOthersPlayerData = (state) => state.players.value.othersPlayersData;

export default playersSlice.reducer
