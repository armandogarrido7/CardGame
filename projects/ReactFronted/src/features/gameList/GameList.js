import GameCard from "../gameCard/GameCard";
import {useSelector} from "react-redux";
import {selectAPIError, selectGamesList} from "../../slices/gamesSlice";

function GameList() {
    const games = useSelector(selectGamesList);
    const apiError = useSelector(selectAPIError);
    return (
        <>
            <h2 className="text-center">~ Game List ~</h2>
            <div className="d-flex flex-column w-100 justify-content-center align-items-center">
                {
                    apiError ?
                        (<h3 className="text-center alert alert-danger w-50 m-auto">Error fetching Games!</h3>) :
                        (
                            (games.length > 0) ? (
                                games.map((game) => (
                                    <GameCard game={game} key={game.game_id}/>
                                ))
                            ) : (<h3 className="text-center alert alert-info w-50 m-auto">There's no available games</h3>)
                        )
                }
            </div>
        </>
    )
}

export default GameList;
