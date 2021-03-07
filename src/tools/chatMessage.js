import React from 'react';
import {useSelector} from "react-redux";

export default function ChatMessage(props) {

    const username = useSelector(state => state.user.username);

    const classes = props.sender === username ? "message message-personal" : "message";

    return (
            <div className={classes}>
                <div>
                    {
                        props.sender !== username ?
                            <u>{props.sender + ":"}</u>
                        :
                            null
                    }
                    {
                        isURL(props.message) ?
                            <div><a href={props.message} style={{color:"#99ddff"}}>{props.message}</a></div>
                        : 
                            <div>{props.message}</div>
                    }
                </div>
                <div className="timestamp alignRight">
                    {props.date}
                </div>
            </div>
    )
  }
  
function isURL(str) {
    const pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
        '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
    return !!pattern.test(str);
}