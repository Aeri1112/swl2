import React from "react";

class Bars extends React.Component {

    render ()
    {
        let img;
        let side;
        let perc;
        switch (this.props.type) {
            case "Health":
                img = <img src={require(`../images/heart.png`) } alt=""/>;
                perc = this.props.width;
                break;
            case "Mana":
                img = <img src={require(`../images/poison.png`) } alt=""/>;
                perc = this.props.width;
                break;
            case "Energy":
                img = <img src={require(`../images/energy.png`) } alt=""/>;
                perc = this.props.width;
                break;
            case "Experience":
                img = <img src={require(`../images/growth.png`) } alt=""/>;
                perc = this.props.width;
                break;
            case "Ausrichtung":
                img = <img src={require(`../images/transfer.png`) } alt=""/>;
                side = `linear-gradient(to right, #dc3545 ${this.props.perc + 40}%, white ${this.props.white}%, #28a745 ${60-this.props.perc}%)`;
                perc = this.props.perc + "%";
                break;
            default:
                break;
        }
        return(
            <div className="container col-md-6 p-0 progress-group">
                <div className="progress-group-header align-items-end">
                    <i className="cil-globe far progress-group-icon">
                     {img}
                    </i> 
                    <div>{this.props.type}</div>
                    <div className="ml-auto font-weight-bold mr-2">{this.props.data}</div>
                    <div className="text-muted small">({perc})</div>
                </div>
                <div className="progress-group-bars">
                    <div style={{height: "4px"}} className="progress">
                    <div className={`progress-bar ${this.props.bg}`} role="progressbar" style={{width: this.props.width, background: side}} aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                </div>
        );
    }
}

export default Bars;