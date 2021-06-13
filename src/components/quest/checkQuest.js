import React, { useState, useEffect } from 'react';
import { Button, Spinner } from 'react-bootstrap';
import Countdown from '../../tools/countdown';
import { GET } from '../../tools/fetch';
import Puzzle from '../puzzle/App';

const CheckQuest = (props) => {

    const [data, setData] = useState();
    const [loading, setLoading] = useState();

    const [npc, setNpc] = useState();
    const [loadingNpc, setLoadingNpc] = useState();

    const [winPuzzle, setWinPuzzle] = useState(false);

    const loadData = async () => {
        setLoading(true);
        const response = await GET(`/quest/${props.details.typ}`);
        if(response) {
            setData(response)
        }
        setLoading(false);
    }

    const loadNpc = async () => {
        setLoadingNpc(true);
        const response = await GET(`/character/npc?id=${props.details.gegner_id}`);
        if(response) {
            setNpc(response)
        }
        setLoadingNpc(false);
    }

    const handleClickStart = async () => {
        setLoading(true);
        const request = await GET("/quest/wait?wait=1");
        if(request) {
            setData(request)
        }
        setLoading(false);
    }

    const handleClickAttack = async () => {
        setLoading(true);
        const request = await GET("/quest/fight?fight=1");
        if(request) {
            setData(request)
        }
        setLoading(false);
    }

    useEffect(() => {
        loadData();
        if(props.details.typ === "fight") {
            loadNpc();
        }
    },[])

    useEffect(() => {
        if(winPuzzle) {
            loadData();
        }
    },[winPuzzle])
    return ( 
        <div>
            {
                <div className="text-center">
                    {props.details.name}
                    <div className="mt-2 mb-2">
                        {props.details.einleitungstext}
                    </div>
                </div>
            }
            {
                //response[2] = char->targettime
                data && data.response[2] !== 0 && props.details.typ === "fight" &&
                <div className="text-center">
                    {
                        data.response[8].gegner_id === "2" &&
                        <img className="d-block mx-auto img" src={require(`../../images/monster/neu/layer2_1_2.jpg`) } alt="" />
                    }
                </div>
            }
            {
                //response[2] = char->targettime
                data && data.response[2] !== 0 &&
                <Countdown timeTillDate={data.response[2]} timeFormat="X" onFinish={loadData} />
            }
            {
                //Fight-Report
                //response[2] = char->targettime
                data && data.response[2] !== 0 && data.response[9] !== null && props.details.typ === "fight" &&
                <div className="text-center">
                    {
                        <div dangerouslySetInnerHTML={{ __html: data.response[9][2] }} />
                    }
                </div>
            }
            {
                data && data.response[2] === 0 && props.details.typ === "puzzle" &&
                <div>
                    <Puzzle img={data.response[8]} win={setData}/>
                </div>
            }
            {
                //response[4] = lose
                data && data.response[4] === true ?
                    <div>
                        <div className="text-center mt-3 mb-3">{props.details.misserfolgstext}</div>
                    </div>
                :
                //response[5] = win
                data && data.response[5] === true &&
                    <div>
                        <div className="text-center mt-3 mb-3">
                            {props.details.erfolgstext}
                            <div>
                                <Button onClick={() => props.refresh()}>ok</Button>
                            </div>
                        </div>
                    </div>
            }
            {
                //response[2] = char->targettime
                data && data.response[2] === 0 && props.details.typ === "wait" &&
                <Button onClick={handleClickStart}>start</Button>
            }
            {
                //response[2] = char->targettime
                data && loadingNpc === false && data.response[2] === 0 && props.details.typ === "fight" &&
                <div className="text-center">
                    <div>
                        Gegner: {npc.user.char.username}
                        <div>
                            Level: {npc.user.skills.level}
                        </div>
                    </div>
                    {
                        data.response[8].gegner_id === "2" &&
                        <img className="d-block mx-auto img" src={require(`../../images/monster/neu/layer2_1_2.jpg`) } alt="" />
                    }
                    <Button className="m-3" onClick={handleClickAttack}>attack</Button>
                </div>
            }
            {
                loading === true &&
                <div className="text-center mt-3 mb-3">
                    <Spinner animation="border" />
                </div>
            }
        </div>
     );
}
 
export default CheckQuest;