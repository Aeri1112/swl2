import { createStore, applyMiddleware } from 'redux';
import thunk from "redux-thunk";
import combinedReducer from './reducer';

const store = createStore(combinedReducer, applyMiddleware(thunk));

export default store;