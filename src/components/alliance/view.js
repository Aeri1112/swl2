import React, {useEffect, useState} from 'react';
import {GET, POST} from "../../tools/fetch";
import AllianceMenu from "./alliance_menu";
import { useHistory } from "react-router-dom";
import Button from "react-bootstrap/Button";
import Modal from 'react-bootstrap/Modal';
import Form from "react-bootstrap/Form";

const View = () => {

    const [loading, setLoading] = useState();
    const [response, setResponse] = useState();

    const [userModal, setUserModal] = useState(false);
    const [userId, setUserId] = useState();
    const [selectedOption, setSelectedOption] = useState();

    const history = useHistory();

    const loadingData = async () => {
        setLoading(true);
        try{
            const response = await GET(`/alliances/view/${document.URL.split("/")[5]}`);
            if (response) {
                setResponse(response);
            }
        }
        catch {

        }
        finally {
            setLoading(false);
        }
    }

    const handleUserClick = () => {
        setUserModal(true);
    }

    const handleHide = () => {
        setUserModal(false);
    }

    const handleOptionChange = (e) => {
        setSelectedOption(e.target.id)
    }

    const handleSaveClick = async () => {
        setUserModal(false);
        const request = await POST(`/alliances/view/${document.URL.split("/")[5]}`, {userid:userId, selectedOption:selectedOption})
        if(request) {
            loadingData();
        }
    }

    useEffect(() => {
        loadingData();
    }, [])

    return ( 
        <div>
            {console.log(selectedOption)}
            {
                loading === false &&
                <div>
                    <div className="text-center">
                        {response.alliance.name + " - " + response.alliance.short}
                    </div>
                    <br/>
                    <div className="text-center m-1">
                        {"Leader: " + response.leader.username}
                    </div>
                    <div className="text-center m-1">
                        {"Co-leader: "} {response.coleader ? response.coleader.username : "no"}
                    </div>
                    <br/>
                    <div className="row">
                        {
                            response.members.map((element) => {
                                return (
                                    <div key={element.userid} className="col">
                                        {
                                            +response.char.alliance === response.alliance.id ?
                                            <div>
                                                <Button onClick={() => {handleUserClick(); setUserId(element.userid)}} className="text-dark" variant="link">{element.username}</Button>
                                            </div>
                                            : 
                                            <div>
                                                {element.username}
                                            </div>
                                        }
                                    </div>
                                );
                            })
                        }
                    </div>
                    <br/>
                    {
                        response.no_alliance === false ?
                            <AllianceMenu alliId={response.user_alliance}/>
                        : 
                            <div className="text-center">
                                <Button variant="link" onClick={() => history.goBack()}>zurück</Button>
                            </div>
                    }
                    <Modal show={userModal} onHide={handleHide}>
                        <Modal.Header closeButton>
                            <Modal.Title>modify User</Modal.Title>
                        </Modal.Header>
                        <Modal.Body>
                            <Form>
                                {
                                    response.char.userid === response.alliance.leader &&
                                    <Form.Check
                                        custom
                                        type="radio"
                                        label="promote to leader"
                                        name="radio-input"
                                        id="leader"
                                        value="leader"
                                        checked={selectedOption === "leader"}
                                        onChange={handleOptionChange}
                                    />
                                }
                                {
                                    response.char.userid === response.alliance.leader || response.char.userid === response.alliance.coleader ?
                                    <Form.Check
                                        custom
                                        type="radio"
                                        label="promote to coleader"
                                        name="radio-input"
                                        id="coleader"
                                        value="coleader"
                                        checked={selectedOption === "coleader"}
                                        onChange={handleOptionChange}
                                    />
                                    : null
                                }
                                <Form.Check
                                    custom
                                    type="radio"
                                    label="kick"
                                    name="radio-input"
                                    id="kick"
                                    value="kick"
                                    checked={selectedOption === "kick"}
                                    onChange={handleOptionChange}
                                />
                            </Form>
                        </Modal.Body>
                        <Modal.Footer>
                            <Button onClick={handleSaveClick}>save</Button>
                        </Modal.Footer>
                    </Modal>
                </div>
            }
        </div>
     );
}
 
export default View;