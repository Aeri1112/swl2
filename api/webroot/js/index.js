var $messages = $('.messages-content'),
    d, h, m,
    i = 0;

    myName = $('#username').val();

$(window).load(function() {

  $messages.mCustomScrollbar();

  firebase.database().ref("messages").limitToLast(50).on("child_added", function (snapshot) {
    if (snapshot.val().sender == myName) {
      $('<div class="message message-personal"><div id="message-' + snapshot.key + '">' + snapshot.val().message + '</div></div>').appendTo($('.mCSB_container')).addClass('new');
      $('<div class="timestamp" align="right">' + snapshot.val().dt + '</div>').appendTo($('#message-' + snapshot.key + ''));
      $('.message-input').val(null);
    } else {
      $('<div class="message new"><div id="message-' + snapshot.key + '">' + snapshot.val().sender + ': ' + snapshot.val().message + '</div></div>').appendTo($('.mCSB_container')).addClass('new');
      $('<div class="timestamp" align="right">' + snapshot.val().dt + '</div>').appendTo($('#message-' + snapshot.key + ''));
    }
    
    
    updateScrollbar();
  });

});

function updateScrollbar() {
  $messages.mCustomScrollbar("update").mCustomScrollbar('scrollTo', 'bottom', {
    scrollInertia: 10,
    timeout: 0
  });
}

function setDate(){
  d = new Date()
  if (m != d.getMinutes()) {
    m = d.getMinutes();
    $('<div class="timestamp">' + d.getHours() + ':' + m + '</div>').appendTo($('.message:last'));
  }
}

function insertMessage() {
  msg = $('.message-input').val();
  if ($.trim(msg) == '') {
    return false;
  }

  sendMessage();
}

$('.message-submit').click(function() {
  insertMessage();
});

$(window).on('keydown', function(e) {
  if (e.which == 13) {
    insertMessage();
    return false;
  }
});