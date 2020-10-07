<div id="group_chat_dialog" title="Group Chat Window">
 <div id="group_chat_history" style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;">

 </div>
 <div class="form-group">
  <textarea name="group_chat_message" id="group_chat_message" class="form-control"></textarea>
 </div>
 <div class="form-group" align="right">
  <button type="button" name="send_group_chat" id="send_group_chat" class="btn btn-info">Send</button>
 </div>
</div>

<script>
$(document).ready(function(){
    $.ajaxSetup({
                    headers:
                    { 'X-CSRF-TOKEN': csrfToken }
                });
                setInterval(function(){
                fetch_group_chat_history();
                }, 5000);
                
    $('#send_group_chat').click(function(){
    var chat_message = $('#group_chat_message').val();
    var action = 'insert_data';
    if(chat_message != '')
    {
        $.ajax({
        url:"<?php echo $this->Url->build(['controller' => 'messages', 'action' => 'group_chat']); ?>",
        method:"POST",
        data:{chat_message:chat_message, action:action},
        success:function(data){
            $('#group_chat_message').val('');
            $('#group_chat_history').html(data);
        }
        })
    }
    });
    function fetch_group_chat_history()
    {
        var group_chat_dialog_active = $('#is_active_group_chat_window').val();
        var action = "fetch_data";
            $.ajax({
                url:"<?php echo $this->Url->build(['controller' => 'messages', 'action' => 'group_chat']); ?>",
                method:"POST",
                data:{action:action},
                success:function(data)
                {
                    $('#group_chat_history').html(data);
                }
            })
    }
    });
</script>