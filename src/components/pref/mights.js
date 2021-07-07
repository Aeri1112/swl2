import React from 'react';
import { Col, Row, Badge } from 'react-bootstrap';

const Mights = (props) => {
    return ( 
        <div>
            <Row>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fspee <Badge className="text-white bg-dark">{props.player.skills.fspee + props.player.tempBonusForces.tmpfspee}</Badge>
                    </div>
                </Col>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fjump <Badge className="text-white bg-dark">{props.player.skills.fjump + props.player.tempBonusForces.tmpfjump}</Badge>
                    </div>
                </Col>
            </Row>
            <Row>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fpush <Badge className="text-white bg-dark">{props.player.skills.fpush + props.player.tempBonusForces.tmpfpush}</Badge>
                    </div>
                </Col>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fpull <Badge className="text-white bg-dark">{props.player.skills.fpull + props.player.tempBonusForces.tmpfpull}</Badge>
                    </div>
                </Col>
            </Row>
            <Row>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fseei <Badge className="text-white bg-dark">{props.player.skills.fseei + props.player.tempBonusForces.tmpfseei}</Badge>
                    </div>
                </Col>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fsabe <Badge className="text-white bg-dark">{props.player.skills.fsabe + props.player.tempBonusForces.tmpfsabe}</Badge>
                    </div>
                </Col>
            </Row>
            <hr/>
            <Row>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fpers <Badge className="text-white bg-dark">{props.player.skills.fpers + props.player.tempBonusForces.tmpfpers}</Badge>
                    </div>
                </Col>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fproj <Badge className="text-white bg-dark">{props.player.skills.fproj + props.player.tempBonusForces.tmpfproj}</Badge>
                    </div>
                </Col>
            </Row>
            <Row>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fblin <Badge className="text-white bg-dark">{props.player.skills.fblin + props.player.tempBonusForces.tmpfblin}</Badge>
                    </div>
                </Col>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fconf <Badge className="text-white bg-dark">{props.player.skills.fconf + props.player.tempBonusForces.tmpfconf}</Badge>
                    </div>
                </Col>
            </Row>
            <Row>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fheal <Badge className="text-white bg-dark">{props.player.skills.fheal + props.player.tempBonusForces.tmpfheal}</Badge>
                    </div>
                </Col>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fteam <Badge className="text-white bg-dark">{props.player.skills.fteam + props.player.tempBonusForces.tmpfteam}</Badge>
                    </div>
                </Col>
            </Row>
            <Row>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fprot <Badge className="text-white bg-dark">{props.player.skills.fprot + props.player.tempBonusForces.tmpfprot}</Badge>
                    </div>
                </Col>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fabso <Badge className="text-white bg-dark">{props.player.skills.fabso + props.player.tempBonusForces.tmpfabso}</Badge>
                    </div>
                </Col>
            </Row>
            <hr/>
            <Row>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fthro <Badge className="text-white bg-dark">{props.player.skills.fthro + props.player.tempBonusForces.tmpfthro}</Badge>
                    </div>
                </Col>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Frage <Badge className="text-white bg-dark">{props.player.skills.frage + props.player.tempBonusForces.tmpfrage}</Badge>
                    </div>
                </Col>
            </Row>
            <Row>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fgrip <Badge className="text-white bg-dark">{props.player.skills.fgrip + props.player.tempBonusForces.tmpfgrip}</Badge>
                    </div>
                </Col>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fdrai <Badge className="text-white bg-dark">{props.player.skills.frage + props.player.tempBonusForces.tmpfrage}</Badge>
                    </div>
                </Col>
            </Row>
            <Row>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fthun <Badge className="text-white bg-dark">{props.player.skills.fthun + props.player.tempBonusForces.tmpfthun}</Badge>
                    </div>
                </Col>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fchai <Badge className="text-white bg-dark">{props.player.skills.fchai + props.player.tempBonusForces.tmpfchai}</Badge>
                    </div>
                </Col>
            </Row>
            <Row>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fdead <Badge className="text-white bg-dark">{props.player.skills.fdead + props.player.tempBonusForces.tmpfdead}</Badge>
                    </div>
                </Col>
                <Col xs="6" className="d-flex justify-content-center">
                    <div>
                        Fdest <Badge className="text-white bg-dark">{props.player.skills.fdest + props.player.tempBonusForces.tmpfdest}</Badge>
                    </div>
                </Col>
            </Row>
        </div>
     );
}
 
export default Mights;