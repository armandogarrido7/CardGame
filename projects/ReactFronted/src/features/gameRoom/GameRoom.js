import {useNavigate} from "react-router-dom";
import {useEffect, useState} from "react";
import {useDispatch, useSelector} from "react-redux";
import {setCurrentGame, selectCurrentGame} from "../../slices/gamesSlice";
import {gameState, removePlayerFromGame, startGame} from "../../api/api";
import {
    selectAccountID,
    selectCurrentGameID,
    setCurrentGameID,
    setPlaying
} from "../../slices/accountSlice";
import {setPlayerID, selectPlayerID} from "../../slices/playersSlice";
import GameBoard from "../gameBoard/GameBoard";

function GameRoom() {
    const playerID = useSelector(selectPlayerID);
    const accountID = useSelector(selectAccountID);
    const gameID = useSelector(selectCurrentGameID);
    const game = useSelector(selectCurrentGame);
    const dispatch = useDispatch();
    const [APIError, setAPIError] = useState(false);
    const [hasLoaded, setHasLoaded] = useState(false);
    const navigate = useNavigate();

    useEffect(() => {
        getDBGame(gameID);
    }, [gameID]);

    function getDBGame(game_id) {
        gameState(game_id)
            .then((response) => {
                dispatch(setCurrentGame(response.data.data));
                setHasLoaded(true);
                setAPIError(false);
            })
            .catch((error) => {
                setAPIError(true);
                setHasLoaded(true);
            });
    }

    function leaveGame() {
        removePlayerFromGame(accountID, gameID).then((response) => {
            dispatch(setCurrentGameID(null));
            dispatch(setPlaying(false));
            dispatch(setPlayerID(null));
            navigate('/games');
        }).catch((error) => {
            console.log(error);
        });
    }

    function pressStartGame() {
        setHasLoaded(false);
        startGame(gameID).then((response) => {
            setHasLoaded(true);
        }).catch((error) => {
            console.log(error);
        })
    }
    if (hasLoaded) {
        return (
            <>
                {
                    APIError ?
                        (
                            <div className="d-flex w-100 flex-column align-items-center my-5">
                                <h2 className="text-center alert alert-danger w-50 m-auto">Error fetching Game
                                information!</h2>
                                <button className="btn btn-primary my-3" onClick={() => navigate('/games')}>Back to Game
                                    List
                                </button>
                            </div>
                        ) : (
                            (game) ?
                                (
                                    (game.status_name === 'waiting_players' || game.status_name === 'waiting_host') ? (
                                    <div
                                        className="bg-black text-white d-flex flex-column align-items-center w-50 m-auto p-3 rounded-2 my-5">
                                        <h2 className="text-center"> Game {game.name} - {game.game_id}</h2>
                                        <h4>Players ({game.players_number} / {game.max_players}):</h4>
                                        {
                                            (game.players) ?
                                                (game.players.data.map((player) => {
                                                    return (
                                                        <div key={player.player_id} className="h5">
                                                            <img src={player.account.data.profile_pic}
                                                                 className="me-2 w-25 rounded rounded-circle"/>
                                                            {player.account.data.name}
                                                            {
                                                                (player.account.data.account_id === game.created_by) ? (
                                                                    <i className="fa-solid fa-crown text-warning ms-1"></i>
                                                                ) : ''
                                                            }
                                                        </div>)
                                                })) : ''
                                        }
                                        <div className="alert alert-info">
                                            Game Status: {game.status_description}
                                        </div>
                                        <div className="d-flex justify-content-around w-100 m-2">
                                            {
                                                (game.created_by === accountID && game.status_name === 'waiting_players') ? (
                                                    <button className="btn btn-success disabled" disabled>Start
                                                        Game</button>
                                                ) : ''
                                            }
                                            {
                                                (game.created_by === accountID && game.status_name === 'waiting_host') ? (
                                                    <button className="btn btn-success" onClick={pressStartGame}>Start Game</button>
                                                ) : ''
                                            }
                                            <button className="btn btn-danger" onClick={leaveGame}>
                                                Leave Game
                                            </button>
                                        </div>

                                    </div>
                                    ) : (
                                        <div className="w-100 gx-0 mx-0 fill-y">
                                            <GameBoard></GameBoard>
                                        </div>
                                    )
                                ) : (
                                    <div className="d-flex flex-column align-items-center w-100 my-5">
                                        <h2 className="alert alert-danger text-center w-50">Game info not found</h2>
                                        <button className="btn btn-primary my-3" onClick={() => navigate('/games')}>Back to Game
                                            List
                                        </button>
                                    </div>
                                )
                        )
                }
            </>
        );
    }
}

export default GameRoom;
