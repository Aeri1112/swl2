import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';

import reducer from './reducers/skills';

export default combineReducers({
    reducer,
  router: routerReducer
});