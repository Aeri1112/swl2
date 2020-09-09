import React from 'react';
import { Route, Switch, BrowserRouter as Router } from 'react-router-dom';
import "./styles/cake.css";
import "./styles/style.css"
import 'bootstrap/dist/css/bootstrap.min.css';

//loading components
import Nav from './components/Nav';
import Overview from './components/overview';
import Abis from './components/abilities';
import Forces from "./components/forces";

const App = () => {

   return (
    <>
        <Router>
          <Nav />
            <Switch>
              <Route path="/overview" component={Overview} />
              <Route path="/abilities" component={Abis} />
              <Route path="/forces" component={Forces} />
            </Switch>
        </Router>
    </>
  )};

export default App;