import React from "react";

class Card extends React.Component {

    render() {
        return(
        <div>
            {this.props.show === true ?
                <img width={150} src={`./asset/${this.props.value}_of_${this.props.suit}.png`} alt="" />
                : <img width={150} src={`./asset/back.png`} alt="" />}
            
        </div>
        );
    }
}

export default Card;