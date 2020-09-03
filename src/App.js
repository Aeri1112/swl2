import React from 'react';
import { Route, Switch, BrowserRouter as Router } from 'react-router-dom';

//loading components
import Nav from './components/Nav';
import UsingAxios from './components/overview';
import Abis from './components/abilities';

const App = () => {

   return (
    <div>
        <Router>
          <Nav />
            <Switch>
              <Route path="/overview" component={UsingAxios} />
              <Route path="/abilities" component={Abis} />
            </Switch>
        </Router>
    </div>
  )};

export default App;