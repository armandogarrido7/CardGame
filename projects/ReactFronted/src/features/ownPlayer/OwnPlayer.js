import {useSelector} from "react-redux";
import {selectOwnPlayerData, selectPlayerID} from "../../slices/playersSlice";
import {useEffect, useState} from "react";
import {selectCurrentGame} from "../../slices/gamesSlice";
import {makePlay} from "../../api/api";
import {selectCurrentGameID} from "../../slices/accountSlice";

function OwnPlayer() {
    const [hasLoaded, setHasLoaded] = useState(false);
    const ownPlayerData = useSelector(selectOwnPlayerData);
    const currentGame = useSelector(selectCurrentGame);
    const playerID = useSelector(selectPlayerID);
    const gameID = useSelector(selectCurrentGameID);

    useEffect(() => {
        setHasLoaded(true);
    }, []);

    function playCard(cardID) {
        if (currentGame.turn_type === 'play' && currentGame.current_player === playerID) {
            makePlay(gameID, playerID, cardID).then((response) => {
                console.log(response);
            }).catch((error) => {
                console.log(error);
            })
        }
    }

    if (hasLoaded) return (
        <>
            <div className="d-flex align-items-center gap-2">
                <img src={ownPlayerData.account.data.profile_pic} alt={ownPlayerData.account.data.name}
                     className="profile_pic rounded rounded-circle"/>
                <div className="d-flex align-items-baseline mt-3 pt-3 pb-2 px-3 mb-2 rounded rounded-pill bg-info">
                    <h5 className="me-1">{ownPlayerData.lives}</h5>
                    <i className="fa-solid fa-heart fa-xl text-danger"></i>
                    <h5 className="mx-2"> / </h5>
                    {
                        (ownPlayerData.predictions.data.length > 0 && currentGame) ?
                            (
                                (currentGame.current_round_number >= 5 && currentGame.current_round_number % 5 === 0 ) ?
                                    (
                                        (
                                            (ownPlayerData.predictions.data[ownPlayerData.predictions.data.length - 1].will_win === true) ?
                                                (<i className="fa-solid fa-thumbs-up fa-xl me-1" style={{color: "#00ff00"}}></i>) :
                                                (<i className="fa-solid fa-thumbs-down fa-xl me-1" style={{color: "#ff0000"}}></i>)
                                        )
                                    )
                                    : (
                                        <h5 className="me-1">{ownPlayerData.predictions.data[ownPlayerData.predictions.data.length - 1].rounds_won}</h5>
                                    )
                            ):
                            (<i className="fa-solid fa-xmark text-danger fa-xl"></i>)
                    }
                    <i className="fa-solid fa-bullseye fa-xl text-black"></i>
                    <h5 className="mx-2">/</h5>
                    <h5 className="me-1">{ownPlayerData.rounds_won}</h5>
                    <i className="fa-solid fa-medal fa-xl" style={{color: '#ffd700'}}></i>
                </div>
                {
                    (currentGame.status_name !== 'finished' && currentGame.current_player === ownPlayerData.player_id) ? (<i className="fa-solid fa-gamepad fa-bounce fa-xl" style={{color: "#ffdf00"}}/>) : ''
                }
                {
                    (ownPlayerData.lives === 0) ? (<i className="fa-solid fa-ghost fa-beat-fade fa-2xl" style={{color: "white"}}></i>) : ''
                }
            </div>
            <div className="cards d-flex gap-2">
                {(ownPlayerData.deck.data.length > 0) ? (
                        (currentGame.current_round_number >= 5 && currentGame.current_round_number % 5 === 0) ?
                            (ownPlayerData.deck.data.map((card) => {
                                return (<img key={card.id} onClick={() => {
                                    playCard(card.id)
                                }} src={require('../img/back.png')}
                                             alt={card.number + card.suit}
                                             className='game_card not_clickable'/>)
                            }))
                             :
                            (ownPlayerData.deck.data.map((card) => {
                                return (<img key={card.id} onClick={() => {
                                    playCard(card.id)
                                }} src={require('../img/' + card.number + '_' + card.suit + '.png')}
                                             alt={card.number + card.suit}
                                             className={(currentGame.turn_type === 'play' && currentGame.current_player === playerID) ?
                                                 ('game_card clickable') : ('game_card not_clickable')}/>)
                            }))

                    )
                    : ''
                }
            </div>
        </>

    );
}

export default OwnPlayer;
