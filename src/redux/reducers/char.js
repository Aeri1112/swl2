import { FETCH_CHAR } from "../constants/actionTypes";
import { FETCH_MASTER } from "../constants/actionTypes";

const initialState = {
    fetching: false,
    fetched: false,
    char: {},
    error: null
}

const char = (state=initialState, action) => {
    switch (action.type) {
        case FETCH_CHAR:
            return{
                ...state,
                fetched: true,
                char: action.payload
            }
        case FETCH_MASTER:
            return{
                ...state,
                master: action.payload
            }
        default:
        return state;   
    }
}

export default char;