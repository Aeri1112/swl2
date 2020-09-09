import React from "react";
import { connect } from "react-redux";
import { fetchUserChar } from "../redux/actions/userActions";
import { fetchUserData } from "../redux/actions/userActions";

import Bars from "./bars";
import Row from "react-bootstrap/Row";

const mapStateToProps = state => {
    return {
        skills: state.skills.skills,
        char: state.char.char
    };
  }
  const dispatchProps = dispatch => ({

    fetchData: () => dispatch(fetchUserData()),
    fetchChar: () => dispatch(fetchUserChar())

})

class Overview extends React.Component {

    componentDidMount () {
        this.props.fetchData();
        this.props.fetchChar();
    }

    render() {
        const { char, skills } = this.props;
        console.log(this.props);

        return (
        <>
        <Row>
            <div className="col-6">
                <div>
                    Name: { char.username }
                </div>
                <div>
                    Spezies: { char.species }
                </div>
                <div>
                    Alter: {  }
                </div>
                <div>
                    Größe: {  } cm
                </div>
                <div>
                    Heimatwelt: {  }
                </div>
            </div> 
            <div className="col-6">
                <div>
                    Level: {  }
                </div>
                <div>
                    Allianz: {  }
                </div>
                <div>
                    Rang: {  }
                </div>
                <div>
                    Meister: {  }
                </div>
            </div>
        </Row>

            <Bars type={"Ausrichtung"} width={"50%"} bg={""}/>
            <Bars type={"Health"} width={ skills.health_width + "%"} bg={"bg-danger"}/>
            <Bars type={"Mana"} width={ skills.mana_width + "%"} bg={"bg-primary"}/>
            <Bars type={"Energy"} width={ skills.energy_width + "%"} bg={"bg-warning"}/>
            <Bars type={"Experience"} width={"10%"} bg={"bg-success"}/>

        </>
        );
    }
}

export default connect(mapStateToProps, dispatchProps)(Overview);