import React from "react";
import { connect } from "react-redux";
import { fetchAllianceData } from "../redux/actions/allianceActions";


const mapStateToProps = state => {
    
    return {
        alli: state.alliance.data
    };
  }
  
  const mapDispatchToProps = (dispatch, ownProps) => {

      const id = parseInt(ownProps.id);

    return {
    dispatch_props: () => dispatch(fetchAllianceData(id))
  }
}

class Alliance extends React.Component{

    componentDidMount() {
    this.props.dispatch_props();
    console.log(this.props);
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