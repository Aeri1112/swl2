import React, {useState, useEffect} from 'react';
import {useSelector} from "react-redux";
import SidebarChat from "./sidebarchat";
import './sidebar.css';
import {firestore} from "../../tools/firebase";
import { POST } from '../../tools/fetch';
import { Button } from 'react-bootstrap';

const Sidebar = (props) => {

    const [chats, setChats] = useState([]);

    const [search, setSearch] = useState("");
    const [searching, setSearching] = useState(false);
    const [searchResult, setSearchResult] = useState();

    const userid = useSelector(state => state.user.userId);
    const username = useSelector(state => state.user.username);

    const handleSearch = (e) => {
        setSearch(e.target.value)
    }

    const handleClickOpen = (id) => {
        props.setChat(id)
        setSearch("");
    }

    const handleClickStart = (searchedId) => {
        
        // Add a new document with a generated id.
        firestore.collection("chats").add({
            name : [username, search],
            members: [userid, +searchedId]
        })
        .then((docRef) => {
            props.setChat(docRef.id)
        })
        .catch((error) => {
            console.error("Error adding document: ", error);
        });
        
        setSearch("");
    }

    const searchingDatabse = async () => {
        setSearching(true);

        const response = await POST("/messages/search",{suchbegriff:search});
        if(response.userid) {
            Object.keys(chats).map((chat) => {
                if(chats[chat].data.members.includes(response.userid) && chats[chat].data.members.length === 2) {
                    //ein Chat ist vorhanden
                    setSearchResult(<div><Button onClick={() => handleClickOpen(chats[chat].id)}>open Chat</Button></div>)
                }
                else {
                    setSearchResult(<div><Button onClick={() => handleClickStart(response.userid)}>start Chat</Button></div>)
                }
            })
            if(chats.length === 0) {
                setSearchResult(<div><Button onClick={() => handleClickStart(response.userid)}>start Chat</Button></div>)
            }
        }
        else {
            setSearchResult();
        }

        setSearching(false)
    }

    useEffect(() => {
        
        const unsub = firestore.collection("chats").where("members","array-contains",userid).orderBy("lastMessage","desc").onSnapshot(snapshot => (
            setChats(snapshot.docs.map(doc => ({
                id: doc.id,
                data: doc.data(),
                })
            ))
        ))
        
        return () => {
            unsub()
        }
    },[]);

    useEffect(() => {
        searchingDatabse();
    },[search])

    return ( 
    <div className="col-md-4">
        <div className="sidebar">
            {/*header*/}
            <div>

            </div>
            {/*search*/}
            <div className="sidebarSearch">
                {/*searchContainer*/}
                <div className="sidebarSearchContainer">
                    <input onChange={handleSearch} value={search} placeholder="search or start new chat" type="text" />
                </div>
            </div>
            {
                searchResult
            }
            {/*chats*/}
            <div className="sidebarChats">
                {
                    searching &&
                    "loading..."
                }
                {
                    Object.keys(chats).map((key) => <SidebarChat
                                                        key={key}
                                                        setChat={props.setChat}
                                                        name={username === chats[key].data.name[0] ? chats[key].data.name[1] : chats[key].data.name[0]}
                                                        id={chats[key].id}
                                                    />
                    )
                }
            </div>
        </div>
    </div>
     );
}
 
export default Sidebar;