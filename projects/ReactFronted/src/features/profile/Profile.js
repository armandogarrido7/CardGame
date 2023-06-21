import {
    selectEmail,
    selectProfilePicURL,
    selectName,
    selectWins,
    selectGamesPlayed,
    setEmail,
    setLogged,
    setAPIToken,
    setAccountID,
    setName,
    setProfilePic,
    setGamesPlayed,
    setWins,
    setGoogleToken
} from "../../slices/accountSlice";
import {useDispatch, useSelector} from "react-redux";
import {useNavigate} from "react-router-dom";

function Profile() {
    const name = useSelector(selectName);
    const email = useSelector(selectEmail);
    const profilePicURL = useSelector(selectProfilePicURL);
    const gamesPlayed = useSelector(selectGamesPlayed);
    const wins = useSelector(selectWins);
    const navigate = useNavigate();
    const dispatch = useDispatch();
    function signOut(){
        localStorage.removeItem('account');
        dispatch(setAPIToken(''));
        dispatch(setGoogleToken(''));
        dispatch(setAccountID(''));
        dispatch(setEmail(''));
        dispatch(setName(''));
        dispatch(setProfilePic(''));
        dispatch(setGamesPlayed(''));
        dispatch(setWins(''));
        dispatch(setLogged(false));
       navigate('/');
    }

    return (
        <div className="d-flex flex-column align-items-center w-100 p-3">
            <h2 className="text-center">Welcome back {name}!</h2>
            <img src={profilePicURL} className="w-25 rounded-circle mb-3" alt="profile_pic"></img>
            <h4>Email: {email}</h4>
            <h4>Games Played: {gamesPlayed}</h4>
            <h4>Wins: {wins}</h4>
            <button className="btn btn-info w-25" onClick={signOut}>Sign Out</button>
        </div>
    );
}

export default Profile;
