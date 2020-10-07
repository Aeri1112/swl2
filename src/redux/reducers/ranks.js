const initialState = {
    fetching: false,
    fetched: false,
    rank: [
        {id: "0", rank: "Uneingeweiht", side: "0"},
        {id: "1", rank: "Eingeweiht", side: "0"},
        {id: "2", rank: "Lernender", side: "0"},
        {id: "3", rank: "Auszubildender", side: "0"},
        {id: "4", rank: "Geselle", side: "0"},
        {id: "5", rank: "Charge", side: "0"},
        {id: "6", rank: "Disciple", side: "0"},
        {id: "7", rank: "Primarch", side: "-1"},
        {id: "7", rank: "Jedi Knight", side: "1"},
        {id: "8", rank: "Dark Lord", side: "-1"},
        {id: "8", rank: "Jedi Master", side: "1"},
        {id: "9", rank: "Imperator", side: "-1"},
        {id: "9", rank: "Kanzler", side: "1"},
],
    error: null
}

const ranks = (state=initialState, action) => {
        return state;
    }


export default ranks;