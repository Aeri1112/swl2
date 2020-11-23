import { FETCH_STATS } from "../constants/actionTypes";
import { FETCH_SIDE } from "../constants/actionTypes";
import { FETCH_CHAR } from "../constants/actionTypes";
import { FETCH_MASTER } from "../constants/actionTypes";
import { FETCH_INV } from "../constants/actionTypes";
import { FETCH_EQP } from "../constants/actionTypes";


const initialState = {
    fetching: false,
    fetched: false,
    char: {},
    skills: {},
    side: {},
    master: {},
    inv: {},
    eqp: {},
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
        case FETCH_MASTER:
            return{
                ...state,
                fetched: true,
                master: action.payload
            }
        case FETCH_INV:
            return{
                ...state,
                fetched: true,
                inv: action.payload
            }
        case FETCH_EQP:
        return{
            ...state,
            fetched: true,
            eqp: action.payload
        }
        default:
        return state;   
    }
}

export default skills;