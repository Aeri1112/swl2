import React from "react";
import axios from "axios";

import Bars from "./bars";

class UsingAxios extends React.Component {
    state = {
      side: [],
      char: [],
      skills: []
    }

    componentDidMount() {
        let body = {
            userId: 4
            };
        axios.post(`http://localhost/react/my-app/api/character/overview.php`, body)
      .then(res => {
        const side = res.data[0];
        const char = res.data[1];
        const skills = res.data[2];
        this.setState({ side });
        this.setState({ char });
        this.setState({ skills });
      })
    }

    render() {

        return (

        <div className="row">
            <div className="col-6">
                <div>
                    Name: { this.state.char.username }
                </div>
                <div>
                    Spezies: { this.state.char.species }
                </div>
                <div>
                    Alter: { this.state.char.age }
                </div>
                <div>
                    Größe: { this.state.char.size }
                </div>
                <div>
                    Heimatwelt: { this.state.char.homeworld }
                </div>
            </div> 
            <div className="col-6">
                <div>
                    Level: { this.state.skills.level }
                </div>
                <div>
                    Allianz: { this.state.char.alliance }
                </div>
                <div>
                    Rang: { this.state.char.rank }
                </div>
                <div>
                    Meister: { this.state.char.masterid }
                </div>
            </div>
            <Bars type={"Ausrichtung"} width={"50%"} bg={""}/>
            <Bars type={"Health"} width={ this.state.skills.health_width + "%"} bg={"bg-danger"}/>
            <Bars type={"Mana"} width={this.state.skills.mana_width + "%"} bg={"bg-primary"}/>
            <Bars type={"Energy"} width={this.state.skills.energy_width + "%"} bg={"bg-warning"}/>
            <Bars type={"Experience"} width={"10%"} bg={"bg-success"}/>
        </div>
        );
    }
}

export default UsingAxios;