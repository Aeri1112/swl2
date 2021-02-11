import React, {useState, useEffect} from 'react';
import {GET} from "../../tools/fetch";
import ArenaModal from "./arena_modal_open";
import Countdown from "../../tools/countdown";
import moment from "moment";

import Table from 'react-bootstrap/Table'
import Button from "react-bootstrap/Button";
import Modal from "react-bootstrap/Modal";
import Spinner from 'react-bootstrap/Spinner'

const Arena = (props) => {

    const [loading, setLoading] = useState();
    const [response, setResponse] = useState();
    const [report, setReport] = useState();

    //if you cannot enter the arena
    const [open, setOpen] = useState(false);
    const [redirect, setRedirect] = useState();

    //open fight
    const [opening, setOpening] = useState(false);

    const loadData = async () => {
        setLoading(true);
        try {
            const response = await GET('/city/arena');
            setResponse(response);
            typeof response.fight_report !== "undefined" && setReport(response.fight_report.report);

            if(response.char.item_hand === 0) {
                setOpen("weapon")
                setRedirect("inventory")
            }
            else if (response.char.health <= 20 && response.char.actionid !== 15) {
                setOpen("health")
                setRedirect("bar")
            }
        }
        catch (e) {
            console.error(e)
        }
        finally {
            setLoading(false);
        }
    }

    useEffect(() => {
        !opening && loadData();
        
    }, [opening]);

    const clearFight = async () => {
        setReport();
        const clear = GET("/city/arena/clear");
        clear && loadData();
    }

    const handleJoin = async (id) => {
        const request = await GET(`/city/arena/join/${id}`);
            if(request) {
                loadData();
            }
    }

    const handleCancel = async () => {
        const request = await GET("/city/arena/cancel");
        if(request) {
            loadData();
        }
    }

    const tableData = (response) => {
        return response.fights.map((fight, index1) => {
            const username = [];
            const user = [];
            user[0] = "..., "
            user[1] = "... vs. ";
            user[2] = "..., "
            user[3] = "...";
            return(
                        <tr key={index1}>
                            <td>
                                {
                                    fight.type === "duel" ?
                                    response.fighters.map((fighter, index2) => {
                                        return (
                                            +fighter.fightid === fight.fightid ? 
                                                <div key={index2}>{fighter.char.username + "(" + fighter.skills.level + ")"} {index2 === 0 && " vs. "}</div>
                                            : null
                                        )
                                    })
                                    //coop
                                    : fight.type === "coop" &&
                                    response.fighters.map((fighter, index3) => {
                                        if(+fighter.fightid === fight.fightid) {
                                            if(fighter.teamid === "0" && fighter.position === "0") {
                                                user[0] = ""+fighter.char.username+"("+fighter.skills.level+"), ";
                                                username[0] = fighter.char.username;
                                            }
                                            if(fighter.teamid === "0" && fighter.position === "1") {
                                                user[1] = ""+fighter.char.username+"("+fighter.skills.level+") vs. ";
                                                username[1] = fighter.char.username;
                                            }
                                            if(fighter.teamid === "1" && fighter.position === "0") {
                                                user[2] = ""+fighter.char.username+"("+fighter.skills.level+"), ";
                                                username[2] = fighter.char.username;
                                            }
                                            if(fighter.teamid === "1" && fighter.position === "1") {
                                                user[3] = ""+fighter.char.username+"("+fighter.skills.level+")";
                                                username[3] = fighter.char.username;
                                            }
                                        }
                                    })
                                }
                                {fight.type === "coop" && user}
                            </td>
                            <td>
                                {fight.type}
                            </td>
                            <td>
                                {fight.bet}
                            </td>
                            <td>
                                {fight.status}
                            </td>
                            <td>
                                {
                                    fight.status === "preparing" ||
                                    fight.status === "fighting" ?
                                        <Countdown
                                            onFinish={() => props.history.push(`/arena`)}
                                            timeTillDate={fight.opentime + +fight.startin}
                                            timeFormat="X"
                                        />
                                    : null
                                }
                                {
                                    fight.type === "duel" && 
                                    fight.status === "open" && 
                                    response.char.actionid === 0 && 
                                    response.char.energy > 0 &&                                   
                                        <Button onClick={() => handleJoin(fight.fightid)} className="text-dark" variant="link">attack</Button>
                                }
                                {
                                    fight.type === "coop" && fight.status === "open" ?
                                        typeof username[0] === "undefined" && typeof username[1] !== "undefined" ?
                                        "join "+username[1]
                                        : typeof username[1] === "undefined" && typeof username[0] !== "undefined" ?
                                        "join "+username[0]
                                        : typeof username[1] === "undefined" && typeof username[0] === "undefined" ?
                                        "join Team 1"
                                        : null
                                    : null
                                }
                                {
                                    fight.type === "coop" && fight.status === "open" ?
                                        typeof username[0] === "undefined" || typeof username[1] === "undefined" ?
                                        typeof username[2] === "undefined" || typeof username[3] === "undefined" ?
                                        " | "
                                        : null
                                        : null
                                        : null
                                }
                                {
                                    fight.type === "coop" && fight.status === "open" ?
                                        typeof username[2] === "undefined" && typeof username[3] !== "undefined" ?
                                        "join "+username[3]
                                        : typeof username[3] === "undefined" && typeof username[2] !== "undefined" ?
                                        "join "+username[2]
                                        : typeof username[3] === "undefined" && typeof username[2] === "undefined" ?
                                        "join Team 2"
                                        : null
                                    : null
                                }
                            </td>
                        </tr>)
        }) 
    }

    return (
        <div>
            {
                loading && 
                    <div className="text-center">
                        <Spinner animation="border" role="status">
                            <span className="sr-only">Loading...</span>
                        </Spinner>
                    </div>
            }
            {
                open &&
                <Modal show={true} onHide={() => props.history.push(`/${redirect}`)}>
                    <Modal.Header>
                        <Modal.Title>You can't enter the Arena</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        {
                            open === "weapon" ? "Better equip a weapon!"
                            : open === "health" || open === "energy" ? "Better take a drink!"
                            : open === "busy" ? "You fighting somewhere else!"
                            : null
                        }
                    </Modal.Body>
                </Modal>
            }
            {
                loading === false && !report &&
                <div>
                <Table striped size="sm" responsive="sm">
                    <caption>running fights</caption>
                    <thead>
                        <tr>
                            <th scope="col">
                                Name
                            </th>
                            <th scope="col">
                                Typ
                            </th>
                            <th scope="col">
                                Wetteinsatz
                            </th>
                            <th scope="col">
                                Status
                            </th>
                            <th scope="col">
                                Aktion
                            </th>
                        </tr>
                    </thead>
                        <tbody>
                            {loading === false ?
                            response.fights ?
                            tableData(response)
                            :<tr><td colSpan="5">no fights</td></tr>
                            : <tr><td colSpan="5">loading...</td></tr>
                            }
                        </tbody>
                </Table>
                
                <div className="text-right">
                    <Button 
                        disabled={response.char.actionid !== 0 || response.char.energy <= 1 ? true : false} 
                        onClick={() => setOpening(true)}
                    >
                        Kampf eröffnen
                    </Button>
                    {" "}
                    <Button 
                        disabled={response.char.actionid === 15 ? false : true} 
                        onClick={() => handleCancel()}
                    >
                        Kampf abbrechen
                    </Button>
                </div>
                
                <ArenaModal show={opening} onHide={setOpening} response={response}/>
                </div>
            }
            {
                loading === false && report ?
                <div>
                    <div dangerouslySetInnerHTML={{ __html: report }}>
                    </div>
                    <Button className="text-dark" variant="link" onClick={() => clearFight()}>verwerfen</Button>
                </div>
                : null
            }
            {
                loading === false && 
                    <div className="small">
                        <div>finished fights</div>
                        {response.fight_reps.map((element) => {

                            return (
                                <div key={element.md5}>{moment(element.zeit,"X").format("DD.MM.YY HH:MM")} - <a rel="noopener noreferrer" target="_blank" href={`https://hosting142616.a2e76.netcup.net/fight/reada/${element.md5}`}>{element.headline}</a></div>
                            );
                        })}
                    </div>
            }
        </div>
    );
}

export default Arena;