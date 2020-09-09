import React from "react";

class Bars extends React.Component {

    render ()
    {
        return(
            <div className="container col-md-6 p-0 progress-group">
                <div className="progress-group-header align-items-end">
                    <i className="cil-globe far progress-group-icon">
                    
                    </i> 
                    <div>{this.props.type}</div>
                    <div className="ml-auto font-weight-bold mr-2"></div>
                    <div className="text-muted small"></div>
                </div>
                <div className="progress-group-bars">
                    <div style={{height: "4px"}} className="progress">
                    <div className={`progress-bar ${this.props.bg}`} role="progressbar" style={{width: this.props.width}} aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                </div>
        );
    }
}

export default Bars;