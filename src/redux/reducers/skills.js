import { FETCH_STATS } from "../constants/actionTypes";
import { FETCH_SIDE } from "../constants/actionTypes";
import { FETCH_CHAR } from "../constants/actionTypes";

const initialState = {
    fetching: false,
    fetched: false,
    char: {},
    skills: {},
    side: {},
    error: null
}

const skills = (state=initialState, action) => {
    switch (action.type) {
        case FETCH_STATS:
            return{
                ...state,
                fetched: true,
                skills: action.payload
            }
        case FETCH_SIDE:
            return{
                ...state,
                fetched: true,
                side: action.payload
            }
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

export default skills;