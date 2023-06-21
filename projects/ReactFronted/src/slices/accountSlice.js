import {createSlice} from "@reduxjs/toolkit";
let acc_data = JSON.parse(localStorage.getItem('account'));
export const accountSlice = createSlice({
    name: 'account',
    initialState: {
        value: acc_data || {
            logged: false,
            googleToken: '',
            APIToken: '',
            name: '',
            email: '',
            accountID: '',
            profilePicURL: '',
            gamesPlayed: 0,
            wins: 0,
            playing: false,
            currentGameID: 0
        }
    },
    reducers: {
        setLogged: (state, loggedStatus) => {
            // Redux Toolkit allows us to write "mutating" logic in reducers. It
            // doesn't actually mutate the state because it uses the Immer library,
            // which detects changes to a "draft state" and produces a brand new
            // immutable state based off those changes
            state.value.logged = loggedStatus.payload;
        },
        setGoogleToken:(state, googleToken) => {
          state.value.googleToken = googleToken.payload;
        },
        setAPIToken: (state, APIToken) => {
            state.value.APIToken = APIToken.payload;
        },
        setName: (state, name) => {
            state.value.name = name.payload;
        },
        setEmail: (state, email) => {
            state.value.email = email.payload;
        },
        setAccountID: (state, account_id) => {
            state.value.accountID = account_id.payload;
        },
        setProfilePic: (state, profile_pic_url) => {
            state.value.profilePicURL = profile_pic_url.payload;
        },
        setGamesPlayed: (state, games_played) => {
            state.value.gamesPlayed = games_played.payload;
        },
        setWins: (state, wins) => {
            state.value.wins = wins.payload;
        },
        setPlaying: (state, playing) => {
            state.value.playing  = playing.payload;
        },
        setCurrentGameID: (state, current_game) => {
            state.value.currentGameID = current_game.payload;
        }
    }
})

// Action creators are generated for each case reducer function
export const {
    setLogged,
    setGoogleToken,
    setAPIToken,
    setName,
    setEmail,
    setAccountID,
    setProfilePic,
    setGamesPlayed,
    setWins,
    setPlaying,
    setCurrentGameID
} = accountSlice.actions
export const selectLogged = state => state.account.value.logged;
export const selectAPIToken = state => state.account.value.APIToken;
export const selectName = state => state.account.value.name;
export const selectEmail = state => state.account.value.email;
export const selectAccountID = state => state.account.value.accountID;
export const selectProfilePicURL = state => state.account.value.profilePicURL;
export const selectGamesPlayed = state => state.account.value.gamesPlayed;
export const selectWins = state => state.account.value.wins;
export const selectPlaying = state => state.account.value.playing;
export const selectCurrentGameID = state => state.account.value.currentGameID;
export default accountSlice.reducer
