import React from 'react';
import {IS_AUTH} from "../constants/actionTypes";

const initialState = {
    isAuth:"false",
    userId: 0,
};

const User = (state=initialState, action) => {
    switch(action.type) {
        case IS_AUTH:
            return {
                ...state,
                isAuth: action.payload.isAuth,
                userId: action.payload.userId,
                username: action.payload.username
            }
        default:
            return {
                ...state
            }
    }
}

export default User;