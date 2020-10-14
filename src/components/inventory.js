import React from "react";
import { ResponsiveEmbed } from "react-bootstrap";
import { connect } from "react-redux";
import {GET} from "../tools/fetch";

class Inventory extends React.Component {

    state = {
        loading: true
    }
    componentDidMount = async () => {
        try {
            const response = await GET('/character/inventory?id=weapons')
                        if(response) {
                            this.props.onWeapons(response);
                            this.setState({loading: false})
                        }
        }
        catch (e) {
            
        }
    }

    render() {

        let showInv = "Loading...";

        if(this.state.loading === false) {

            showInv = this.props.inv.items.map(item => {
                return (
                        <li>
                            {item.name}
                        </li>                    
                );
            });
        }
        return (
            <>
            <ul>
                {showInv}
            </ul>
            </>
        );
    }
}

const mapStateToProps = state => {
    return {
        inv: state.skills.inv
    }
}

const mapDispatchToProps = dispatch => {
    return {
        onWeapons: (data) => dispatch({type: "FETCH_INV", payload: data})
    }
}
export default connect(mapStateToProps, mapDispatchToProps)(Inventory);