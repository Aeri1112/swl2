import React, { useEffect, useState } from 'react';
import { GET, POST } from '../../tools/fetch';
import { Button, Col, Row } from "react-bootstrap";

const Account = () => {

    const [file, setFile] = useState(null);
    const [errorImage, setErrorImage] = useState("");

    const [errorMail, setErrorMail] = useState("");
    
    const [loading, setLoading] = useState();
    const [response, setResponse] = useState();

    const loadData = async () => {
        try {
            setLoading(true)
            const response = await GET("/preferences/account");
            if(response) {
                setResponse(response);
            }
        }
        finally {
            setLoading(false)
        }
    }

    //Profilbild change
    const handleImageChange = event => {
        if (!event.target.files[0].name.match(/\.(jpg|jpeg|png)$/)) {
            setFile(null);
            setErrorImage('Please select valid image.');
            return false;
        }
        else {
            setFile(event.target.files[0])
        }
    }

    //Profilbild submit
    const handleSubmit = async (event) => {
        event.preventDefault();
        const fd = new FormData();
        fd.append(
            "input",
            file,
            file.name
        );

        let jwt = sessionStorage.getItem('jwt') || null
        let auth = {}
        if (jwt) {
            auth = {'Authorization': 'Bearer ' + jwt}
        }
        const options = {
        method: "POST",
        body: fd,
        auth,
        mode: 'cors',
        credentials: 'include',
        redirect: 'follow'
        // If you add this, upload won't work
        // headers: {
        //   'Content-Type': 'multipart/form-data',
        // }
        };

        try {
            //online
            //https://react.starwarslegends.de/rest-api/preferences/account
            //offline
            //http://localhost/qyr/rest-api/preferences/account
            setLoading(true);
            const request = await fetch("https://react.starwarslegends.de/rest-api/preferences/account", options);
            if(request) {
                await loadData();
            }
        }
        finally {
            setLoading(false)
        }
    }

    //Email submit
    const handleMailChange = async () => {
        try {
            setLoading(true)
            const request = await POST("/preferences/account",{mail:response.account.email})
            if(request) {
                if(request.check) {
                    setErrorMail("Email is already used")
                }
                await loadData();
            }
        }
        finally {
            setLoading(false)
        }
    }

    //Email change
    const handleMailInput = (e) => {
        if(!validateEmail(e.target.value)) {
            setErrorMail("enter valid Mail adress")
        }
        else {
            setErrorMail("");
        }
        setResponse({
            ...response,
            account: {...response.account, email:e.target.value}
        })
    }

    //validate email
    const validateEmail = (email) => {
        var regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return regex.test(String(email).toLowerCase());
    }

    useEffect(() => {
        loadData();
    },[])

    return ( 
            <div>
                <Row>
                    <Col className="h2 text-center">
                        Account-Einstellungen
                    </Col>
                </Row>  
                {
                    response &&
                        <div>
                            <Row>
                                <Col>
                                    <b><div>Account-Name</div></b>
                                    <div>{response.account.accountname}</div>
                                </Col>
                                <Col>
                                    <b><div>User-ID</div></b>
                                    <div>{response.char.userid}</div>
                                </Col>
                            </Row>
                            <Row>
                                <Col>
                                    <b>E-Mail</b>
                                </Col>
                            </Row>
                            <Row className="mb-3">
                                <Col className="d-flex">
                                    <input className="mr-1" type="mail" name="mail" onChange={handleMailInput} value={response.account.email} />
                                    <Button disabled={errorMail !== ""} size="sm" type="submit" onClick={handleMailChange}>ändern</Button>
                                </Col>
                            </Row>
                            <div className="text-danger">
                                {errorMail}
                            </div>
                            <Row>
                                <Col>
                                    <b>Profilbild</b>
                                </Col>
                            </Row>
                            <Row>
                                <Col>
                                    <img src={`/images/profile/${response.char.pic}`} className="text-center img-radius" alt="User-Profile-Image"/>
                                </Col>
                            </Row>
                            <Row>
                                <Col className="d-flex align-items-center">
                                    <input name="file" type="file" onChange={handleImageChange} />
                                    <Button size="sm" type="submit" disabled={file === null} onClick={handleSubmit}>ändern</Button>
                                </Col>
                            </Row>
                            <div>
                                {errorImage}
                            </div>
                            <div>
                                {response.message && response.message}
                            </div>
                        </div>
                }
            </div>
     );
}
 
export default Account;