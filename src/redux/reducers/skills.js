import { FETCH_STATS } from "../constants/actionTypes";

const initialState = {
    fetching: false,
    fetched: false,
    skills: {},
    error: null
}

const reducer = (state=initialState, action) => {
    switch (action.type) {
        case FETCH_STATS:
            return{
                ...state,
                fetched: true,
                skills: action.payload
            }    
        default:
            break;
    }
    return state;
}

export default reducer;