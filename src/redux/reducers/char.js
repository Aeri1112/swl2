import { FETCH_CHAR } from "../constants/actionTypes";

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
        default:
        return state;   
    }
}

export default char;