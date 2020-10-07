import React from "react";
import { connect } from "react-redux";
import { fetchUserChar } from "../redux/actions/userActions";
import { fetchUserData } from "../redux/actions/userActions";
import { fetchUserSide } from "../redux/actions/userActions";
import { fetchMaster } from "../redux/actions/userActions";

import Alliance from "./alliance";
import Rank from "./ranks";
import Bars from "./bars";
import Row from "react-bootstrap/Row";

const mapStateToProps = state => {
    return {
        skills: state.skills.skills,
        char: state.skills.char,
        side: state.skills.side,
        master: state.skills.master
    };
  }
  const dispatchProps = dispatch => ({

    fetchData: () => dispatch(fetchUserData()),
    fetchChar: () => dispatch(fetchUserChar()),
    fetchSide: () => dispatch(fetchUserSide()),
    fetchMaster: () => dispatch(fetchMaster())
})

class Overview extends React.Component {

    componentDidMount () {
        this.props.fetchData();
        this.props.fetchChar();
        this.props.fetchSide();     
        this.props.fetchMaster();
    }
    
    render() {
        const { char, skills, side, master } = this.props;
        console.log(master);
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
                    Alter: { char.age }
                </div>
                <div>
                    Größe: { char.size } cm
                </div>
                <div>
                    Heimatwelt: { char.homeworld }
                </div>
            </div> 
            <div className="col-6">
                <div>
                    Level: { skills.level }
                </div>
                <div>
                    Allianz: {char.alliance  ?  <Alliance id={char.alliance}/> : null }
                </div>
                <div>
                    Rang: { char.rank ? <Rank rank={char.rank} side={skills.side}/> : null }
                </div>
                <div>
                    { master.skills ? master.skills.level > 75 ? (
                        <>Meister: {master.char.username} ({master.skills.level})</>
                        ) : (
                        <>Schüler: {master.char.username} ({master.skills.level})</>
                        ) : (
                        <></>
                        ) 
                    }
                </div>
            </div>
        </Row>

            <Bars type={"Ausrichtung"} width={"100%"} data={side.side} perc={side.perc} white={side.white_begin} bg={""}/>
            <Bars type={"Health"} width={ skills.health_width + "%"} data={char.health} bg={"bg-danger"}/>
            <Bars type={"Mana"} width={ skills.mana_width + "%"} data={char.mana} bg={"bg-primary"}/>
            <Bars type={"Energy"} width={ skills.energy_width + "%"} data={char.energy} bg={"bg-success"}/>
            <Bars type={"Experience"} width={skills.level_width + "%"} data={skills.xp} bg={"bg-warning"}/>

        </>
        );
    }
}

export default connect(mapStateToProps, dispatchProps)(Overview);