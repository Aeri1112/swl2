import React, { useEffect, useState } from 'react';
import {useSelector} from "react-redux";
import { Button, Spinner } from 'react-bootstrap';
import Pagination from "react-js-pagination";
import { GET } from '../../tools/fetch';
import "./glass.css";

const Players = () => {

    const [loading, setLoading] = useState();
    const [data, setData] = useState();

    const [activePage, setActivePage] = useState(1);

    const [ranking, setRanking] = useState("level");

    const userid = useSelector(state => state.user.userId);

    const handlePageChange = (pageNumber) => {
        setActivePage(pageNumber)
    }



    const loadData = async() => {
        setLoading(true);
        const response = await GET(`/statistics/ranking?type=${ranking}&page=${activePage}`);
        if(response) {
            setData(response);
        }
        setLoading(false);
    }

    useEffect(() => {
        loadData();
    },[activePage, ranking]);

    return ( 
        <div>
            {
                <div>
                    <div className="d-flex align-items-center justify-content-center main">
                        <div className="row glass">
                            <div className="col-md-4 dash">
                                <div className="status">
                                    MVP
                                </div>
                                {
                                    <div className="m-1">
                                        <div className="row">
                                            <div className="col">
                                                <Button variant="link" onClick={() => setRanking("level")}>
                                                    Erfahrenste (Level)
                                                </Button>
                                            </div>
                                        </div>
                                        <small>
                                            {
                                                loading === false ?
                                                    data.level.map((player, index) => {
                                                        const green = userid === player.userid ? "d-flex justify-content-between text-info" : "d-flex justify-content-between";
                                                        return (
                                                            <div key={index} className={green}>
                                                                <div>
                                                                    {+index+1 +". " + player.jedi_user_char.username}
                                                                </div>
                                                                <div>
                                                                    { player.level }
                                                                </div>
                                                            </div>
                                                        );
                                                    })
                                                : <Spinner animation="border" />
                                            }
                                        </small>
                                        <div className="row mt-1">
                                            <div className="col">
                                                <Button variant="link" onClick={() => setRanking("killedRat")}>
                                                    Rattenfänger (killed Rats)
                                                </Button>
                                            </div>
                                        </div>
                                        <small>
                                            {
                                                loading === false ?
                                                    data.rats.map((player, index) => {
                                                        const green = userid === player.userid ? "d-flex justify-content-between text-info" : "d-flex justify-content-between";
                                                        return (
                                                            <div key={index} className={green}>
                                                                <div>
                                                                    {+index+1 +". " + player.jedi_user_char.username}
                                                                </div>
                                                                <div>
                                                                    { player.killedRat }
                                                                </div>
                                                            </div>
                                                        );
                                                    })
                                                : <Spinner animation="border" />
                                            }
                                        </small>
                                        <div className="row mt-1">
                                            <div className="col">
                                                <Button variant="link" onClick={() => setRanking("loots")}>
                                                    Schatzsucher (Loots)
                                                </Button>
                                            </div>
                                        </div>
                                        <small>
                                            {
                                                loading === false ?
                                                    data.loots.map((player, index) => {
                                                        const green = userid === player.userid ? "d-flex justify-content-between text-info" : "d-flex justify-content-between";
                                                        return (
                                                            <div key={index} className={green}>
                                                                <div>
                                                                    {+index+1 +". " + player.jedi_user_char.username}
                                                                </div>
                                                                <div>
                                                                    { player.loots }
                                                                </div>
                                                            </div>
                                                        );
                                                    })
                                                : <Spinner animation="border" />
                                            }
                                        </small>
                                        <div className="row mt-1">
                                            <div className="col">
                                                <Button variant="link" onClick={() => setRanking("arenawins")}>
                                                    Gladiator (Arena Wins)
                                                </Button>
                                            </div>
                                        </div>
                                        <small>
                                            {
                                                loading === false ?
                                                    data.arena.map((player, index) => {
                                                        const green = userid === player.userid ? "d-flex justify-content-between text-info" : "d-flex justify-content-between";
                                                        return (
                                                            <div key={index} className={green}>
                                                                <div>
                                                                    {+index+1 +". " + player.jedi_user_char.username}
                                                                </div>
                                                                <div>
                                                                    { player.arenawins } {"(" + Math.round((player.arenawins * 100 / (player.arenalosts + player.arenawins)) * 100) / 100 + "%)" }
                                                                </div>
                                                            </div>
                                                        );
                                                    })
                                                : <Spinner animation="border" />
                                            }
                                        </small>
                                    </div>
                                }
                            </div>
                            <div className="col-md-8 rank">
                                <div className="status">
                                    Player Ranking
                                </div>
                                    {
                                        <div className="m-1">
                                            <div className="row justify-content-between">
                                                <div className="col-auto">
                                                    Name
                                                </div>
                                                <div className="col-auto">
                                                    Level
                                                </div>
                                            </div>
                                            {
                                                loading === false ?
                                                    data.list.map((player,index) => {
                                                        const green = userid === player.userid ? "row justify-content-between text-info" : "row justify-content-between";
                                                        return (
                                                            <div key={index} className={green}>
                                                                <div className="col-auto">
                                                                    {+index+1 +". " + player.jedi_user_char.username}
                                                                </div>
                                                                <div className="col-auto">
                                                                    {player[`${ranking}`]} 
                                                                    {
                                                                        ranking === "arenawins" &&
                                                                        " (" + Math.round((player.arenawins * 100 / (player.arenalosts + player.arenawins)) * 100) / 100 + "%)"
                                                                    }
                                                                </div>
                                                            </div>
                                                        );
                                                    })
                                                :
                                                    <div className="text-center"><Spinner animation="border"/></div>
                                            }
                                        </div>
                                    }
                            </div>
                        </div>
                    </div>
                    <div>
                        {
                            loading === false &&
                                <Pagination
                                    hideDisabled
                                    activePage={activePage}
                                    itemsCountPerPage={10}
                                    totalItemsCount={data.players}
                                    pageRangeDisplayed={3}
                                    onChange={handlePageChange}
                                    itemClass="page-item"
                                    linkClass="page-link"
                                />
                        }
                    </div>
                </div>
            }
        </div>
     );
}
 
export default Players;