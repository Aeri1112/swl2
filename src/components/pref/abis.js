import React from 'react';
import { Col, Row, Badge } from 'react-bootstrap';

const Abis = (props) => {
    return ( 
        <div>
            <Row>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        CNS <Badge className="text-white bg-dark">{props.player.skills.cns + props.player.tempBonus.tmpcns}</Badge>
                    </div>
                </Col>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        AGI <Badge className="text-white bg-dark">{props.player.skills.agi + props.player.tempBonus.tmpagi}</Badge>
                    </div>
                </Col>
            </Row>
            <Row>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        TAC <Badge className="text-white bg-dark">{props.player.skills.tac + props.player.tempBonus.tmptac}</Badge>
                    </div>
                </Col>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        DEX <Badge className="text-white bg-dark">{props.player.skills.dex + props.player.tempBonus.tmpdex}</Badge>
                    </div>
                </Col>
            </Row>
            <Row>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        SPI <Badge className="text-white bg-dark">{props.player.skills.spi + props.player.tempBonus.tmpspi}</Badge>
                    </div>
                </Col>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        ITL <Badge className="text-white bg-dark">{props.player.skills.itl + props.player.tempBonus.tmpitl}</Badge>
                    </div>
                </Col>
            </Row>
            <Row>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        LSA <Badge className="text-white bg-dark">{props.player.skills.lsa + props.player.tempBonus.tmplsa}</Badge>
                    </div>
                </Col>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        LSD <Badge className="text-white bg-dark">{props.player.skills.lsd + props.player.tempBonus.tmplsd}</Badge>
                    </div>
                </Col>
            </Row>
        </div>
     );
}
 
export default Abis;