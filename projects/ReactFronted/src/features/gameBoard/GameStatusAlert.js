import {useState} from "react";
import {useSelector} from "react-redux";
import {selectCurrentGame} from "../../slices/gamesSlice";
import {selectPlayerID} from "../../slices/playersSlice";

function GameStatusAlert(){
    const currentGame = useSelector(selectCurrentGame);
    const playerID = useSelector(selectPlayerID);
    return (
        <>
            {
                (currentGame.turn_type === 'prediction' && currentGame.current_player !== playerID) ?
                    (<div className="alert alert-warning text-center mt-2">
                        <h5>The other players are making their predictions</h5>
                        <i className="fa-solid fa-spinner fa-spin-pulse text-black fa-xl"></i>
                    </div>) : ''
            }
            {
                (currentGame.turn_type === 'play' && currentGame.current_player === playerID) ?
                    (<div className="alert alert-success text-center mt-2">
                        <h5>Click on the card you want to play!</h5>
                    </div>) : ''
            }
            {
                (currentGame.turn_type === 'play' && currentGame.current_player !== playerID) ?
                    (<div className="alert alert-info text-center mt-2">
                        <h5>The other players are making their moves</h5>
                        <i className="fa-solid fa-spinner fa-spin-pulse text-black fa-xl"></i>
                    </div>) : ''
            }
        </>
    );
}

export default GameStatusAlert;
