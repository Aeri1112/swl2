import React, {useEffect, useState, useRef} from 'react';
import {useSelector} from "react-redux";
import ChatMessage from "../../tools/chatMessage";
import "./chat.css";
import {firestore} from "../../tools/firebase";
import moment from "moment";
import firebase from "firebase";
import ReactScrollableFeed from "react-scrollable-feed";

const Chat = (props) => {

    const dummy = useRef(null);

    const [text, setText] = useState("");
    const [messages, setMessages] = useState([]);

    const [roomName, setRoomName] = useState();

    const username = useSelector(state => state.user.username);

    const scrollToBottom = () => {
        dummy.current.scrollTop = dummy.current.scrollHeight;
      }

    const handleTextChange = (e) => {
        setText(e.target.value)
    }

    const onAddMessage = (event) => {
        event.preventDefault();

        firestore.collection("chats").doc(props.chat).collection("messages").add({
            dt: firebase.firestore.FieldValue.serverTimestamp(),
            sender: username,
            message: text,
            seen: 0
        });

        firestore.collection("chats").doc(props.chat).set({
            lastMessage:firebase.firestore.FieldValue.serverTimestamp()
        }, {merge: true})
        
        setText("");
    }

    useEffect(() => {
        scrollToBottom();
    },[messages])

    useEffect(() => {

        const ref = firestore.collection("chats").doc(props.chat).collection("messages").orderBy("dt","asc");
        const data = ref.onSnapshot(snapshot => setMessages(snapshot.docs.map(message => ({
            id: message.id,
            data: message.data()
        }))));
        const name = firestore.collection("chats").doc(props.chat).onSnapshot((snapshot) => {setRoomName(username === snapshot.data().name[0] ? snapshot.data().name[1] : snapshot.data().name[0])})
    
        return () => {
            data();
            name();
        }

    },[props.chat]);

    return ( 
        <div className="col-md-8">
            <div className="chat">
                <div className="chatHeader">
                    <div className="chatHeaderInfo">
                        <h4>Chat with {roomName}</h4>
                    </div>
                </div>
                <div className="messages chatBody" ref={dummy}>
                    <ReactScrollableFeed>
                        <div className="messages-content">
                            {
                                messages.map(message => (
                                    <ChatMessage key={message.id} sender={message.data.sender} message={message.data.message} date={moment.unix(message.data.dt?.seconds).format("DD.MM.YYYY HH:mm:ss")} />
                                ))
                            }
                        </div>
                    </ReactScrollableFeed>
                </div>
                <div className="chatFooter">
                    <div className="message-box">
                        <textarea type="text" className="message-input" id="message" onChange={handleTextChange} value={text} placeholder="Type message..."></textarea>
                        <input type="hidden" id="username" name="username" value={username}></input>
                        <button type="submit" onClick={onAddMessage} className="message-submit">Send</button>
                    </div>
                </div>
            </div>
        </div>
     );
}
 
export default Chat;