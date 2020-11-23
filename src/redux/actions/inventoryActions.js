// was ich noch empfehlen kann,
// ist eine sammlung an state dispatch funtionen zu machen, in verschiedenen files vllt.
// nehmen wir das hier: dispatch({type: "FETCH_INV", payload: response})

// bei mir wuerde das inventoryActions.js sein oder so aehnlich
export const inventoryState__setItems = items => ({type: "FETCH_INV", payload: items})
export const inventoryState__setEquipment = items => ({type: "FETCH_EQP", payload: items})