import React from "react";

import Force from "./force";

import Row from "react-bootstrap/Row";

class Forces extends React.Component {
   
    render() {

        return (
                <Row>
                    <Force f="fspee"/>
                    <Force f="fjump"/>
                    <Force f="fpush"/>
                    <Force f="fpull"/>
                    <Force f="fseei"/>
                    <Force f="fsabe"/>

                    <Force f="fpers"/>
                    <Force f="fproj"/>
                    <Force f="fblin"/>
                    <Force f="fconf"/>
                    <Force f="fheal"/>
                    <Force f="fteam"/>
                    <Force f="fprot"/>
                    <Force f="fabso"/>

                    <Force f="fthro"/>
                    <Force f="frage"/>
                    <Force f="fgrip"/>
                    <Force f="fdrai"/>
                    <Force f="fthun"/>
                    <Force f="fchai"/>
                    <Force f="fdest"/>
                    <Force f="fdead"/>

                    <Force f="frvtl"/>
                    <Force f="ftnrg"/>
                </Row>
        );
    }
}
  
  export default Forces;