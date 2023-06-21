import {Modal} from "react-bootstrap";
import {useDispatch, useSelector} from "react-redux";
import {selectCurrentGame, selectFinishedGameModal, setCurrentGame} from "../../slices/gamesSlice";
import {selectPlayerID, setOthersPlayersData, setOwnPlayerData, setPlayerID} from "../../slices/playersSlice";
import {useNavigate} from "react-router-dom";
import {setCurrentGameID, setPlaying} from "../../slices/accountSlice";

function FinishedGameModal() {
    const showFinishedGameModal = useSelector(selectFinishedGameModal);
    const playerID = useSelector(selectPlayerID);
    const currentGame = useSelector(selectCurrentGame);
    const navigate = useNavigate();
    const dispatch = useDispatch();

    function finishGame() {
        dispatch(setCurrentGame({}));
        dispatch(setCurrentGameID(''));
        dispatch(setPlaying(false));
        dispatch(setOwnPlayerData({}));
        dispatch(setOthersPlayersData({}));
        dispatch(setPlayerID(''));
        navigate('/games');
    }

    return (
        <Modal show={currentGame.status_name === 'finished'} size="lg"
               aria-labelledby="contained-modal-title-vcenter-1" centered>
            <Modal.Header>
                <Modal.Title>Game is Over</Modal.Title>
            </Modal.Header>
            <Modal.Body>
                <div className="text-center d-flex flex-column align-items-center">
                    {
                        (currentGame.winner) ? ((currentGame.winner === playerID) ?
                            (<h3>Congratulations, you won this game! 🥳 </h3>) :
                            (<><h3>Sorry, you lose this game 😔</h3><h4>Good luck for the next one! 💪</h4></>)) : ''
                    }
                    <buttton className="btn btn-primary w-50 mt-2" onClick={finishGame}>
                        Go back to Game List
                    </buttton>
                </div>
            </Modal.Body>
        </Modal>
    )
}

export default FinishedGameModal;
