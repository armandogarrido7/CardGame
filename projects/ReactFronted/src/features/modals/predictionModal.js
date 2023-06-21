import {Modal} from "react-bootstrap";
import {makePrediction} from "../../api/api";
import {useSelector} from "react-redux";
import {selectPlayerID} from "../../slices/playersSlice";
import {selectCurrentGame} from "../../slices/gamesSlice";

function PredictionModal() {
    const playerID = useSelector(selectPlayerID);
    const currentGame = useSelector(selectCurrentGame);

    function sendPrediction(prediction) {
        makePrediction(prediction, playerID).catch((error) => {
            console.log(error);
        });
    }

    return (
        <Modal show={currentGame.status_name !== 'finished' && currentGame.turn_type === 'prediction' && currentGame.current_player === playerID} size="lg"
               aria-labelledby="contained-modal-title-vcenter" centered>
            <Modal.Header>
                <Modal.Title>Make Your Prediction</Modal.Title>
            </Modal.Header>
            <Modal.Body>
                <div>
                    <div className="options d-flex flex-column justify-content-around">
                        {
                            (currentGame.current_cards_number !== 1) ?
                                (<h4 className="text-center">How many rounds do you think you will win?</h4>) :
                                (<h4 className="text-center">Are you going to win or lose this round?</h4>)
                        }
                        <div className="d-flex justify-content-around">
                            {(currentGame.allowed_predictions) ? (
                                    (currentGame.current_cards_number !== 1) ?
                                        ((currentGame.allowed_predictions.map(option => {
                                            return (
                                                <button className="btn btn-primary" key={option} value={option}
                                                        onClick={(e) => {
                                                            sendPrediction(parseInt(e.target.value))
                                                        }}>{option}</button>
                                            );
                                        }))) :
                                        ((
                                            <>
                                                <button className="btn btn-primary" onClick={(e) => {
                                                    sendPrediction(true)
                                                }}>Win</button>
                                                <button className="btn btn-primary" onClick={(e) => {
                                                    sendPrediction(false)
                                                }}>Lose</button>
                                            </>
                                                ))
                            ): ''
                            }
                        </div>
                    </div>

                </div>
            </Modal.Body>
        </Modal>
    );

}

export default PredictionModal;
