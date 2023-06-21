import {useSelector} from "react-redux";
import {selectCurrentGame} from "../../slices/gamesSlice";

function GameBoardCenter() {
    const currentGame = useSelector(selectCurrentGame);
    return (
        <>
            {
                (currentGame.players.data.length > 0 && currentGame.turn_type === 'play') ? (
                    currentGame.players.data.map((player) => {
                        let plays = player.plays.data
                        if (plays.length > 0) {
                            let last_play = plays[plays.length - 1]
                            let card_object = last_play.card.data;
                            if (last_play.card_flipped) {
                                return (
                                    <div className="position-relative" key={player.player_id}>
                                        <img
                                            src={require("../img/" + card_object.number + "_" + card_object.suit + ".png")}
                                            alt={card_object.id} className="game_card_played"/>
                                        <img src={player.account.data.profile_pic}
                                             alt={player.account.data.account_id}
                                             className="rounded rounded-circle card_player_img"/>
                                    </div>)
                            }
                        }
                    })
                ) : ''
            }
        </>
    );
}

export default GameBoardCenter;
