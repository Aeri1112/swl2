import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';

import skills from './reducers/skills';
import alliance from "./reducers/alliance";
import ranks from "./reducers/ranks";
import char from "./reducers/char";
import user from "./reducers/user";

export default combineReducers({
    skills,
    alliance,
    ranks,
    char,
    user,
  router: routerReducer
});