import React from "react";
import { connect } from "react-redux";
import { fetchUserData } from "../redux/actions/userActions";
import Modal from "./modal";

import Col from "react-bootstrap/Col";
import Media from "react-bootstrap/Media";

let text = require('./skills_text.json');

const mapStateToProps = state => {
    return {
        skills: state.skills.skills
    };
  }

class Abi extends React.Component{

    componentDidMount () {
        this.props.dispatch(fetchUserData())
    }

    render () {
        const { skills } = this.props;
        const abi = this.props.abi;

        return (
            <>
            {
                text.map(content => content.id === abi ? (
                    <Col sm="6">
                        <ul className="list-unstyled">
                            <li className="media"> 
                                <img className="align-self-center mr-3" src={require(`../images/${abi}.gif`) } alt="" />
                                <Media.Body>
                                    <h5 className="mt-0 mb-1" data-toggle="modal" data-target={`#${abi}Modal`}>
                                        {content.head} {skills[abi]}
                                    </h5>
                                    Basis: {skills[abi]} Items: 0
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