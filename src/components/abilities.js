import React from "react";
import { connect } from "react-redux";
import { fetchUserData } from "../redux/actions/userActions"

const mapStateToProps = state => {
    return {
        skills: state.reducer.skills
    };
  }

class Abis extends React.Component {
    componentDidMount () {
        this.props.dispatch(fetchUserData())
    }
   
    render() {
        const { skills } = this.props;

        return (
        <div>
            {skills.cns}
        </div>
        );
    }
}
  
  export default connect(mapStateToProps)(Abis)