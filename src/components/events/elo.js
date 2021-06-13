import React, { useEffect, useState } from 'react';
import {useSelector} from "react-redux";
import Pagination from "react-js-pagination";
import { Button } from 'react-bootstrap';
import { GET, POST } from '../../tools/fetch';
import moment from "moment";

const Elo = () => {

    const [loading, setLoading] = useState();
    const [data, setData] = useState();

    const [activePage, setActivePage] = useState(1);

    const userid = useSelector(state => state.user.userId);

    const loadData = async () => {
        setLoading(true);
        const response = await GET(`/events/ranking?page=${activePage}`)
        if(response) {
            setData(response);
        }
        setLoading(false);
    }

    const handlePageChange = (pageNumber) => {
        setActivePage(pageNumber)
    }

    const handleAttack = async (userid) => {
        const request = await POST(`/events/ranking`,{userid:userid});
        if(request) {
            setData(request)
        }
    }

    const handleJoin = async () => {
        const request = await GET(`/events/ranking/join`);
        if(request) {
            setData(request);
        }
    }

    useEffect(() => {
        loadData();
    },[activePage])

    return ( 
        <div>
            {
                loading === false &&
                    <div>
                        <div className="text-center">
                            Single-Ranking-Event
                        </div>
                        <div className="d-flex justify-content-between">
                                {
                                    data.event !== null &&
                                    <div>
                                        restliche Versuche: { data.event.attemps }
                                    </div>
                                }
                            <div>
                                Läuft bis: { moment(data.cur_event.expire).format("DD.MM.YYYY HH:mm") }
                            </div>
                        </div>
                        <div className="d-flex justify-content-between">
                            <div>
                                Reset der Versuche: every day
                            </div>
                            <div>
                                <Button
                                    variant="success"
                                    disabled = {data.event !== null}
                                    onClick={handleJoin}
                                >
                                    join
                                </Button>
                            </div>
                        </div>
                        <div className="table-responsive-md">
                            <table className="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">User</th>
                                        <th scope="col">Points</th>
                                        <th scope="col">Fights</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {
                                        data.players.map((player, index) => {
                                            const tableDanger = player.userid === userid ? "table-danger" : null;
                                            return (
                                                <tr key={player.userid} className={tableDanger}>
                                                    <td>
                                                        { +index+1 + ". " }
                                                    </td>
                                                    <td>
                                                        { player.JediUserChars.username }
                                                    </td>
                                                    <td>
                                                        { player.points }
                                                    </td>
                                                    <td>
                                                        { player.fights }
                                                    </td>
                                                    <td>
                                                        {
                                                            player.userid !== userid &&
                                                            <Button 
                                                                onClick={() => handleAttack(player.userid)}
                                                                disabled={data.event === null || data.event.attemps <= 0}
                                                            >
                                                                attack
                                                            </Button>
                                                        }
                                                    </td>
                                                </tr>
                                            );
                                        })
                                    }
                                </tbody>
                            </table>
                        </div>
                        {
                            <Pagination
                                hideDisabled
                                activePage={activePage}
                                itemsCountPerPage={10}
                                totalItemsCount={data.count}
                                pageRangeDisplayed={3}
                                onChange={handlePageChange}
                                itemClass="page-item"
                                linkClass="page-link"
                            />
                        }
                        <div className="row">
                            <div className='col-md'>
                                your attacks:
                                <div className="small">
                                    {
                                        data.fight_reps_a.map((rep) => {
                                            return (
                                                <div key={rep.md5}>{moment(rep.zeit,"X").format("DD.MM. HH:mm") + " Uhr " + rep.headline}</div>
                                            );
                                        })
                                    }
                                </div>
                            </div>
                            <div className='col-md'>
                                your defenses:
                                <div className="small">
                                    {
                                        data.fight_reps_d.map((rep) => {
                                            return (
                                                <div key={rep.md5}>{moment(rep.zeit,"X").format("DD.MM. HH:mm") + " Uhr " + rep.headline}</div>
                                            );
                                        })
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
            }
        </div>
     );
}
 
export default Elo;