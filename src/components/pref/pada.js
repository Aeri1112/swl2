import React, { useEffect, useState } from 'react';
import { useHistory } from "react-router-dom";
import { Row, Button, Modal } from 'react-bootstrap';
import { GET, POST } from '../../tools/fetch';
import Card from './card';
import {useSelector} from "react-redux";
import "./pada.css";
import {firestore} from "../../tools/firebase";
import Request from './request';

const Pada = () => {

    const history = useHistory();
    const [response, setResponse] = useState(null);
    const [loading, setLoading] = useState(false);

    const [text, setText] = useState("");

    const [modal, setModal] = useState(false);
    const [lateModal, setLateModal] = useState(false);

    const [listData, setListData] = useState([]);

    const loadData = async () => {
        try {
            setLoading(true);
            const response = await GET("/preferences/pada");
            if(response) {
                setResponse(response);
                if(response.level >= 75) {
                    setListData(response.padas);
                }
                else {
                    setListData(response.masters);
                }
            }
        } finally {
            setLoading(false)
        }
    }

    const forfeit = async (id) => {
        try {
            // da diese funktion die load wrapped, kann ich hier noch mal die selbe loading variable
            // nutzen. Das liegt daran, dass wir loadData() auch hiere awaiten.
            // Dadurch wird die asynchrone ausfuerung sequentiell ausgefuehrt.
            // forfeit() -> loading = true
            // loadData() -> loading = true
            // loadData() -> loading = false
            // forfeit() -> loading = false
            // da 2 mal der zustand nicht geaendet wird... ist alles cool
            setLoading(true)
            const request = await GET(`/preferences/forfeit/${id}`);
            if(request) {
                await loadData()   
            }
        } finally {
            setLoading(false)
        }
    }

    const accept = async (id) => {
        try {
            setLoading(true)
            const request = await GET(`/preferences/accept/${id}`);
            if(request) {
                if(request.late) {
                    setLateModal(true)
                }
                await loadData()   
            }
        } finally {
            setLoading(false)
        }
    }

    const request = async (id) => {
        try {
            setLoading(true)
            const request = await GET(`/preferences/request/${id}`);
            if(request) {
                await loadData()   
            }
        } finally {
            setLoading(false)
        }
    }

    const handleLeave = async () => {
        try {
            setLoading(true)
            const request = await GET(`/preferences/leave`);
            if(request) {
                await loadData()   
            }
        } finally {
            setLoading(false)
        }
    }

    const handleShow = () => setModal(true);
    const handleClose = () => setModal(false);

    const handleCloseLateModal = () => setLateModal(false);

    const handleChangeText = (e) => {
        setText(e.target.value)
    }
    const handlePost = async () => {
        try {
            setLoading(true)
            const offer = await POST(`/preferences/offer`,{
                text: text
            });
            if(offer) {
                await loadData()   
            }
        } finally {
            setModal(false)
            setLoading(false)
        }
    }

    const [chats, setChats] = useState([]);
    const userid = useSelector(state => state.user.userId);
    const username = useSelector(state => state.user.username);

    const redirectChat = () => {
        //alle meine Chats durchgehen
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
    }

    useEffect(() => {
        if(response) {
            //dann den des Padas raus filtern
            Object.keys(chats).map((chat) => {
                if(chats[chat].data.members.includes(response.masterPada.userid) && chats[chat].data.members.length === 2) {
                    //ein Chat ist vorhanden
                    localStorage.setItem('pada', chats[chat].id);
                    history.push("/messages")
                }
                else {
                    // Add a new document with a generated id.
                    firestore.collection("chats").add({
                        name : [username, response.masterPada.username],
                        members: [+userid, +response.masterPada.userid]
                    })
                    .then((docRef) => {
                        localStorage.setItem('pada', docRef.id);
                        history.push("/messages")
                    })
                    .catch((error) => {
                        console.error("Error adding document: ", error);
                    });
                }
            })
            //Überhaupt keine Chats vorhanden
            if(chats.length === 0) {
                // Add a new document with a generated id.
                firestore.collection("chats").add({
                    name : [username, response.masterPada.username],
                    members: [+userid, +response.masterPada.userid]
                })
                .then((docRef) => {
                    localStorage.setItem('pada', docRef.id);
                    history.push("/messages")
                })
                .catch((error) => {
                    console.error("Error adding document: ", error);
                });
            }
        }
    },[chats])

    useEffect(() => {
        loadData();
    },[])

    return ( 
        <div>
            {
                response && response.nomaster === true &&
                <div>
                    <div>
                        Du bist noch keine Bindung zu einem {response.level >= 75 ? "Schüler" : "Meister"} eingegangen.
                    </div>
                    {                             
                        //wenn noch kein Gesuch gestellt wurde
                        response.req == null &&
                        <div>
                            <div>
                                Falls du auf der Suche nach einem {response.level >= 75 ? "Schüler" : "Meister"} bist, kannst du hier ein Gesuch absetzen. Danach erhälts du auch eine Übersicht der verfügbaren Charaktere.
                            </div>
                            <div className="text-center">
                                <Button onClick={handleShow} variant="success">
                                    Gesuch aufgeben
                                </Button>
                            </div>
                        </div>
                    }
                    {                            
                        //wenn bereits ein Gesuch gestellt
                        response.req !== null &&
                        <div>
                            <div>
                                Hier siehst du eine Übersicht der aktuell suchenden Charaktere.
                            </div>
                            <Row>
                                {listData && listData.map((player) => {
                                    return(
                                            <Request
                                                key = {player.char.userid}
                                                player = {player}
                                                forfeit = {forfeit}
                                                accept = {accept}
                                                request = {request}
                                            />
                                        )
                                    })
                                }
                            </Row>
                        </div>
                    }
                </div>
            }
            {
                response && response.nomaster === false &&
                <div>
                    <div className="h1 container text-center">
                        Dein {response.masterPada.skills.level < 75 ? "Padawan" : "Meister"}
                    </div>
                    <Card
                        player={response.masterPada}
                        handleLeave = {handleLeave}
                        redirectChat = {redirectChat}
                    />
                </div>
            }
            <Modal scrollable show={modal} onHide={handleClose}>
                <Modal.Header closeButton>
                    <Modal.Title>
                        Gesuch erstellen
                    </Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <label for="message-text" className="col-form-label">Message:</label>
                    <textarea value={text} onChange={handleChangeText} maxLength={200} className="form-control" id="message-text"/>
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="danger" onClick={handleClose}>
                        Close
                    </Button>
                    <Button variant="success" onClick={handlePost}>
                        abschicken
                    </Button>
                </Modal.Footer>
            </Modal>
            
            <Modal scrollable show={lateModal} onHide={handleCloseLateModal}>
                <Modal.Header closeButton>
                    <Modal.Title>
                        Bereits vergeben
                    </Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    Jemand anderes war schneller
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="primary" onClick={handleCloseLateModal}>
                        ok
                    </Button>
                </Modal.Footer>
            </Modal>
        </div>
     );
}
 
export default Pada;