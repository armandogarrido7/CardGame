import {useEffect, useState} from "react";
import {gameList} from "../../api/api";
import {selectShowNewGameModal, setGames, setShowNewGameModal} from "../../slices/gamesSlice";
import {useSelector, useDispatch} from 'react-redux';
import {
    selectCurrentGameID,
    selectLogged
} from "../../slices/accountSlice";
import {useNavigate} from "react-router-dom";
import GameList from "../gameList/GameList";
import {Button} from 'react-bootstrap';
import NewGameModal from '../modals/newGameModal';
function Games() {
    const currentGameID = useSelector(selectCurrentGameID);
    const logged = useSelector(selectLogged);
    const dispatch = useDispatch();
    const [hasLoaded, setHasLoaded] = useState(false);
    const navigate = useNavigate();
    const showNewGameModal = useSelector(selectShowNewGameModal);

    useEffect(() => {
        if (logged) {
            getDBGames();
            if (currentGameID) {
                navigate('/game/' + currentGameID);
            }
        }
    }, [dispatch]);

    function getDBGames() {
        gameList()
            .then((response) => {
                dispatch(setGames(response.data.data));
                setHasLoaded(true);
            })
            .catch((error) => {
                console.log(error);
            });
    }


    if (!logged) {
        return (<h1 className='alert alert-warning text-center w-50 m-auto mt-5'>You must login in order to play!</h1>)
    } else {
        return (
            <div className="container d-flex flex-column justify-content-center">
                <h2 className="text-center m-4">~ New Game ~</h2>
                <Button variant="success" className="w-50 mx-auto mb-5" onClick={() => {dispatch(setShowNewGameModal(true))}}>
                    Create New Game
                </Button>
                <NewGameModal/>
                <GameList/>
            </div>
        );
    }
}

export default Games;
