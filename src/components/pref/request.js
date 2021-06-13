import React from 'react';
import { Col, Row, Button } from 'react-bootstrap';

const Request = (props) => {
    const player = props.player;
    const forfeit = props.forfeit;
    return ( 
        <div key={player.char.userid} className="col-md-6">
            <div className="card user-card">
                <div className="card-block">
                    <div className="user-image">
                        <img src="https://bootdey.com/img/Content/avatar/avatar7.png" className="img-radius" alt="User-Profile-Image"/>
                    </div>
                    <h6 className="f-w-600 m-t-25 m-b-10">{player.char.username}</h6>
                    <p className="text-muted"> {player.char.species} | {player.char.sex === "m" ? "Male" : "Female"} | {player.char.homeworld}</p>
                    <hr/>
                    <p className="text-muted m-t-15">Activity Level: {player.activePoints}%</p>
                    <ul className="list-unstyled activity-leval">
                        <li className={player.activePoints > 20 ? "active" : null}></li>
                        <li className={player.activePoints > 40 ? "active" : null}></li>
                        <li className={player.activePoints > 60 ? "active" : null}></li>
                        <li className={player.activePoints > 80 ? "active" : null}></li>
                        <li className={player.activePoints === 100 ? "active" : null}></li>
                    </ul>
                    <div className="bg-c-blue counter-block m-t-10 p-20">
                        <div className="row">
                            <div className="col-4 p-1">
                                <div>Level</div>
                                <p>{player.skills.level}</p>
                            </div>
                            <div className="col-4 p-1">
                                <div>Ausrichtung</div>
                                <p>{player.skills.side < 0 ? <span className="text-danger">dunkel</span> : player.skills.side > 0 ? <span className="text-success">hell</span> : "neutral"}</p>
                            </div>
                            <div className="col-4 p-1">
                                <div>Allianz</div>
                                <p>{player.allianceData.short}</p>
                            </div>
                        </div>
                    </div>
                    <p className="m-t-15 text-muted">{player.text}</p>
                    <hr/>
                    <div className="row justify-content-center user-social-link">
                        {
                            player.reqToMe !== null && player.reqToMe.status === 1 ?
                                <Row>
                                    <Col md="auto">
                                        <Button variant="success">Accept</Button>
                                    </Col>
                                    <Col md="auto">
                                        <Button variant="danger" onClick={() => forfeit(player.reqToMe.id)}>Forfeit</Button>
                                    </Col>
                                </Row>
                            :
                                player.reqToMe !== null && player.reqToMe.status === 2 ?
                                <Row>
                                    <Col md="auto">
                                        <Button variant="danger" disabled>abgelehnt</Button>
                                    </Col>
                                </Row>
                            :
                                <Row>
                                    <Col md="auto">
                                        <Button>Anfragen</Button>
                                    </Col>
                                </Row>
                        }
                    </div>
                </div>
            </div>
        </div>
     );
}
 
export default Request;