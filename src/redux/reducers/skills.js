import { FETCH_STATS } from "../constants/actionTypes";

const initialState = {
    fetching: false,
    fetched: false,
    skills: {},
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
        default:
        return state;   
    }
}

export default skills;