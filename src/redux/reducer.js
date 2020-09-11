import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';

import skills from './reducers/skills';
import alliance from "./reducers/alliance";

export default combineReducers({
    skills,
    alliance,
  router: routerReducer
});