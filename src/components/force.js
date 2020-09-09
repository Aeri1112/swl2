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

class Force extends React.Component{

    componentDidMount () {
        this.props.dispatch(fetchUserData())
    }

    render () {
        const { skills } = this.props;
        const force = this.props.f;
        let img;
        let margin;

        if ((force === "fpers" || force === "fproj" || force === "fblin" || force === "fconf"
        || force === "fteam" || force === "fheal" || force === "fabso" || force === "fprot") && (skills.fdead > 0 || skills.fdest > 0)) {
            img = <img className="align-self-center mr-3" src={require(`../images/${force}_g.gif`) } alt="" />;
        }
        else if ((force === "fblin" || force === "fconf" || force === "fteam" || force === "fheal"
        || force === "fabso" || force === "fprot") && (skills.fthun > 0 || skills.fchai > 0)) {
            img = <img className="align-self-center mr-3" src={require(`../images/${force}_g.gif`) } alt="" />;
        }
        else if ((force === "fteam" || force === "fheal"
        || force === "fabso" || force === "fprot") && (skills.fgrip > 0 || skills.fdrai > 0)) {
            img = <img className="align-self-center mr-3" src={require(`../images/${force}_g.gif`) } alt="" />;
        }
        else if ((force === "fabso" || force === "fprot") && (skills.fthro > 0 || skills.frage > 0)) {
            img = <img className="align-self-center mr-3" src={require(`../images/${force}_g.gif`) } alt="" />;
        }

        else if ((force === "fthro" || force === "fgrip" || force === "fdrai" || force === "frage"
        || force === "fthun" || force === "fchai" || force === "fdest" || force === "fdead") && (skills.fabso > 0 || skills.fprot > 0)) {
            img = <img className="align-self-center mr-3" src={require(`../images/${force}_g.gif`) } alt="" />;
        }
        else if ((force === "fgrip" || force === "fdrai" || force === "fthun" || force === "fchai"
        || force === "fdest" || force === "fdead") && (skills.fteam > 0 || skills.fheal > 0)) {
            img = <img className="align-self-center mr-3" src={require(`../images/${force}_g.gif`) } alt="" />;
        }
        else if ((force === "fthun" || force === "fchai"
        || force === "fdest" || force === "fdead") && (skills.fconf > 0 || skills.fblin > 0)) {
            img = <img className="align-self-center mr-3" src={require(`../images/${force}_g.gif`) } alt="" />;
        }
        else if ((force === "fdest" || force === "fdead") && (skills.fproj > 0 || skills.fpers > 0)) {
            img = <img className="align-self-center mr-3" src={require(`../images/${force}_g.gif`) } alt="" />;
        }
        else {
            img = <img className="align-self-center mr-3" src={require(`../images/${force}.gif`) } alt="" />;
        }

        //margin bottom für abstand zwischen neutral light und dark
        if(force === "fsabe" || force === "fabso" || force === "fdead") { margin = "mb-3"; }
        else { margin = ""; }


        return (
            <>
            {
                text.map(content => content.id === force ? (
                    <Col sm="6">
                        <ul className={`list-unstyled mb-0 ${margin}`}>
                            <li className="media"> 
                                {img}
                                <Media.Body>
                                    <h5 className="mt-0 mb-1 d-flex justify-content-between" data-toggle="modal" data-target={`#${force}Modal`}>
                                        {content.head} { skills[force] > 0 && <span className="text-right"> {skills[force]} </span>} 
                                    </h5>
                                    { skills[force] > 0 &&
                                        <div>Basis: {skills[force] } </div> }
                                </Media.Body>
                            </li>
                        </ul>
                    </Col>
                    
                    ) : (
                        <></>
                    )
            )}
            <Modal target={force} />
            </>
        )
    }
}

export default connect(mapStateToProps)(Force);