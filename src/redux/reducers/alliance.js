import { FETCH_DATA } from "../constants/actionTypes";

const initialState = {
    fetching: false,
    fetched: false,
    data: {},
    error: null
}

const alliance = (state=initialState, action) => {
    switch (action.type) {
        case FETCH_DATA:
            return{
                ...state,
                fetched: true,
                data: action.payload
            }
        default:
        return state;   
    }
}

export default alliance;