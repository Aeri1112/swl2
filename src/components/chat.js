import React, { useEffect, useRef, useState } from 'react';
import {useSelector} from "react-redux";
import ReactScrollableFeed from "react-scrollable-feed";
import ChatMessage from "../tools/chatMessage";
import {database} from "../tools/firebase";

const Chat = () => {

    const dummy = useRef(null);
    const [text, setText] = useState("");
    const [messages, setMessages] = useState();

    const username = useSelector(state => state.user.username);
    
    const scrollToBottom = () => {
      dummy.current.scrollTop = dummy.current.scrollHeight;
    }

    /* TODO: Add SDKs for Firebase products that you want to use
         https://firebase.google.com/docs/web/setup#config-web-app */
      
    useEffect(() => {
      database.ref("messages").limitToLast(50).on("value", snapshot => {
        const getChats = snapshot.val();
        setMessages(getChats);
      });
    }, []);

    useEffect(() => {
      scrollToBottom();
    }, [messages]);

    const handleTextChange = (e) => {
      setText(e.target.value)
    }

    const onAddMessage = (event) => {
      event.preventDefault();
      var dt = new Date();
      var h = dt.getHours();
      if(h < 10) var h = '0' + h;
      var m = dt.getMinutes();
      if(m < 10) var m = '0' + m;
      var month = dt.getMonth() + 1;
      var day = dt.getDate();
      var year = dt.getFullYear();
      var dtstring =  h + ':' + m + ', ' + day + '.' + month + '.' + year;
      database.ref('messages').push().set({message: text, dt:dtstring, sender: username});
      setText("");
    }

    return (
    <div className="chat">
      <div className="chat-title">
        <h1>Gruppenchat</h1>
        <h2>SWL</h2>
        <figure className="avatar" style={{bottom:"0px"}}>
          <img src="https://p7.hiclipart.com/preview/349/273/275/livechat-online-chat-computer-icons-chat-room-web-chat-others.jpg" /></figure>
      </div>
      <div className="messages" ref={dummy}>
        <ReactScrollableFeed>
          <div className="messages-content">
            {messages && Object.keys(messages).map((key, index) => <ChatMessage key={key} sender={messages[key].sender} message={messages[key].message} date={messages[key].dt} />)}
          </div>
        </ReactScrollableFeed>
      </div>
      <div className="message-box">
        <textarea style={{width:"90%"}} type="text" className="message-input" id="message" onChange={handleTextChange} value={text} placeholder="Type message..."></textarea>
        <input type="hidden" id="username" name="username" value={username}></input>
        <button type="submit" onClick={onAddMessage} className="message-submit">Send</button>
      </div>
    </div>
    )
}

export default Chat;