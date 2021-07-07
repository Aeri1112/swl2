import React from "react";
import { Link } from 'react-router-dom';
import { Col, Row } from "react-bootstrap";

const Casino = () => {

    return (
        <Row>
            <Col md="6" xs="4">
                <Link to="/bj">
                    <img className="w-100 h-100" src={require(`../../images/bj.png`)} alt="" />
                </Link>
            </Col>
            <Col md="6" xs="4">
                <Link to="/roulette">
                    <img className="w-100 h-100" src={require(`../../images/roulette.jpg`)} alt="" />
                </Link>
            </Col>
        </Row>
    );
}

export default Casino;