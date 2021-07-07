import React from 'react';
import { Col, Row } from "react-bootstrap"

const Allgemein = (props) => {
    return ( 
        <div>
            <Row>
                <Col>
                    letzte Aktivität
                </Col>
                <Col className="text-right">
                    {props.player.account}
                </Col>
            </Row>
            <Row>
                <Col>
                    Arena wins
                </Col>
                <Col className="text-right">
                    {props.player.stats.arenaPer} %
                </Col>
            </Row>
            <Row>
                <Col>
                    NPC wins
                </Col>
                <Col className="text-right">
                    {props.player.stats.npcPer} %
                </Col>
            </Row>
            <Row>
                <Col>
                    Allianz
                </Col>
                <Col className="text-right">
                    {
                        props.player.alliance.length !== 0 ?
                            props.player.alliance.name + " (" + props.player.alliance.short + ")"
                        :
                            "keine"
                    }
                </Col>
            </Row>
            <Row>
                <Col>
                    Cash
                </Col>
                <Col className="text-right">
                    {props.player.cash} Cr.
                </Col>
            </Row>
        </div>
     );
}
 
export default Allgemein;