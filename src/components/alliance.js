import React from "react";
import { connect } from "react-redux";
import { fetchAllianceData } from "../redux/actions/allianceActions";


const mapStateToProps = state => {
    
    return {
        alli: state.alliance.data
    };
  }
  
  const mapDispatchToProps = (dispatch) => {

    return {
    dispatch_props: (id) => dispatch(fetchAllianceData(id))
  }
}

class Alliance extends React.Component{

    componentDidMount() {
    this.props.dispatch_props(this.props.id);
    }

    render () {
            const { alli } = this.props;

        return (
            <>
            {alli.name}
            </>
        )
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(Alliance);