import React from "react";
import Abi from "./ability";

import Container from "react-bootstrap/Container";
import Col from "react-bootstrap/Col";
import Row from "react-bootstrap/Row";

class Abis extends React.Component {
   
    render() {

        return (
                <Row>
                    <Abi abi="cns"/>
                    <Abi abi="agi"/>
                    <Abi abi="spi"/>
                    <Abi abi="itl"/>
                    <Abi abi="tac"/>
                    <Abi abi="dex"/>
                    <Abi abi="lsa"/>
                    <Abi abi="lsd"/>
                </Row>
        );
    }
}
  
  export default Abis;