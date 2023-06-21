import {newGame} from "../../api/api";
import {selectAccountID, setCurrentGameID, setPlaying} from "../../slices/accountSlice";
import {setPlayerID} from "../../slices/playersSlice";
import {subscribeToGame} from "../../mercure/Mercure";
import {useDispatch, useSelector} from "react-redux";
import {
    selectNewGameName,
    selectNewGamePlayersNum,
    selectShowNewGameModal,
    setNewGameName, setNewGameNamePlayersNum, setShowNewGameModal
} from "../../slices/gamesSlice";
import {useNavigate} from "react-router-dom";
import {Button, Modal} from "react-bootstrap";

function NewGameModal() {
    const navigate = useNavigate();
    const dispatch = useDispatch();
    const accountID = useSelector(selectAccountID);
    const gameName = useSelector(selectNewGameName);
    const playersNum = useSelector(selectNewGamePlayersNum);
    const showNewGameModal = useSelector(selectShowNewGameModal);

    function createNewGame() {
        newGame(gameName, parseInt(playersNum), accountID).then((response) => {
            dispatch(setPlaying(true));
            dispatch(setCurrentGameID(response.data.data.game_id));
            dispatch(setPlayerID(response.data.data.players.data[0].player_id));
            subscribeToGame(response.data.data.game_id);
            dispatch(setShowNewGameModal(false));
            dispatch(setNewGameName(''));
            dispatch(setNewGameNamePlayersNum(''));
            navigate('/game/' + response.data.data.game_id);
        }).catch((error) => {
            console.log(error)
        })
    }

    return (
        <Modal show={showNewGameModal} size="lg"
               aria-labelledby="contained-modal-title-vcenter-1" centered>
            <Modal.Header>
                <Modal.Title>Create New Game</Modal.Title>
            </Modal.Header>
            <Modal.Body>
                <form>
                    <label className="d-flex flex-column mb-3">
                        Game Name
                        <input type="text" value={gameName} onChange={(e) => {
                            dispatch(setNewGameName(e.target.value))
                        }} className="w-50" name="game_name"
                               placeholder="Write the Game's name" required/>
                    </label>
                    <label className="d-flex flex-column mb-2">
                        Number of Players
                        <input type="number" value={playersNum} onChange={(e) => {
                            dispatch(setNewGameNamePlayersNum(e.target.value))
                        }} className="w-50" name="game_players_num"
                               min="2" max="9"
                               placeholder="Select the number of players" required/>
                    </label>
                </form>
            </Modal.Body>
            <Modal.Footer>
                <Button variant="danger" onClick={() => {dispatch(setShowNewGameModal(false))}}>
                    Close
                </Button>
                {
                    (gameName !== '' && playersNum !== '') ? (
                        <input type="submit" className="btn btn-success" value="Create Game"
                               data-bs-dismiss="modal"
                               onClick={(e) => createNewGame(e)}/>) : (
                        <input type="submit" className="btn btn-success" value="Create Game" disabled
                        />)
                }
            </Modal.Footer>
        </Modal>
        // <div className="modal fade" id="exampleModal" tabIndex="-1"
        //      aria-labelledby="exampleModalLabel"
        //      aria-hidden="true">
        //     <div className="modal-dialog">
        //         <div className="modal-content">
        //             <div className="modal-header">
        //                 <h5 className="modal-title" id="exampleModalLabel">Create New Game</h5>
        //                 <button type="button" className="btn-close" data-bs-dismiss="modal"
        //                         aria-label="Close"></button>
        //             </div>
        //             <form>
        //                 <div className="modal-body">
        //                     <label className="d-flex flex-column mb-3">
        //                         Game Name
        //                         <input type="text" value={gameName} onChange={(e) => {
        //                             setGameName(e.target.value)
        //                         }} className="w-50" name="game_name"
        //                                placeholder="Write the Game's name" required/>
        //                     </label>
        //                     <label className="d-flex flex-column mb-2">
        //                         Number of Players
        //                         <input type="number" value={playersNum} onChange={(e) => {
        //                             setPlayersNum(e.target.value)
        //                         }} className="w-50" name="game_players_num"
        //                                min="2" max="9"
        //                                placeholder="Select the number of players" required/>
        //                     </label>
        //                 </div>
        //                 <div className="modal-footer">
        //                     <button type="button" className="btn btn-danger"
        //                             data-bs-dismiss="modal">Close
        //                     </button>
        //                     {
        //                         (!gameName || !playersNum) ? (
        //                             <input type="submit" className="btn btn-success" value="Create Game"
        //                                    data-bs-dismiss="modal"
        //                                    onClick={(e) => createNewGame(e)}/>) : (
        //                             <input type="submit" className="btn btn-disabled" value="Create Game" disabled
        //                                    />)
        //                     }
        //
        //                 </div>
        //             </form>
        //         </div>
        //     </div>
        // </div>
    );
}

export default NewGameModal;
