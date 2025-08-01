// firebase-config.js

// Load Firebase SDK from CDN in your HTML file (not here)

// Your Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyAduADfKzwtT7pqzct-aeqZLLzmmEaKS3s",
  authDomain: "mediconnect-pomelo.firebaseapp.com",
  projectId: "mediconnect-pomelo",
  storageBucket: "mediconnect-pomelo.appspot.com",
  messagingSenderId: "361684866524",
  appId: "1:361684866524:web:cd725aa5f209aea59b2b19"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Google Sign-In (redirect if not signed in)
firebase.auth().onAuthStateChanged(function(user) {
  if (!user) {
    const provider = new firebase.auth.GoogleAuthProvider();
    firebase.auth().signInWithPopup(provider).catch(console.error);
  } else {
    console.log("Signed in as:", user.displayName);
  }
});


