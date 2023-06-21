import './App.css';
import AppHeader from "./features/header/Header";
import {BrowserRouter, Routes, Route} from "react-router-dom";
import Home from "./features/home/Home";
import Login from "./features/login/Login";
import Games from "./features/games/Games";
import Profile from "./features/profile/Profile";
import GameRoom from "./features/gameRoom/GameRoom";
import {useEffect} from "react";
import {useDispatch} from "react-redux";
import {
    setAccountID,
    setAPIToken,
    setEmail,
    setGamesPlayed,
    setLogged,
    setProfilePic,
    setName,
    setWins, setCurrentGameID, setPlaying
} from "./slices/accountSlice";
import {subscribeToAccountUpdates, subscribeToGame, subscribeToGameList} from "./mercure/Mercure";
import {getAccountData} from "./api/api";
import {setPlayerID} from './slices/playersSlice';

function App() {
    const dispatch = useDispatch();

    useEffect(() => {
        function updateAccountData(account_data) {
            dispatch(setLogged(true));
            dispatch(setPlayerID(account_data.player_id))
            dispatch(setCurrentGameID(account_data.current_game));
            dispatch(setEmail(account_data.email));
            dispatch(setName(account_data.name));
            dispatch(setGamesPlayed(account_data.games_played));
            dispatch(setPlaying(account_data.playing));
            dispatch(setProfilePic(account_data.profile_pic));
            dispatch(setWins(account_data.wins));
        }

        const account_data = JSON.parse(localStorage.getItem('account'));
        if (account_data) {
            dispatch(setAPIToken(account_data.API_token));
            dispatch(setAccountID(account_data.account_id));
            getAccountData(account_data.account_id).then((response) => {
                console.log('Automatic Login');
                updateAccountData(response.data.data);
                subscribeToAccountUpdates(account_data.account_id);
                subscribeToGameList();
                if (response.data.data.current_game) {
                    subscribeToGame(response.data.data.current_game);
                }
            }).catch((error) => {
                console.log(error);
            })
        }
    }, [dispatch]);

    return (
        <div className="App">
            <BrowserRouter>
                <AppHeader/>
                {/*<Loading/>*/}
                <Routes>
                    <Route path="" element={<Home/>}></Route>
                    <Route path="/login" element={<Login/>}></Route>
                    <Route path="/profile" element={<Profile/>}></Route>
                    <Route path="/games" element={<Games/>}></Route>
                    <Route path="/game/:game_id" element={<GameRoom/>}></Route>
                </Routes>
            </BrowserRouter>
        </div>
    );
}

export default App;
