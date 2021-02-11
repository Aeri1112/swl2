import { FETCH_DATA } from "../constants/actionTypes";

const initialState = {
    fetching: false,
    fetched: false,
    AlliData: {},
    error: null
}

const alliance = (state=initialState, action) => {
    switch (action.type) {
        case FETCH_DATA:
            return{
                ...state,
                fetched: true,
                AlliData: action.payload
            }
        default:
        return state;   
    }
}

export default alliance;