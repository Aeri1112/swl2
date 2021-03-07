import React, { useEffect, useState } from "react";
import { useSelector, useDispatch } from "react-redux";
import {Link} from "react-router-dom";
import {GET, POST} from "../../tools/fetch";
import Alliance_menu from "./alliance_menu";

import { fetchAllianceData } from "../../redux/actions/allianceActions";

import Table from "react-bootstrap/Table";
import Button from "react-bootstrap/Button";
import Modal from "react-bootstrap/Modal";
import Form from "react-bootstrap/Form";

const Alliance = () => {

    const [loading, setLoading] = useState();
    const dispatch = useDispatch();
    const AlliData = useSelector(state => state.alliance);

    const [alliance, setAlliance] = useState();
    const [join, setJoin] = useState(false);
    const [password, setPassword] = useState();
    const [error, setError] = useState();

    const loadingAllianceData = async() => {
        try {
            setLoading(true)
            const response = await GET('/alliances')
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

    const handleClickJoin = () => {
        setJoin(true);
    }

    const handleClose = () => {
        setJoin(false);
        setPassword();
        setError();
    }

    const handlePassword = async () => {
        const request = await POST(`/alliances/join/${alliance}`, {password:password});
        if(request.error) {
            setError(request.error);
        }
        else if (!request.error) {
            setJoin(false);
            loadingAllianceData();
        }
    }

    const handlePasswordChange = (e) => {
        setPassword(e.target.value)
    }

    useEffect(() => {
        loadingAllianceData();
    }, []);

    return (
    <>
        {
            loading === false && AlliData.AlliData.no_alliance === false ? 
                <div>
                    {
                        <div>
                            <div className="text-center mb-2">
                                {"Willkommen bei " + AlliData.AlliData.alliance.name + " (" + AlliData.AlliData.alliance.short + ")"}
                            </div> 
                            {
                                AlliData.AlliData.alli_fight !== null &&
                                <div className="d-flex justify-content-center">Die Teilnahme an einem Raid ist möglich</div>
                            }
                            {
                                AlliData.AlliData.raid_running === true &&
                                <div className="d-flex justify-content-center">Raid-Gruppe ist bereits gestartet</div>
                            }
                            <div className='col-auto p-0 d-flex justify-content-center'>
                                <img className="img-fluid" src={`${AlliData.AlliData.alliance.pic}`} />
                            </div>
                            <div className="text-center mt-2">
                                {AlliData.AlliData.alliance.description}
                            </div> 
                            {<Alliance_menu alliId={AlliData.AlliData.alliance.id}/>}
                        </div>
                    }
                </div>    
            : 
                "loading..."
        }
        {
            loading === false && AlliData.AlliData.no_alliance === true &&
            <div>
                <Table striped size="sm" responsive="sm">
                    <thead>
                        <tr>
                            <th>
                                Name
                            </th>
                            <th>
                                Kürzel
                            </th>
                            <th>
                                Ausrichtung
                            </th>
                            <th>
                                Aktion
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {
                            AlliData.AlliData.alliances.map((element) => {
                                return (
                                    <tr key={element.id}>
                                        <td>
                                            {element.name}
                                        </td> 
                                        <td>
                                            {element.short}
                                        </td>
                                        <td>
                                            {element.alignment === 1 ? "hell" : element.alignment === 0 ? "neutral" : "dunkel"}
                                        </td>
                                        <td>
                                            <Link to={`/alliance/view/${element.id}`}>view</Link>
                                            {" | "}
                                            <Button className="p-0 border-0" onClick={() => {handleClickJoin(); setAlliance(element.id)}} variant="link" style={{verticalAlign:"top"}}>join</Button>
                                        </td>  
                                    </tr>
                                );
                            })
                        }
                    </tbody>
                </Table>
            </div>
        }
        <Modal show={join} onHide={handleClose}>
            <Modal.Header closeButton>
                <Modal.Title>Joining Alliance</Modal.Title>
            </Modal.Header>
            <Modal.Body>
                <Form>
                    <Form.Group controlId="exampleForm.ControlInput1">
                        <Form.Label>enter their password</Form.Label>
                        <Form.Control onChange={handlePasswordChange} value={password} type="text" placeholder="password" />
                    </Form.Group>
                </Form>
                {<div className="text-danger">{error}</div>}
            </Modal.Body>
            <Modal.Footer>
                <Button onClick={handlePassword}>
                    join
                </Button>
            </Modal.Footer>
        </Modal>
    </>
    );
}

export default Alliance;