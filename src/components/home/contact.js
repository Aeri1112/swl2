import React from 'react';
import {Modal, ModalBody } from "react-bootstrap";
import ModalHeader from 'react-bootstrap/esm/ModalHeader';

const Con = (props) => {
    return ( 
            <Modal animation={false} show={props.show} onHide={() => props.handleCon()}>
                <ModalHeader>
                    Kontakt
                </ModalHeader>
                <ModalBody>
                    wir haben einen Discord-Server:<br/>
                    <a href="https://discord.gg/2N3Bpym">auf zu Discord</a> <br/>
                    mich persönlich kann man auf Discord (Aeri#3877) oder per Mail erreichen:
                    aeri371@googlemail.com
                </ModalBody>
            </Modal>
     );
}
 
export default Con;