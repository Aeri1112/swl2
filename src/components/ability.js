import React from "react";

import Modal from "./modal";

import Col from "react-bootstrap/Col";
import Media from "react-bootstrap/Media";
import Spinner from 'react-bootstrap/Spinner';

import AddCircleOutlineIcon from '@material-ui/icons/AddCircleOutline';

let text = require('./skills_text.json');

const Abi = (props) => {

        return (
            <>
                { 
                    text.map((content,index) => content.id === props.abi ? (
                        <Col sm="6" key={index}>
                            <ul className="list-unstyled">
                                <li className="media"> 
                                    <img className="align-self-center mr-3" src={require(`../images/${props.abi}.gif`) } alt="" />
                                    <Media.Body>
                                        <h5 className="mt-0 mb-1 d-flex justify-content-between">
                                            <span>
                                                <span data-toggle="modal" data-target={`#${props.abi}Modal`}>{content.head}</span>
                                                {
                                                    props.skillable === true ?
                                                    <AddCircleOutlineIcon
                                                        onClick={() => {props.onClick(content.id)}}
                                                        fontSize="small"
                                                        color="primary"
                                                    />
                                                    : null
                                                }
                                                {
                                                    props.loadTrainAbi === content.id ? <Spinner size="sm" animation="border"/> : null
                                                }
                                            </span>
                                            { props.basePoints > 0 && <span className="text-right"> {+props.basePoints + +props.tempBonus} </span>} 
                                        </h5>
                                        { props.basePoints > 0 &&
                                            <div>
                                                Basis: {props.basePoints }
                                                {props.tempBonus > 0 && " Items: " + props.tempBonus}
                                            </div> }
                                    </Media.Body>
                                </li>
                            </ul>
                        </Col>  
                        ) :
                        (
                            <></>
                        )
                    )
                }
                <Modal target={props.abi} />
            </>
        )
}

export default Abi;