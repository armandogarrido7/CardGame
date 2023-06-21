import {deleteGame, joinGame} from "../../api/api";
import {useDispatch, useSelector} from "react-redux";
import {selectAccountID, setCurrentGameID, setPlaying} from "../../slices/accountSlice";
import {subscribeToGame} from "../../mercure/Mercure";
import {useNavigate} from "react-router-dom";
import {setPlayerID} from "../../slices/playersSlice";

function GameCard({game}) {
    const dispatch = useDispatch();
    const accountID = useSelector(selectAccountID);
    const gameID = game.game_id;
    const navigate = useNavigate();

    function accountJoinGame() {
        joinGame(accountID, gameID).then((response) => {
            console.log(response);
            dispatch(setPlaying(true));
            dispatch(setCurrentGameID(gameID));
            dispatch(setPlayerID(response.data.data.player_id));
            subscribeToGame(gameID);
            navigate("/game/" + gameID);
        }).catch((error) => {
            console.log(error);
        })
    }

    function hostDeleteGame() {
        deleteGame(gameID).then((response) => {
            console.log('Game deleted');
        })
    }

    return (<div className="card w-100 my-3 bg-black text-white" id={game.game_id}>
        <div className="card-body d-flex flex-column justify-content-center">
            <div className="d-flex justify-content-around">
                <h4 className="card-title text-center">{game.game_id} - {game.name}</h4>
                {
                    (game.status_name === 'waiting_players') ? (
                        <i className="position-absolute top-0 start-100 translate-middle fa-solid fa-circle fa-beat text-success fa-xl"></i>
                    ) : (
                        <i className="position-absolute top-0 start-100 translate-middle fa-solid fa-circle fa-beat text-danger fa-xl"></i>
                    )
                }
                {
                    (game.created_by === accountID) ? (
                        <i className="position-absolute top-0 start-0 translate-middle fa-solid fa-crown fa-2xl text-warning ms-1"></i>) : ''
                }
            </div>
            <h5 className="text-center">{game.players_number}/{game.max_players} players</h5>
            <div className="d-flex justify-content-around">{
                (game.players_number < game.max_players) ?
                    (<button className="btn btn-primary w-25" onClick={(e) => {
                        e.preventDefault();
                        accountJoinGame();
                    }} title="Press the button to Join Game">Join Game</button>) :
                    (<button className="btn btn-primary w-25" disabled>Game is full!</button>)
            }
                {
                    (game.created_by === accountID) ?
                        (<button className="btn btn-danger w-25" onClick={hostDeleteGame}>Delete Game</button>) : ''
                }
            </div>
        </div>
    </div>)
}

export default GameCard;
