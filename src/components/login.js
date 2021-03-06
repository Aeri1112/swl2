import React, { useState } from "react";
import {Redirect} from "react-router-dom";
import { useDispatch } from 'react-redux'
import { Button, Form, FormGroup, FormControl, Row, Col, Navbar } from "react-bootstrap";
import { POST, setJwtToken} from "../tools/fetch";
import Imp from "./home/impressum";
import Dat from "./home/datenschutz";
import Con from "./home/contact";
import About from "./home/more";

import "./login.css";

// ich hab hier n bissl was veraendert, aber nix tragisches.

export default function Login() {

  const [imp, setImp] = useState(false);
  const [about, setAbout] = useState(false);
  const [con, setCon] = useState(false);
  const [dat, setDat] = useState(false);


  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  // data wird als json string dann angezeigt, auch fuer fehler
  const [data, setData] = useState(null)
  const [loading, setLoading] = useState(false)
  
  const dispatch = useDispatch();

  async function handleSubmit(event) {
    event.preventDefault();
    // hier habe das ergebnis des logins gehandlet
    setLoading(true)
    try {
      const data = await POST('/test/login', {accountname: email, password: password})
      if(data) {
        setLoading(false)
        dispatch({type:"IS_AUTH",payload:{isAuth:"true",userId:data.user, username:data.username.username}});
        setJwtToken(data.token)
        setData(data)
      }
      // await fetch("http://localhost/new_jtg/api/accounts/login", {
      //     method: "POST",
      //     body: JSON.stringify({
      //       accountname: "Aeri1112",
      //       password: "1234"
      //     })
      // })
    } catch (e) {
      setData(e)
    }
  }
  
const handleImp = (state) => {
  setImp(state)
}
const handleAbout = (state) => {
  setAbout(state)
}
const handleCon = (state) => {
  setCon(state)
}
const handleDat = (state) => {
  setDat(state)
}

  return (
    <div style={{height:"100vh"}} className="col login-left-box">
      {
        data && data.token &&
        <Redirect push to="/overview" />
      }
      <Row style={{paddingBottom:"8%"}} className="mr-0 ml-0">
        <Col>
          <Navbar>
            <Navbar.Toggle />
            <Navbar.Collapse className="justify-content-end">
              <Navbar.Text>
                <Button className="text-dark" variant="link" onClick={() => handleAbout(true)}>was ist Star Wars Legends?</Button>
                <Button className="text-dark" variant="link" onClick={() => handleCon(true)}>Kontakt</Button>
                <Button className="text-dark" variant="link" onClick={() => handleDat(true)}>Datenschutz</Button>
                <Button className="text-dark" variant="link" onClick={() => handleImp(true)}>Impressum</Button>
              </Navbar.Text>
            </Navbar.Collapse>
          </Navbar>
        </Col>
      </Row>
      <Row className="justify-content-end mr-0 ml-0">
        <Col className="order-last order-lg-first glassLogin">
          <div className="m-4 h2">
            latest Updates
          </div>
          <div className="update border">
            Ein paar Account-Einstellungen sind nun möglich<br/>
            Einen Drink für 100 % Mana <br/>
            Meister-/Padawan System <br/>
            weitere kleinere Verbesserungen
            <footer className="blockquote-footer text-white">29.06.2021</footer>
          </div>
          <div className="update border">
            Eine Möglichkeit gegen stärke, dem Spieler angepasste, NPC's zu käpfen wurde hinzugefügt<br/>
            weitere kleinere Verbesserungen
            <footer className="blockquote-footer text-white">25.05.2021</footer>
          </div>
          <div className="update border">
            Die ersten zwei Quests haben es ins Spiel geschafft!<br/>
            kleinere Verbesserungen
            <footer className="blockquote-footer text-white">09.04.2021</footer>
          </div>
          {/*
          <div className="update border">
            Refresh-Button in der Arena<br/>
            Anzeige der aktuellen Aktion in der Charakter-Übersicht<br/>
            kleinere Verbesserung (z.B. Countdown)
            <footer className="blockquote-footer text-white">07.03.2021</footer>
          </div>
          */}
          {/*
          <div className="update border">
            Erweiterung der Server-Statistik <br/>
            Ranglisten-Wettkampf auf Grundlage der Elo-Punkteverteilung<br/>
            untätige Charaktere können Raids hinzugefügt werden
            <footer className="blockquote-footer text-white">06.03.2021</footer>
          </div>
          */}
        </Col>
        <Col md="5" className="align-items-center justify-content-center d-flex pl-0 pr-0 order-first order-lg-last">
          <div className="login-box">
            <h1>Welcome!</h1>
            <form onSubmit={handleSubmit}>
              <FormGroup controlId="accountname" bssize="large">
                <>Username</>
                <FormControl
                  autoFocus
                  type="text"
                  value={email}
                  onChange={e => setEmail(e.target.value)}
                />
              </FormGroup>
              <Form.Group controlId="formBasicPassword">
                <Form.Label>Password</Form.Label>
                <Form.Control type="password" onChange={(e) => setPassword(e.target.value)} />
              </Form.Group>
              <Button block bssize="large" type="submit" style={{marginTop: 25}}>
                {loading ? 'lade...' : 'Login'}
              </Button>

              {/*
                <Button style={{marginTop: 25}} onClick={testAuth} type={'button'} block>
                  {loading ? 'lade' : 'Test Auth'}
                </Button>

                <Button style={{marginTop: 25}} onClick={logout} type={'button'} block>
                  {loading ? 'lade' : 'Logout'}
                </Button>
              */}

            </form>
            {
              <div className="pt-3 d-flex justify-content-between"><span>Forgot Password?</span> <span>Create Account</span></div>
            }
            
          </div>
        </Col>
      </Row>
        {
          data && data.error &&
          <div className="text-danger">
            {data.error}
          </div>
        }
        <Imp show={imp} handleImp={handleImp} />
        <Con show={con} handleCon={handleCon} />
        <About show={about} handleAbout={handleAbout} />
        <Dat show={dat} handleDat={handleDat} />
    </div>
  );
}