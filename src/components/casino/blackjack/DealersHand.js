import React from "react";
import Card from "./Card";

import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";

const DealersHand = (props) => {

    let firstCard = 0;

    if(props.cards[0][1] === "jack" || props.cards[0][1] === "queen" || props.cards[0][1] === "king") {
        firstCard = 10;
    }
    else if (props.cards[0][1] === "ace") {
        firstCard = 11;
    }
    else {
        firstCard = props.cards[0][1];
    }
    return (<>
        {<div className="h4">Dealers Hand: {props.count === 0 ? firstCard : props.count} </div>}
        <Row>
            {props.cards.map((card, index) => 
                <Col md="auto" key={index}>
                    <Card suit={card[0]} value={card[1]} show={index === 1 && props.show === false ? false : true} /> 
                </Col>)}
        </Row>
    </>);
}

export default DealersHand;