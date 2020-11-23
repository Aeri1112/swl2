import React from "react";

import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";

const BetInput = (props) => {
   
    return (
        <Row>
            <Col xs="auto">
            <form>
                <input onChange={(event) => props.onChange(event)} value={props.value} size="sm" name="bet" type="text" required />
            </form>
            </Col>
        </Row>
    );
}

export default BetInput;