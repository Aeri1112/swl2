import React from 'react';
import { Route, Redirect, Switch, BrowserRouter as Router } from 'react-router-dom';
import {useSelector} from "react-redux";
import "./styles/cake.css";
import "./styles/neon.css";
import "./styles/style.css"
import "./styles/chat.css"
import 'bootstrap/dist/css/bootstrap.min.css';

import Col from "react-bootstrap/Col";
//loading components
import Nav from './components/Nav';
import Overview from './components/overview';
import Abis from './components/abilities';
import Forces from "./components/forces";
import Inventory from "./components/inventory_3";
import Login from "./components/login";
import Blackjack from "./components/casino/blackjack/Blackjack";
import Apartment from './components/city/apartment';
import Bar from "./components/city/bar";
import Casino from "./components/casino/casino";
import Layer from "./components/city/layer";
import Alliance from "./components/alliance/alliance";
import Raid from "./components/alliance/raid";
import All from "./components/alliance/all";
import View from "./components/alliance/view";
import Arena from "./components/city/arena";

import Preferences from "./components/pref/preferences";

import Chat from "./components/chat";
import { Row } from 'react-bootstrap';

const App = () => {

  const isAuth = useSelector(state => state.user.isAuth);
   return (
    <> 
        <Router>
          {
            isAuth !== "false" ?
              <Nav />
            : <Redirect to="/login" />
          }
          <Switch>
          <Route exact path="/" component={Login} />
          <Route path="/login" component={Login} />
          <div className="container-fluid">
            <Row>
              <Col md="9">
                  <Route path="/overview" component={Overview} />
                  <Route path="/abilities" component={Abis} />
                  <Route path="/forces" component={Forces} />
                  <Route path="/inventory" component={Inventory} />
                  <Route path="/casino" component={Casino}/>
                    <Route path="/bj" component={Blackjack}/>
                  <Route path="/apartment" component={Apartment}/>
                  <Route path="/bar" component={Bar}/>
                  <Route path="/layer" component={Layer}/>
                  <Route exact path="/alliance" component={Alliance}/>
                    <Route path="/alliance/raid" component={Raid}/>
                    <Route path="/alliance/all" component={All}/>
                    <Route path="/alliance/view" component={View} />
                  <Route path="/arena" component={Arena} />

                  <Route path="/pref" component={Preferences}/>
              </Col>
              <Col md={"3"}>
              {
                isAuth !== "false" ?
                  <Chat />
                : null
              }
              </Col>
            </Row>
          </div>
          </Switch>
        </Router>
    </>
  )};

export default App;