import {useEffect, useState} from "react";
import {useDispatch, useSelector} from "react-redux";
import {selectCurrentGame} from "../../slices/gamesSlice";
import {selectAccountID} from "../../slices/accountSlice";
import OwnPlayer from "../ownPlayer/OwnPlayer";
import OtherPlayer from "../otherPlayer/OtherPlayer";
import {
    selectOthersPlayerData,
    setOthersPlayersData,
    setOwnPlayerData
} from "../../slices/playersSlice";
import PredictionModal from "../modals/predictionModal";
import FinishedGameModal from "../modals/finishedGameModal";
import GameBoardCenter from "./GameBoardCenter";
import GameStatusAlert from "./GameStatusAlert";

function GameBoard() {
    const accountID = useSelector(selectAccountID);
    const currentGame = useSelector(selectCurrentGame);
    const othersPlayersData = useSelector(selectOthersPlayerData);
    const [hasLoaded, setHasLoaded] = useState(false);
    const dispatch = useDispatch();

    function arrayRotate(arr, reverse) {
        if (reverse) arr.unshift(arr.pop());
        else arr.push(arr.shift());
        return arr;
    }

    useEffect(() => {
        let me = currentGame.players.data.filter(p => p.account.data.account_id === accountID)[0];
        let players_array = currentGame.players.data.slice();
        while (players_array[0] !== me) {
            arrayRotate(players_array, false);
        }
        dispatch(setOthersPlayersData(players_array.filter(p => p.account.data.account_id !== accountID)));
        dispatch(setOwnPlayerData(players_array.filter(p => p.account.data.account_id === accountID)[0]));
        setHasLoaded(true);
    }, [currentGame, accountID, dispatch]);

    if (hasLoaded) {
        switch (currentGame.players_number) {
            case 2:
                return (
                    <div className="bg-success border border-3 border-success p-3 rounded m-4 gap-3">
                        <FinishedGameModal/>
                        <PredictionModal/>
                        <div className="row">
                            <div className="col h-100"/>
                            <div className="col h-100 d-flex flex-column gap-2 align-items-center">
                                <OtherPlayer player={othersPlayersData[0]}/>
                            </div>
                            <div className="col h-100"/>
                        </div>
                        <div className="row">
                            <div className="col-3 h-100"/>
                            <div className="col-6 h-100 min-vh-25 d-flex justify-content-around bg-danger py-4 rounded rounded-4">
                                <GameBoardCenter/>
                            </div>
                            <div className="col-3 h-100"/>
                        </div>
                        <div className="row">
                            <div className="col h-100"/>
                            <div className="col h-100 d-flex flex-column align-items-center">
                                <OwnPlayer/>
                            </div>
                            <div className="col h-100 d-flex justify-content-end align-items-end">
                                <GameStatusAlert/>
                            </div>
                        </div>
                    </div>
                );
            case 3:
                return (
                    <div className="h-100 bg-success border border-3 border-success p-3 rounded m-4">
                        <FinishedGameModal/>
                        <PredictionModal/>
                        <div className="row h-33">
                            <div className="col d-flex flex-column align-items-center">
                                <OtherPlayer player={othersPlayersData[0]}/>
                            </div>
                            <div className="col h-100"/>
                            <div className="col h-100 d-flex flex-column align-items-center">
                                <OtherPlayer player={othersPlayersData[1]}/>
                            </div>
                        </div>
                        <div className="row h-33">
                            <div className="col h-100"/>
                            <div
                                className="col h-100 d-flex justify-content-around bg-danger py-4 rounded rounded-4">
                                <GameBoardCenter/>
                            </div>
                            <div className="col h-100"/>
                        </div>
                        <div className="row h-33 pt-3">
                            <div className="col h-100"/>
                            <div className="col h-100 d-flex flex-column align-items-center">
                                <OwnPlayer/>
                            </div>
                            <div className="col h-100 d-flex justify-content-end align-items-end">
                                <GameStatusAlert/>
                            </div>
                        </div>
                    </div>
                );
            case 4:
                return (
                    <div className="h-100 bg-success border border-3 border-success p-3 rounded m-4">
                        <FinishedGameModal/>
                        <PredictionModal/>
                        <div className="row h-33">
                            <div className="col h-100"/>
                            <div className="col d-flex flex-column align-items-center">
                                <OtherPlayer player={othersPlayersData[1]}/>
                            </div>
                            <div className="col h-100"/>
                        </div>
                        <div className="row h-33">
                            <div className="col h-100 d-flex flex-column align-items-center rotate-izq-90">
                                <OtherPlayer player={othersPlayersData[0]}/>
                            </div>
                            <div
                                className="col h-100 d-flex justify-content-around align-items-center bg-danger py-4 rounded rounded-pill">
                                <GameBoardCenter/>
                            </div>
                            <div className="col-4 h-100 d-flex flex-column align-items-center rotate-der-90">
                                <OtherPlayer player={othersPlayersData[2]}/>
                            </div>
                        </div>
                        <div className="row pt-3 h-33">
                            <div className="col h-100"/>
                            <div className="col h-100 d-flex flex-column align-items-center">
                                <OwnPlayer/>
                            </div>
                            <div className="col h-100 d-flex justify-content-end align-items-end">
                                <GameStatusAlert/>
                            </div>
                        </div>
                    </div>
                );
            case 5:
                return (
                    <div className="h-100 bg-success border border-3 border-success p-3 rounded m-4">
                        <FinishedGameModal/>
                        <PredictionModal/>
                        <div className="row h-25">
                            <div className="col h-100 d-flex flex-column align-items-center">
                                <OtherPlayer player={othersPlayersData[1]}/>
                            </div>
                            <div className="col"/>
                            <div className="col h-100 d-flex flex-column align-items-center">
                                <OtherPlayer player={othersPlayersData[2]}/>
                            </div>
                        </div>
                        <div className="row h-25">
                            <div className="col h-100 d-flex flex-column align-items-center justify-content-end">
                            </div>
                            <div
                                className="col h-100 d-flex justify-content-around align-items-center bg-danger py-4 rounded rounded-pill">
                                <GameBoardCenter/>
                            </div>
                            <div className="col-4 h-100 d-flex flex-column align-items-center justify-content-end">
                            </div>
                        </div>
                        <div className="row h-25">
                            <div className="col h-100 d-flex flex-column align-items-center justify-content-end">
                                <OtherPlayer player={othersPlayersData[0]}/>
                            </div>
                            <div className="col h-100"/>
                            <div className="col-4 h-100 d-flex flex-column align-items-center justify-content-end">
                                <OtherPlayer player={othersPlayersData[3]}/>
                            </div>
                        </div>
                        <div className="row h-25">
                            <div className="col h-100"/>
                            <div className="col h-100 d-flex flex-column align-items-center">
                                <OwnPlayer/>
                            </div>
                            <div className="col h-100 d-flex justify-content-end align-items-end">
                                <GameStatusAlert/>
                            </div>
                        </div>
                    </div>
                );
            case 6:
                return (
                    <div className="h-100 bg-success border border-3 border-success p-3 rounded m-4">
                        <FinishedGameModal/>
                        <PredictionModal/>
                        <div className="row h-20">
                            <div className="col h-100 d-flex flex-column align-items-center">
                                <OtherPlayer player={othersPlayersData[1]}/>
                            </div>
                            <div className="col"/>
                            <div className="col h-100 d-flex flex-column align-items-center">
                                <OtherPlayer player={othersPlayersData[2]}/>
                            </div>
                        </div>
                        <div className="row h-20">
                            <div className="col h-100 d-flex flex-column align-items-center rotate-izq-90">
                                <OtherPlayer player={othersPlayersData[0]}/>
                            </div>
                            <div
                                className="col h-100 d-flex justify-content-around align-items-center bg-danger py-4 rounded rounded-pill">
                                <GameBoardCenter/>
                            </div>
                            <div className="col-4 h-100 d-flex flex-column align-items-center rotate-der-90">
                                <OtherPlayer player={othersPlayersData[3]}/>
                            </div>
                        </div>
                        <div className="row h-20">
                            <div className="col h-100"/>
                            <div className="col h-100">
                                <GameBoardCenter/>
                            </div>
                            <div className="col h-100"/>
                        </div>
                        <div className="row h-20">
                            <div className="col h-100"/>
                            <div className="col h-100"/>
                            <div className="col h-100"/>
                        </div>
                        <div className="row pt-3 h-20">
                            <div className="col h-100"/>
                            <div className="col h-100 d-flex flex-column align-items-center">
                                <OwnPlayer/>
                            </div>
                            <div className="col h-100 d-flex justify-content-end align-items-end">
                                <GameStatusAlert/>
                            </div>
                        </div>

                    </div>
                );
            default:
                return (<></>);
        }
    }

}

export default GameBoard;
