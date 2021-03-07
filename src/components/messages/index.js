import React, {useState} from 'react';
import Sidebar from "./sidebar";
import Chat from "./chat";

const Messages = () => {

    const [chat, setChat] = useState();

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