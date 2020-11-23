import React from 'react';
import { Route, Switch, BrowserRouter as Router } from 'react-router-dom';
import "./styles/cake.css";
import "./styles/style.css"
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

const App = () => {

   return (
    <>
        <Router>
          <Nav />
          <Col md="9">
            <Switch>
              <Route path="/overview" component={Overview} />
              <Route path="/abilities" component={Abis} />
              <Route path="/forces" component={Forces} />
              <Route path="/inventory" component={Inventory} />
              <Route path="/login" component={Login}/>
              <Route path="/casino" component={Blackjack}/>
            </Switch>
          </Col>
        </Router>
    </>
  )};

export default App;