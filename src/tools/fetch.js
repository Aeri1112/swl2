import {get} from 'lodash'

// Das Ding hier habe ich dir aehnlich schon mal gezeigt.

// hier die base url zum setzen, diese setze ich in der index.js.
let baseUrl = '';
export const setBaseUrl = (url) => {
	baseUrl = url
}
// der jwt token fuer den auth mechanismus.
// hier nutze ich den session storage zum initialiseren
let jwt = sessionStorage.getItem('jwt') || null
export const setJwtToken = (token) => {
	// und beim setzen aktualisiere ich den session storage
	// der ist automatisch wieder leer, wenn der brower geschlossen wurde.
	// diese funktion nutze ich fuer den login und logout, beim logout reicht den zu NULL'en
	// da ohne den token, er sich nicht mehr authentifizieren kann
	jwt = token
	sessionStorage.setItem('jwt', token)
}

// die standard headers
const defaultOptions = {
	mode: 'cors',
	credentials: 'include',
	headers: {
		'Content-Type': 'application/json'
	},
	redirect: 'follow'
}

// mein eigener fetch wrapper
const _fetch = async (url, options = {}) => {
	if (baseUrl) {
		url = baseUrl + url
	}
	// das besondere, er fuegt den jwt token, falls gesetzt, als Authorization header hinzu.
	// wenn die plugin doko gesehen hast (recht kurz), dann wirst es hier wieder erkennen
	// dadurch, dass ich das hier verpacke, muss ich mich sonst auch nicht mehr darum kuemmern.
	// wenn der auth fehlschlaegt, wird ein error geworfen mit dem code 401.
	// errors werden weiter unten geworfen
	let auth = {}
	if (jwt) {
		auth = {'Authorization': 'Bearer ' + jwt}
	}
	// hier wird die erste stufe von fetch gezuendet
	const response = await fetch(url, {
		...defaultOptions,
		...options,
		headers: {
			...defaultOptions.headers,
			...(options ? options.headers : {}),
			...auth
		}
	})
	// und hier wird json extrahiert
	const data = await response.json()
	if (!response.ok) {
		// error, also ausserhalb 2xx
		// hier nutze ich 'get' von 'lodash'.
		// das ist recht praktisch um verschachteltes aus object zu laden
		// generell verpackt das rest plugin in cake den content in einen result feld.
		// dieses feld wird immer befuellt.
		// daher werfe ich nen error, dieser kann dann per try/catch order .catch(...) gefangen werden.
		/*
		if(response.status === 401 || response.status === 404) {
			window.location.assign("/login");
			return;
		}
		*/
		throw get(data, 'result', {mesasge : response.statusText, code: response.status})
	}
	// dito wie beim error, nur zusaetzlich mit fallback, wird es aber normal nicht brauchen
	return data.result || data
}

// dann kommen hier noch paar shorthand funktionen
export const GET = (url, options = {}) => _fetch(url, {...options, method: 'GET'})
export const DELETE = (url, options = {}) => _fetch(url, {...options, method: 'DELETE'})
export const POST = (url, data = null, options = {}) => {
	options = {...options, method: 'POST'}
	if (null !== data) {
		options.body = JSON.stringify(data)
	}
	return _fetch(url, options)
}
export const PUT = (url, data = null, options = {}) => {
	options = {...options, method: 'PUT'}
	if (null !== data) {
		options.body = JSON.stringify(data)
	}
	return _fetch(url, options)
}
