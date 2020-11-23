import React from "react";
import Card from "./Card";

import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";

const Hand = (props) => {

    let softScore;
    if(props.softCount) {
        softScore = (props.count - 10);
    }
    return (<>
        {
            <div className="h4 d-inline">
                Your Hand:
                {softScore ? " " + softScore + " /" : null}
                {" " + props.count}    
            </div>}
        {
            props.status !== null ? 
                <div className="h4 d-inline">
                    {" => " + props.status}
                </div>
            : null
        } 
        <Row>
        {props.cards.map((card, index) => 
            <Col md="auto" key={index}>
                <Card suit={card[0]} value={card[1]} show={true}/> 
            </Col>)}
        </Row>
    </>);
}

export default Hand;