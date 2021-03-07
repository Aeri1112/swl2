import React, { useEffect, useState } from "react";
import {useDispatch, useSelector} from "react-redux";
import {GET, setJwtToken} from "../tools/fetch";
import { Redirect } from 'react-router-dom';
import { Nav, Navbar, NavDropdown, NavLink } from "react-bootstrap";
import { LinkContainer } from 'react-router-bootstrap';

import {characterState__setOverviewData} from "../redux/actions/characterActions";
import { fetchAllianceData } from "../redux/actions/allianceActions";

const Navi = () => {

  const dispatch = useDispatch();

  const [redirect, setRedirect] = useState(false);
  const [loading, setLoading] = useState();

  const rspStore2 = useSelector(state => state.skills.skills.rsp);
  const rfpStore2 = useSelector(state => state.skills.skills.rfp);

  const alliData = useSelector(state => state.alliance);

  const loadData = async() => {
      try {
          setLoading(true)
          const response = await GET('/character/overview')
          if (response) {
              dispatch(characterState__setOverviewData(response))  
          }
          const responseAlli = await GET('/alliances')
            if (responseAlli) {
                dispatch(fetchAllianceData(responseAlli))
            }
          setLoading(false)
      } 
      catch (e) {
          return
      }
  }

  const handleLogout = () => {
      setJwtToken(null) // hier nutze ich sie, um den token wieder zu entziehen.
      // wenn keine aktion auf dem server notwendig ist, reicht das hier auch.
      setRedirect(true)
      dispatch({type:"IS_AUTH",payload:{isAuth:"false"}});
  }

  useEffect(() => {
    loadData();
  }, []);

  return (
    <div>
      {redirect === true && 
        <Redirect push to="/login" />
        /*window.location.assign("/login")*/
      }
      {
        loading === false &&
        <Navbar collapseOnSelect bg="dark" variant="dark" expand="lg">
        
        <LinkContainer to="/overview">
          <Navbar.Brand>SWL</Navbar.Brand>
        </LinkContainer>
        
        <Navbar.Toggle aria-controls="basic-navbar-nav" />
        <Navbar.Collapse id="basic-navbar-nav">
          <Nav className="mr-auto">

            <NavDropdown title={(rfpStore2 > 0 || rspStore2 > 0) ? <span className="text-danger">Charakter !</span> : "Überblick"} id="basic-nav-dropdown">
              <LinkContainer to="/overview">
                <NavDropdown.Item>Überblick</NavDropdown.Item>
              </LinkContainer>
              <LinkContainer to="/inventory">
                <NavDropdown.Item>Ausrüstung</NavDropdown.Item>
              </LinkContainer>
              <LinkContainer to="/abilities">
                <NavDropdown.Item>{rspStore2 > 0 ? <span>Fähigkeiten <span className="text-danger">{rspStore2}</span></span> : "Fähigkeiten"}</NavDropdown.Item>
              </LinkContainer>
              <LinkContainer to="/forces">
                <NavDropdown.Item>{rfpStore2 > 0 ? <span>Mächte <span className="text-danger">{rfpStore2}</span></span> : "Mächte"}</NavDropdown.Item>
              </LinkContainer>
            </NavDropdown>

            <NavDropdown title="Stadt" id="basic-nav-dropdown">
              <LinkContainer to="/city">
                <NavDropdown.Item>Überblick</NavDropdown.Item>
              </LinkContainer>
              <LinkContainer to="/apartment">
                <NavDropdown.Item>Apartment</NavDropdown.Item>
              </LinkContainer>
              <LinkContainer to="/bar">
                <NavDropdown.Item>Bar</NavDropdown.Item>
              </LinkContainer>
              <LinkContainer to="/arena">
                <NavDropdown.Item>Arena</NavDropdown.Item>
              </LinkContainer>
              <LinkContainer to="/casino">
                <NavDropdown.Item>Casino</NavDropdown.Item>
              </LinkContainer>
              <LinkContainer to="/layer">
                <NavDropdown.Item>Layer</NavDropdown.Item>
              </LinkContainer>
            </NavDropdown>

            <LinkContainer to="/messages">
              <Nav.Link>Nachrichten</Nav.Link>
            </LinkContainer>

            <LinkContainer to="/alliance">
              <Nav.Link>{alliData && alliData.AlliData.alli_fight ? <span className="text-danger">Allianz<img style={{width:"20px",height:"20px"}} src={require("../images/raid.png")} /></span> : "Allianz"}</Nav.Link>
            </LinkContainer>

            <NavDropdown title="Einstellungen" id="basic-nav-dropdown">
              <LinkContainer to="/pref">
                <NavDropdown.Item>Kampf</NavDropdown.Item>
              </LinkContainer>
              <LinkContainer to="#">
                <NavDropdown.Item>Ausbildung</NavDropdown.Item>
              </LinkContainer>
              <LinkContainer to="#">
                <NavDropdown.Item>Account</NavDropdown.Item>
              </LinkContainer>
            </NavDropdown>

            <NavDropdown title="Bugs" id="basic-nav-dropdown">
              <LinkContainer to="#">
                <NavDropdown.Item>auflisten</NavDropdown.Item>
              </LinkContainer>
              <LinkContainer to="#">
                <NavDropdown.Item>melden</NavDropdown.Item>
              </LinkContainer>
            </NavDropdown>

            <NavDropdown title="Statistiken" id="basic-nav-dropdown">
              <LinkContainer to="/statistics/players">
                <NavDropdown.Item>Spieler-Rangliste</NavDropdown.Item>
              </LinkContainer>
              <LinkContainer to="#">
                <NavDropdown.Item>Allianz-Rangliste</NavDropdown.Item>
              </LinkContainer>
              <LinkContainer to="/statistics">
                <NavDropdown.Item>persönliche Statistik</NavDropdown.Item>
              </LinkContainer>
            </NavDropdown>

            <NavDropdown title="Events" id="basic-nav-dropdown">
              <LinkContainer to="/events/rank">
                <NavDropdown.Item>Ranglisten-Wettkampf</NavDropdown.Item>
              </LinkContainer>
            </NavDropdown>

            <Nav.Link onClick={handleLogout}>Logout</Nav.Link>

          </Nav>
        </Navbar.Collapse>
      </Navbar>
      }
    </div>
);
}

export default Navi;