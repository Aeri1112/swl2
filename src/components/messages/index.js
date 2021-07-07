import React, {useEffect, useState} from 'react';
import Sidebar from "./sidebar";
import Chat from "./chat";

const Messages = () => {

    const [chat, setChat] = useState();

    useEffect(() => {
        if(localStorage.getItem("pada")) {
            setChat(localStorage.getItem("pada"))
            localStorage.removeItem("pada")
        }
    },[])

    return ( 
    <div className="d-flex row">
        <Sidebar setChat={setChat}/>
        {
            chat && <Chat chat={chat} />
        }
    </div>
    
     );
}
 
export default Messages;