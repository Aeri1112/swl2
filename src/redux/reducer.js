import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';

import skills from './reducers/skills';
import alliance from "./reducers/alliance";
import ranks from "./reducers/ranks";

export default combineReducers({
    skills,
    alliance,
    ranks,
  router: routerReducer
});