import React from "react";
import Abi from "./ability";

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