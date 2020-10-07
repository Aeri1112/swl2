<?php
use Cake\Datasource\ConnectionManager;

$connection = ConnectionManager::get('default');

$user = $connection->execute('SELECT username FROM jedi_user_chars WHERE userid = :id', ['id' => $_SESSION["Auth"]["User"]["id"]])->fetch('assoc');
?>
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-database.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#config-web-app -->

<script>
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

  firebase.database().ref("messages").on("child_removed", function (snapshot) {
    document.getElementById("message-" + snapshot.key).innerHTML = "This message has been deleted";
  });

  function deleteMessage(self) {
    var messageId = self.getAttribute("data-id");
    firebase.database().ref("messages").child(messageId).remove();
  }

  function sendMessage() {
    var message = document.getElementById("message").value;
    var dt = new Date();
    var h = dt.getHours();
	if(h < 10) var h = '0' + h;
    var m = dt.getMinutes();
	if(m < 10) var m = '0' + m;
    var month = dt.getMonth() + 1;
    var day = dt.getDate();
    var year = dt.getFullYear();
    var dtstring =  h + ':' + m + ', ' + day + '.' + month + '.' + year;
    firebase.database().ref("messages").push().set({
      "message": message,
      "sender": myName,
      "dt": dtstring
    });
    return false;
  }
</script>

<style>
  figure.avatar {
    bottom: 0px !important;
  }
  .btn-delete {
    background: red;
    color: white;
    border: none;
    margin-left: 10px;
    border-radius: 5px;
  }
</style>

<div class="chat">
  <div class="chat-title">
    <h1>Gruppenchat</h1>
    <h2>SWL</h2>
    <figure class="avatar">
      <img src="https://p7.hiclipart.com/preview/349/273/275/livechat-online-chat-computer-icons-chat-room-web-chat-others.jpg" /></figure>
  </div>
  <div class="messages">
    <div class="messages-content"></div>
  </div>
  <div class="message-box">
    <textarea type="text" class="message-input" id="message" placeholder="Type message..."></textarea>
    <input type="hidden" id="username" name="username" value="<?= $user["username"] ?>"></input>
    <button type="submit" class="message-submit">Send</button>
  </div>

<?= $this->Html->script("index") ?>