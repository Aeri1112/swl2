import React from "react";

let skills = require('./skills_text.json');

class Modal extends React.Component{

    render () {
        const target = this.props.target;
        
        return (
            <>
            {
                skills.map(content => content.id === target ? (
                    <div className="modal fade" id={`${target}Modal`} tabIndex="-1" role="dialog" aria-labelledby={`${target}ModalLabel`} aria-hidden="true">
                        <div className="modal-dialog" role="document">
                            <div className="modal-content">
                                <div className="modal-header">
                                    <h5 className="modal-title" id={`${target}ModalLabel`}>{content.head}</h5>
                                </div>
                                <div className="modal-body">
                                    {content.body}
                                </div>
                                <div className="modal-footer">
                                    <button type="button" className="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    ) : (
                        <></>
                    )
            )}
            </>
        )
    }
}

export default Modal;