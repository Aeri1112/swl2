import React, { useEffect, useState } from 'react';
import {firestore} from "../../tools/firebase";
import moment from "moment";
import "./sidebarchat.css";

const SidebarChat = ({setChat, id, name }) => {

    const [message, setMessage] = useState([]);

    const createChat = () => {
        setChat(id)
    }

    useEffect(() => {
        if(id) {
            firestore.collection("chats").doc(id).collection("messages").limit(1).orderBy("dt","desc").onSnapshot((snapshot) => setMessage(snapshot.docs.map((doc) => doc.data())))
        }
    },[id])

    return ( 
        <div onClick={createChat} className="sidebarchat">
            <div className="sidebarChatInfo">
                <h2>{name}</h2>
                <em>{message[0]?.message}</em>{" "}
                <small className="text-gray">{message[0]?.dt && message[0].dt.seconds !== null && moment.unix(message[0]?.dt.seconds).format("DD.MM.YYYY HH:mm:ss")}</small>
            </div>
        </div>
     );
}
 
export default SidebarChat;