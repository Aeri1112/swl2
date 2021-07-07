import React, { useEffect, useState } from 'react';
import { Row, Col, Button } from 'react-bootstrap';
import Modal from 'react-bootstrap/Modal';
import Allgemein from "./allgemein";
import Abis from "./abis";
import Mights from './mights';

const Card = (props) => {

    const [show, setShow] = useState(false);

    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);

    const [tab, setTab] = useState(1);
    const [tabContent, setTabContent] = useState();

    useEffect(() => {
        switch (tab) {
            case 1:
                setTabContent(<Allgemein player={props.player}/>)
                break;
            case 2:
                setTabContent(<Abis player={props.player} />)
                break;
            case 3:
                setTabContent(<Mights player={props.player} />)
                break;
            default:
                break;
        }
    },[tab])
    
    return (
        <div className="container"> 
            <Row className="justify-content-center">
                <Col className="shadow">
                    <Row>
                        <Col className="p-3 border rounded-left" md={4} xs="12">
                            <Row className="justify-content-between" style={{minHeight:"250px", height:"100%", margin:"0.1rem"}}>
                                <Col md={12} className="text-center mt-1 mb-1 justify-content-center border rounded-pill align-self-start">
                                    Level {props.player.skills.level}
                                </Col>
                                <Col className="d-flex justify-content-center align-self-center p-1">
                                    <img src={`/images/profile/${props.player.userid}.jpg`} className="text-center img-radius" alt="User-Profile-Image"/>
                                </Col>
                                <Col md={12} className="text-center mb-1 mt-1 border rounded-pill align-self-end">
                                    Activity {props.player.activePoints}
                                </Col>
                            </Row>
                        </Col>
                        <Col className="border border-left-0 rounded-right" md={8} xs="12" style={{background:"linear-gradient(to left,rgb(219,219,219),rgb(255,255,255))"}}>
                            <Row>
                                <Col>
                                    <h1>
                                        {props.player.username}
                                    </h1>
                                </Col>
                            </Row>
                            <Row>
                                <Col xs="4" className={window.innerWidth < 576 && "p-1"}>
                                    <Button onClick={() => setTab(1)} size={window.innerWidth < 576 && "sm"} className="w-100" variant="outline-secondary">
                                        Allgemein
                                    </Button>
                                </Col>
                                <Col xs="4" className={window.innerWidth < 576 && "p-1"}>
                                    <Button onClick={() => setTab(2)} size={window.innerWidth < 576 && "sm"} className="w-100" variant="outline-secondary">
                                        Fähigkeiten
                                    </Button>
                                </Col>
                                <Col xs="4" className={window.innerWidth < 576 && "p-1"}>
                                    <Button onClick={() => setTab(3)} size={window.innerWidth < 576 && "sm"} className="w-100" variant="outline-secondary">
                                        Mächte
                                    </Button>
                                </Col>
                            </Row>
                            {
                                tabContent
                            }
                            <Row className="pb-3">
                                <Col className="d-flex justify-content-center">
                                    <Button onClick={props.redirectChat}>
                                        Nachricht
                                    </Button>
                                </Col>
                                <Col className="d-flex justify-content-center">
                                    <Button onClick={handleShow} variant="danger">
                                        Verlassen
                                    </Button>
                                </Col>
                            </Row>
                        </Col>
                    </Row>
                </Col>
            </Row>
            <Modal show={show} onHide={handleClose}>
                <Modal.Header closeButton>
                    <Modal.Title>Bindung lösen</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    Möchtest du diese Bindung wirklich aufgeben?
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={handleClose}>
                        Close
                    </Button>
                    <Button variant="danger" onClick={props.handleLeave}>
                        Ja, verlassen.
                    </Button>
                </Modal.Footer>
            </Modal>
        </div>
     );
}
 
export default Card;