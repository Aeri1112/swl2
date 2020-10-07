import React from "react";
import { connect } from "react-redux";

const mapStateToProps = state => {
    return {
        skills: state.skills.skills,
        char: state.skills.char,
        side: state.skills.side,
        master: state.skills.master
    };
  }
  const dispatchProps = dispatch => ({

    fetchData: () => fetch('http://localhost/new_jtg/api/character/inventory?id=weapons')
                        .then(response => response.json())
                        .then(response => {
                        dispatch({type: "FETCH_STATS", payload: response[2]});
    })
})

class Inventory extends React.Component {

    componentDidMount () {
        this.props.fetchData();
    }

    render() {
        return (
            <>Test</>
        );
    }
}

export default connect(mapStateToProps, dispatchProps)(Inventory);