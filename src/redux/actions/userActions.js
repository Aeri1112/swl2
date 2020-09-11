export function fetchUserData () {
    return function(dispatch) {
        fetch('http://localhost/react/my-app/api/character/overview.php',
        {
            method: "POST",
            body: JSON.stringify({userId: 4})
        })
        .then(response => response.json())
        .then(response => {
            dispatch({type: "FETCH_STATS", payload: response[2]});
        })
    }
}
export function fetchUserChar () {
    return function(dispatch) {
        fetch('http://localhost/react/my-app/api/character/overview.php',
        {
            method: "POST",
            body: JSON.stringify({userId: 4})
        })
        .then(response => response.json())
        .then(response => {
            dispatch({type: "FETCH_CHAR", payload: response[1]})
        })
    }
}
export function fetchUserSide () {
    return function(dispatch) {
        fetch('http://localhost/react/my-app/api/character/overview.php',
        {
            method: "POST",
            body: JSON.stringify({userId: 4})
        })
        .then(response => response.json())
        .then(response => {
            dispatch({type: "FETCH_SIDE", payload: response[0]})
        })
    }
}