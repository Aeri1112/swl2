import React from "react";
import { connect } from "react-redux";
import { fetchUserData } from "../redux/actions/userActions";
import Modal from "./modal";
import {POST} from "../tools/fetch";

import Col from "react-bootstrap/Col";
import Media from "react-bootstrap/Media";
import Spinner from 'react-bootstrap/Spinner';

import AddCircleOutlineIcon from '@material-ui/icons/AddCircleOutline';

let text = require('./skills_text.json');

const mapStateToProps = state => {
    return {
        skills: state.skills.skills
    };
  }

class Abi extends React.Component{

    constructor(props) {
        super(props);
        this.state = {
            loadTrainAbi: false
        };
    } 

    componentDidMount () {
        this.props.dispatch(fetchUserData())
    }

    async skillAbi (abi) {
        try {
            await POST('/character/abilities',{train:abi})
        }
        finally {
            this.props.dispatch(fetchUserData())
            this.state.loadTrainAbi = false;
        }

    }

    render () {
        const { skills } = this.props;
        const abi = this.props.abi;

        let skillable = false;
        if(skills.rsp > 0) {skillable = true;}

        return (
            <>
            {
                text.map(content => content.id === abi ? (
                    <Col sm="6">
                        <ul className="list-unstyled">
                            <li className="media"> 
                                <img className="align-self-center mr-3" src={require(`../images/${abi}.gif`) } alt="" />
                                <Media.Body>
                                    <h5 className="mt-0 mb-1 d-flex justify-content-between">
                                        <span>
                                            <span data-toggle="modal" data-target={`#${abi}Modal`}>{content.head}</span>
                                            {
                                                skillable === true ?
                                                <AddCircleOutlineIcon onClick={() => {this.skillAbi(content.id); this.setState({loadTrainAbi:true})}} fontSize="small" color="primary" />
                                                : null
                                            }
                                            {
                                                this.state.loadTrainAbi === true ? <Spinner size="sm" animation="border"/> : null
                                            }
                                        </span>
                                         { skills[abi] > 0 && <span className="text-right"> {skills[abi]} </span>} 
                                    </h5>
                                    { skills[abi] > 0 &&
                                        <div>
                                            Basis: {skills[abi] }
                                        </div> }
                                </Media.Body>
                            </li>
                        </ul>
                    </Col>
                    
                    ) : (
                        <></>
                    )
            )}
            <Modal target={abi} />
            </>
        )
    }
}

export default connect(mapStateToProps)(Abi);