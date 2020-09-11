export function fetchAllianceData (id) {
    return function(dispatch) {
        fetch('http://localhost/react/my-app/api/alliance/alliance.php',
        {
            method: "POST",
            body: JSON.stringify({id: id})
        })
        .then(response => response.json())
        .then(response => {
            dispatch({type: "FETCH_DATA", payload: response});
        })
    }
}