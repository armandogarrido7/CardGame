import {useSelector} from "react-redux";
import {selectCurrentGame} from "../../slices/gamesSlice";

function OtherPlayer({player}) {
    const currentGame = useSelector(selectCurrentGame);
    return (
        <>
            <div className="cards d-flex gap-2 h-50">
                {(player.deck.data.length > 0) ?
                    ( (currentGame.current_round_number >= 5 && currentGame.current_round_number % 5 === 0) ?
                        (player.deck.data.map((card) => {
                            return (<img key={card.id} src={require('../img/' + card.number + '_' + card.suit + '.png')}
                                         alt={card.id}
                                         className="oponent_game_card"/>)
                        })):
                        (player.deck.data.map((card) => {
                        return (<img key={card.id} src={require('../img/back.png')}
                                     alt={card.id}
                                     className="oponent_game_card"/>)
                    }))) : ''
                }
            </div>
            <div className="d-flex align-items-center gap-2">
                {
                    (currentGame.winner === player.player_id) ? (<i className="fa-solid fa-crown fa-bounce fa-2xl mt-4 mb-3" style={{color: "gold"}}></i>) : ''
                }

                <img src={player.account.data.profile_pic} alt={player.account.data.name}
                     className="profile_pic rounded rounded-circle"/>
                <div className="d-flex align-items-baseline pt-3 pb-2 px-3 rounded rounded-pill bg-info">
                    <h5 className="me-1">{player.lives}</h5>
                    <i className="fa-solid fa-heart fa-xl text-danger"></i>
                    <h5 className="mx-2"> / </h5>
                    {
                        (player.predictions.data.length > 0 && currentGame) ?
                            (
                                (currentGame.current_round_number >= 5 && currentGame.current_round_number % 5 === 0 ) ?
                                    (
                                        (
                                            (player.predictions.data[player.predictions.data.length - 1].will_win === true) ?
                                                (<i className="fa-solid fa-thumbs-up fa-xl me-1" style={{color: "#00ff00"}}></i>) :
                                                (<i className="fa-solid fa-thumbs-down fa-xl me-1" style={{color: "#ff0000"}}></i>)
                                        )
                                    )
                                    : (
                                        <h5 className="me-1">{player.predictions.data[player.predictions.data.length - 1].rounds_won}</h5>
                                    )
                            ):
                            (<i className="fa-solid fa-xmark text-danger fa-xl"></i>)
                    }
                    <i className="fa-solid fa-xl fa-bullseye text-black"></i>
                    <h5 className="mx-2">/</h5>
                    <h5 className="me-1">{player.rounds_won}</h5>
                    <i className="fa-solid fa-medal fa-xl" style={{color: '#ffd700'}}></i>
                </div>
                {
                    (currentGame.status_name !== 'finished' && currentGame.current_player === player.player_id) ? (<i className="fa-solid fa-gamepad fa-bounce fa-xl" style={{color: "#ffdf00"}}/>) : ''
                }
                {
                    (player.lives === 0) ? (<i className="fa-solid fa-ghost fa-beat-fade fa-2xl" style={{color: "white"}}></i>) : ''
                }
            </div>
        </>

    );
}

export default OtherPlayer;
