import React, { useEffect, useState } from 'react';
import { Row } from 'react-bootstrap';
import { GET } from '../../tools/fetch';
import "./pada.css";
import Request from './request';

const Pada = () => {

    const [response, setResponse] = useState();
    const [loading, setLoading] = useState();

    const [listData, setListData] = useState();

    const loadData = async () => {
        setLoading(true);
        const response = await GET("/preferences/pada");
        if(response) {
            setResponse(response);
            if(response.level >= 75) {
                setListData(response.padas);
            }
            else {
                setListData(response.masters);
            }
            setLoading(false);
        }
    }

    const forfeit = async (id) => {
        const request = await GET(`/preferences/forfeit/${id}`);
        if(request) {
            const response = await GET("/preferences/pada");
            if(response) {
                setResponse(response);
                if(response.level >= 75) {
                    setListData(response.padas);
                }
                else {
                    setListData(response.masters);
                }
            }
        }
    }

    useEffect(() => {
        loadData();
    },[])

    return ( 
        <div>
            {
                loading === false && response.nomaster === true &&
                <div>
                    <div>
                        Du bist noch keine Bindung zu einem {response.level >= 75 ? "Schüler" : "Meister"} eingegangen.
                    </div>
                    <div>
                        Falls du auf der Suche nach einem {response.level >= 75 ? "Schüler" : "Meister"} bist, hast du hier eine Übersicht der aktuell verfügbaren.
                    </div>
                    {
                        <div className="container">
                        <Row>
                            {listData.map((player) => {
                                return(
                                        <Request
                                            player = {player}
                                            forfeit = {forfeit}
                                        />
                                    )
                                })
                            }
                        </Row>
                        </div>
                    }
                </div>
            }
        </div>
     );
}
 
export default Pada;