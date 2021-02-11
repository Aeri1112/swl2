import React, { useEffect, useState } from 'react';
import {GET,POST} from "../../tools/fetch";

import Form from "react-bootstrap/Form";
import Spinner from "react-bootstrap/Spinner";

const Preferences = () => {

    const [loading, setLoading] = useState();
    const [response, setResponse] = useState();
    const [saving, setSaving] = useState(false);
    const [formData, setFormData] = useState({});

    const loadData = async () => {
        setLoading(true)
        try {
            const response = await GET("/preferences/fight");
                if (response) {
                    setResponse(response);
                    setFormData({
                        stance: response.stance,
                        initiative: response.initiative,
                        heroic: response.heroic,
                        inno: response.inno,
                        surro: response.surro,
                        prefereddef: response.prefereddef,
                        preferedoff: response.preferedoff,
                        switchoff_1: response.switchoff_1
                    })
                }
        }
        catch (e) {
            console.log(e)
        }
        finally {
            setLoading(false)
        }
    }

    const handleChange = (e) => {
        if(e.target.name === "inno" || e.target.name === "surro") {
            const oldV = formData[e.target.name];
            const newV = oldV === "1" ? "0" : "1";
            setFormData({
                ...formData,
                [e.target.name]: newV
            })
        }
        else {
            setFormData({
                ...formData,
                [e.target.name]: e.target.value
            })
        }
        setSaving(true)   
    }

    useEffect( () => {
        if(saving === true) {
            const PostData = async () => {
            const request = await POST("/preferences/fight",{formData});
                if(request) {
                    loadData();
                    setSaving(false);
                }
            }
            PostData();
        }
    }, [saving])
    

    useEffect(() => {
        loadData();
    }, [])

    return ( 
        <div>
            {
                loading === false &&
                <div>
                    <Form>
                        <Form.Group controlId="stance">
                            <Form.Label>übliche Lichtschwerthaltung</Form.Label>
                            <Form.Control onChange={handleChange} value={formData.stance} name="stance" as="select" custom>
                                <option value={"0"}>tief</option>
                                <option value={"1"}>mittig</option>
                                <option value={"2"}>hoch</option>
                            </Form.Control>
                        </Form.Group>
                        <Form.Group controlId="initiative">
                            <Form.Label>Initiative</Form.Label>
                            <Form.Control onChange={handleChange} value={formData.initiative} name="initiative" as="select" custom>
                                <option value={"0"}>ruhig</option>
                                <option value={"1"}>normal</option>
                                <option value={"2"}>agressiv</option>
                            </Form.Control>
                        </Form.Group>
                        <Form.Group controlId="heroic">
                            <Form.Label>Heldenhaftigkeit</Form.Label>
                            <Form.Control onChange={handleChange} value={formData.heroic} name="heroic" as="select" custom>
                                <option value={"0"}>feige</option>
                                <option value={"1"}>normal</option>
                                <option value={"2"}>riskant</option>
                                <option value={"3"}>blutdurstig</option>
                            </Form.Control>
                        </Form.Group>
                        <Form.Group controlId="prefereddef">
                            <Form.Label>bevorzugte Defensivmacht</Form.Label>
                            <Form.Control onChange={handleChange} value={formData.prefereddef} name="prefereddef" as="select" custom>
                                {Object.keys(response.def_options).map((element) => {
                                    return <option key={element} value={`${element}`}>{response.def_options[element]}</option>
                                })}
                            </Form.Control>
                        </Form.Group>
                        <Form.Group controlId="preferedoff">
                            <Form.Label>bevorzugte Defensivmacht</Form.Label>
                            <Form.Control onChange={handleChange} value={formData.preferedoff} name="preferedoff" as="select" custom>
                                {Object.keys(response.off_options).map((element) => {
                                    return <option key={element} value={`${element}`}>{response.off_options[element]}</option>
                                })}
                            </Form.Control>
                        </Form.Group>
                        <Form.Group controlId="switchoff_1">
                            <Form.Label>Macht unterdrücken</Form.Label>
                            <Form.Control onChange={handleChange} value={formData.switchoff_1} name="switchoff_1" as="select" custom>
                                {Object.keys(response.options).map((element) => {
                                    return <option key={element} value={`${element}`}>{response.options[element]}</option>
                                })}
                            </Form.Control>
                        </Form.Group>
                        <Form.Check 
                            type="switch"
                            id="inno"
                            label="unschuldige beachten?"
                            name="inno"
                            checked={formData.inno === "1" ? true : false}
                            onChange={handleChange}
                        />
                        <Form.Check 
                            type="switch"
                            id="surro"
                            label="Umgebung beschädigen?"
                            name="surro"
                            checked={formData.surro === "1" ? true : false}
                            onChange={handleChange}
                        />
                    </Form>
                </div>
            }
            {
                saving &&
                    <Spinner animation="border" role="status">
                        <span className="sr-only">Loading...</span>
                    </Spinner>
            }
        </div>
     );
}
 
export default Preferences;