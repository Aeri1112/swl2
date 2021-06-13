import React, { useEffect, useState } from "react";
import { useSelector, useDispatch } from "react-redux";
import {GET,POST} from "../../tools/fetch";
import Alliance_menu from "./alliance_menu";

import { fetchAllianceData } from "../../redux/actions/allianceActions";

import Button from "react-bootstrap/Button";
import Form from "react-bootstrap/Form";

const Raid = () => {

    const [loading, setLoading] = useState();
    const dispatch = useDispatch();
    const AlliData = useSelector(state => state.alliance);

    const [loadingChar, setLoadingChar] = useState();
    const [char, setChar] = useState();

    const [enemy, setEnemy] = useState(1);

    const loadingAllianceData = async() => {
        try {
            setLoading(true)
            const response = await GET('/alliances/raid')
            if (response) {
                dispatch(fetchAllianceData(response))
            }
        } catch (e) {
            console.error(e)
        } finally {
            // finally wird immer ausgefuehrt.
            // dadurch wird der state auch immer danach false gesetzt.
            setLoading(false)
        }
    }

    const loadingCharData = async () => {
        try {
            setLoadingChar(true)
            const response = await GET('/character/user')
            if (response) {
                setChar(response)
            }
        } catch (e) {
            console.error(e)
        } finally {
            // finally wird immer ausgefuehrt.
            // dadurch wird der state auch immer danach false gesetzt.
            setLoadingChar(false)
        }
    }

    const handleCancel = async (userId) => {
        const request = await POST("/alliances/raid/cancel",{userid: userId});
        if(request) {
            loadingAllianceData();
        }
    }

    const handleInitChange = (e) => {
        setEnemy(e.target.value)
    }

    const handleInit = async () => {
        const request = await POST("/alliances/raid",{npc:enemy});
        if (request) {
            loadingAllianceData();
            loadingCharData();
        }
    }

    const handleStart = async () => {
        const request = await GET("/alliances/raid/start");
        if (request) {
            loadingAllianceData();
            loadingCharData();
        }
    }

    const handleJoin = async () => {
        const request = await GET("/alliances/raid/join");
        if (request) {
            loadingAllianceData();
            loadingCharData();
        }
    }

    const handleAdd = async (userid) => {
        const request = await POST("/alliances/raid/add",{userid:userid})
        if (request) {
            loadingAllianceData();
        }
    }

    useEffect(() => {
        loadingAllianceData();
        loadingCharData();
    }, []);

    return ( 
        <div>
            {
                loading === false && loadingChar === false && AlliData.AlliData.no_alliance === false &&
                <div>
                    {
                        AlliData.AlliData.alli_fight !== null &&
                        <div>
                            <div>
                                Beigetretene Mitglieder
                            </div>
                            <div className="row">
                                {
                                    AlliData.AlliData.raid_members.map((element) => {
                                        return (
                                            <div className='col-sm-3 pb-1 pr-1 pt-1' style={{minHeight:"100px",marginLeft:"10px",borderRadius:"20px",backgroundColor:"gainsboro"}}>
                                                <div className="progress progress-bar-vertical" >
                                                    <div className="progress-bar progress-bar-striped bg-danger" role="progressbar" style={{height: element.HealthPro+"%"}} aria-valuenow={element.HealthPro} aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <div className="progress progress-bar-vertical">
                                                    <div className="progress-bar progress-bar-striped bg-primary " role="progressbar" style={{height: element.ManaPro+"%"}} aria-valuenow={element.ManaPro} aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <div className='col-sm-auto'>
                                                    <div>{`Name: ${element.username}`}</div>
                                                    <div>{`Level: ${element.skills.level}`}</div>
                                                    {
                                                        char.user.char.userid === element.userid &&
                                                        <div>
                                                            <Button onClick={() => handleCancel(element.userid)} className="p-0 border-0" variant="link">abbrechen</Button>
                                                        </div>
                                                    }
                                                    {
                                                        AlliData.AlliData.is_leader === true && char.user.char.userid !== element.userid &&
                                                        <div>
                                                            <Button onClick={() => handleCancel(element.userid)} className="p-0 border-0" variant="link">kicken</Button>
                                                        </div>
                                                    }
                                                </div>
                                            </div>
                                        );
                                    })
                                }
                            </div>
                            <div>
                                {
                                    AlliData.AlliData.is_leader &&
                                    <Button onClick={handleStart} className="mt-3 mr-3">starten</Button>
                                }
                                {
                                    <Button
                                        onClick={handleJoin} 
                                        variant="success" 
                                        className="mt-3"
                                        disabled={char.user.char.actionid !== 0 || char.user.char.targetid !== 0 || char.user.char.targettime !== 0 ? true : false}
                                    >
                                        teilnehmen
                                    </Button>
                                }
                            </div>
                        </div>
                    }
                    {
                        AlliData.AlliData.raid_running &&
                        <div>
                            Die Teilnehmer sind bereits gestartet
                        </div>
                    }
                    {
                        //Freie Mitglieder die joinen können
                        AlliData.AlliData.alli_fight !== null && AlliData.AlliData.free_members &&
                        <div>
                            <hr/>
                            <div className="text-center">
                                In unserer Lounge hängen folgende Charaktere ab und würden sich unserer Jagd anschließen wollen
                            </div>
                            <div className="row">
                                {
                                    AlliData.AlliData.free_members.map((element) => {
                                        return (
                                            <div className='col-sm-3 pb-1 pr-1 pt-1' style={{minHeight:"100px",marginLeft:"10px",borderRadius:"20px",backgroundColor:"gainsboro"}}>
                                                <div className="progress progress-bar-vertical" >
                                                    <div className="progress-bar progress-bar-striped bg-danger" role="progressbar" style={{height: element.HealthPro+"%"}} aria-valuenow={element.HealthPro} aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <div className="progress progress-bar-vertical">
                                                    <div className="progress-bar progress-bar-striped bg-primary " role="progressbar" style={{height: element.ManaPro+"%"}} aria-valuenow={element.ManaPro} aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <div className='col-sm-auto'>
                                                    <div>{`Name: ${element.username}`}</div>
                                                    <div>{`Level: ${element.skills.level}`}</div>
                                                    {
                                                        AlliData.AlliData.is_leader === true && char.user.char.userid !== element.userid &&
                                                        <div>
                                                            <Button onClick={() => handleAdd(element.userid)} className="p-0 border-0" variant="link">hinzufügen</Button>
                                                        </div>
                                                    }
                                                </div>
                                            </div>
                                        );
                                    })
                                }
                            </div>
                        </div>
                    }
                    {<div className="text-center">{"restliche Versuche: " + AlliData.AlliData.alliance.attemps}</div>}
                </div>
            }
            {
                loading === false && 
                AlliData.AlliData.is_leader && 
                !AlliData.AlliData.raid_running && 
                !AlliData.AlliData.alli_fight &&
                    <div>
                        <div>
                            <Form>
                                <Form.Group onChange={handleInitChange}>
                                    <Form.Label>Wähle den Gegner</Form.Label>
                                    <Form.Control id="npc" name="npc" as="select" custom>
                                        <option value={1}>Giant-Womp-Rat</option>
                                        <option value={9}>Reek</option>
                                    </Form.Control>
                                </Form.Group>
                                <Button variant="primary" onClick={handleInit}>
                                    starten
                                </Button>
                            </Form>
                        </div>
                    </div>
            }
            {
                loading === false &&
                <Alliance_menu alliId={AlliData.AlliData.alliance.id}/>
            }
            {
                (loading === true || loadingChar === true) &&
                "loading..."
            }
        </div> 
    );
}
 
export default Raid;