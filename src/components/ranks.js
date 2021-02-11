import React from "react";
import { connect } from "react-redux";

const mapStatetoProps = state => {
    
    return {
        ranks: state.ranks.rank
    }
}
class Ranks extends React.Component {

    render() {
        let side;
        if (this.props.side > 0) {side = "1"} else {side = "-1"}
        if (this.props.rank >= 0 && this.props.rank <= 6) {side = "0"}
        const ranks = this.props;

        return (
            
            ranks.ranks.map(content => +content.id === this.props.rank && content.side === side ?
                content.rank : null)
        )
    }
}

export default connect(mapStatetoProps)(Ranks);