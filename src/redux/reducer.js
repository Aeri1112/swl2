import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';

import skills from './reducers/skills';
import char from "./reducers/char";

export default combineReducers({
    skills,
    char,
  router: routerReducer
});