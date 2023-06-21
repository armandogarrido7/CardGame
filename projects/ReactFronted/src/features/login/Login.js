import {GoogleLogin} from '@react-oauth/google';
import {googleLogin} from "../../api/api";
import {useDispatch, useSelector} from "react-redux";
import {
    setAccountID,
    setEmail,
    setName,
    setLogged,
    setProfilePic,
    setAPIToken,
    setGoogleToken,
    setGamesPlayed,
    setWins,
    setPlaying,
    setCurrentGameID,
    selectAccountID
} from "../../slices/accountSlice";
import {useState} from "react";
import jwtDecode from "jwt-decode";
import {subscribeToAccountUpdates, subscribeToGameList} from "../../mercure/Mercure";

function AppLogin() {
    const dispatch = useDispatch();
    const accountID = useSelector(selectAccountID);
    const [googleLoginError, setGoogleLoginError] = useState(false);
    const [googleLoginSuccess, setGoogleLoginSuccess] = useState(false);

    function updateAccountData(APIToken) {
        let account_data = jwtDecode(APIToken);
        dispatch(setLogged(true));
        dispatch(setAPIToken(APIToken));
        dispatch(setAccountID(account_data.account_id));
        dispatch(setEmail(account_data.email));
        dispatch(setName(account_data.name));
        dispatch(setProfilePic(account_data.profile_pic));
        dispatch(setGamesPlayed(account_data.games_played));
        dispatch(setWins(account_data.wins));
        dispatch(setPlaying(account_data.playing));
        dispatch(setCurrentGameID(account_data.current_game));
        localStorage.setItem('account', JSON.stringify({"account_id": account_data.account_id, "API_token": APIToken}));
    }
    const login = (googleToken) => {
        googleLogin(googleToken)
            .then((response) => {
                updateAccountData(response.data.token);
                subscribeToGameList();
                subscribeToAccountUpdates(accountID);
            });
    }

    return (
        <div className="w-100 d-flex flex-column justify-content-center container mt-5">
            <div className="w-50 m-auto d-flex justify-content-center">
                <GoogleLogin
                    onSuccess={credentialResponse => {
                        setGoogleLoginError(false);
                        setGoogleLoginSuccess(true);
                        dispatch(setGoogleToken(credentialResponse.credential));
                        login(credentialResponse.credential);
                    }}
                    onError={() => {
                        setGoogleLoginError(true);
                    }}
                />
            </div>
            { googleLoginError ?
                (<h2 className="alert alert-danger text-center w-50 m-auto mt-5">Google Login Error</h2>) :
                ''
            }
            { googleLoginSuccess ?
                (<h2 className="alert alert-success text-center w-50 m-auto mt-5">Successfully logged with Google!</h2>) :
                ''
            }
        </div>
    );
}

export default AppLogin;
