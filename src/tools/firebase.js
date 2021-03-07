import firebase from "firebase";
import "firebase/database";

// Your web app's Firebase configuration
var firebaseConfig = {
  apiKey: "AIzaSyCc4rEGglZzKlMQpDBhJ59OwSfBuVbGHKw",
  authDomain: "swlchat.firebaseapp.com",
  databaseURL: "https://swlchat.firebaseio.com",
  projectId: "swlchat",
  storageBucket: "swlchat.appspot.com",
  messagingSenderId: "658796691775",
  appId: "1:658796691775:web:61eb3c0f2af42b6998d38a"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);

export const database = firebase.database();
export const firestore = firebase.firestore();