import React, {useState} from 'react';
import {GET} from "../../tools/fetch";
import {Link, useHistory } from "react-router-dom";

import Button from "react-bootstrap/Button";
import Modal from "react-bootstrap/Modal";

const Menu = (props) => {

    const history = useHistory();
    const [leave, setLeave] = useState(false);

    const initLeave = () => {
        setLeave(true);
    }

    const handleLeave = async () => {
        setLeave(false);
        const request = await GET("/alliances/leave");
        if(request) {
            history.push("/overview")
        }
    }

    return ( 
        <div className="row d-flex justify-content-center">
            <div className="m-1">
                <Link to="/alliance">Überblick</Link>
            </div>
            <div className="m-1">
                <Link to="/alliance/raid">Jagd</Link>
            </div>
            <div className="m-1">
                <Link to={`/alliance/view/${props.alliId}`}>Mitglieder</Link>
            </div>
            <div className="m-1">
                <Link to="/alliance/all">auflisten</Link>
            </div>
            <div className="m-1">
                <Link to="/alliance/research">Forschung</Link>
            </div>
            <div className="m-1">
                <Button onClick={initLeave} className="p-0 border-0" style={{verticalAlign:"top"}} variant="link">verlassen</Button>
            </div>
            {
                leave &&
                <Modal
                    show={leave}
                    onHide={() => {setLeave(false)}}
                    keyboard={false}
                >
                    <Modal.Header closeButton>
                    <Modal.Title>Leaving your Alliance</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        Are you sure?
                    </Modal.Body>
                    <Modal.Footer>
                    <Button variant="success" onClick={handleLeave}>
                        Yes
                    </Button>
                    <Button variant="danger" onClick={() => {setLeave(false)}}>No</Button>
                    </Modal.Footer>
                </Modal>
            }
        </div>
     );
}
 
export default Menu;