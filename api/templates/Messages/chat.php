<div class="row">
    <div class="col-md-3">
        <h3 class="bg-info p-1">Unterhaltungen</h3>

        <div class="txtb">
            <label for="search">search (exact username)</label>
            <input autocomplete="off" name="search" id="search" type="text" name="username" onkeyup="searchFor(this.value);"/>
        </div>
        <div id="playerline"></div>    

        <?php 
        if(!empty($chatList))
        {
        ?>
            <ul class="list-group list-group-flush">
                    <?php
                        foreach ($chatList as $key => $chatMember) {
                    ?>
                        <li class="list-group-item">
                        <?= $JediUserChars->get($chatMember)["username"] ?> <span class="label label-success"><?php if($unread[$chatMember] > 0) echo $unread[$chatMember]; ?></span>
                        <button type="button" class="btn btn-info btn-xs start_chat" data-touserid="<?= $chatMember ?>" data-tousername="<?= $JediUserChars->get($chatMember)["username"] ?>">Start Chat</button>
                        </li> 
                <?php               
                }
                ?>
            </ul>
        <?php
        }
        ?>
    </div>
    <div class="col-md-9" style='background-color: #ffffff;'>
        <div class="scroll" id="user_model_details">

        </div>
    </div>
</div>
<?php
?>
<script>
$(document).ready(function(){
    $.ajaxSetup({
                    headers:
                    { 'X-CSRF-TOKEN': csrfToken }
                });
    setInterval(function(){
  update_chat_history_data();
 }, 1000);
function make_chat_dialog_box(to_user_id, to_user_name)
{
 var modal_content = '<div class="text-center">Unterhaltung mit '+to_user_name+'</div>';
 modal_content += '<div style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;" class="chat_history" data-touserid="'+to_user_id+'" id="chat_history_'+to_user_id+'">';
 modal_content += fetch_user_chat_history(to_user_id);
 modal_content += '</div>';
 modal_content += '<div class="form-group">';
 modal_content += '<textarea name="chat_message_'+to_user_id+'" id="chat_message_'+to_user_id+'" class="form-control"></textarea>';
 modal_content += '</div><div class="form-group" align="right">';
 modal_content+= '<button type="button" name="send_chat" id="'+to_user_id+'" class="btn btn-info send_chat">Send</button></div></div>';
 $('#user_model_details').html(modal_content);
}

$(document).on('click', '.start_chat', function(){
 var to_user_id = $(this).data('touserid');
 var to_user_name = $(this).data('tousername');
 make_chat_dialog_box(to_user_id, to_user_name);
 $("#user_dialog_"+to_user_id).dialog({
  autoOpen:false,
  width:400
 });
 $('#user_dialog_'+to_user_id).dialog('open');
});

$(document).on('click', '.send_chat', function(){
  var send_to = $(this).attr('id');
  var text = $('#chat_message_'+send_to).val();
  $.ajax({
   url:"<?php echo $this->Url->build(['controller' => 'messages', 'action' => 'send']); ?>",
   method:"POST",
   data:
   {
       send_to:send_to,
       text:text,
       type:"send"
    },
   success:function(data)
   {
    $('#chat_message_'+send_to).val('');
    $('#chat_history_'+send_to).html(data);
   }
  })
 });

 function fetch_user_chat_history(send_to)
 {
  $.ajax({
   url:"<?php echo $this->Url->build(['controller' => 'messages', 'action' => 'send']); ?>",
   method:"POST",
   data:{send_to:send_to},
   success:function(data){
    $('#chat_history_'+send_to).html(data);
   }
  })
 }
 function update_chat_history_data()
 {
  $('.chat_history').each(function(){
   var send_to = $(this).data('touserid');
   fetch_user_chat_history(send_to);
  });
 }

 $(document).on('click', '.ui-button-icon', function(){
  $('.user_dialog').dialog('destroy').remove();
 });
});
function searchFor(suchbegriff)
 {
     if(suchbegriff.length > 2)
     {
        $.ajax({
            url:"<?php echo $this->Url->build(['controller' => 'messages', 'action' => 'search']); ?>",
            method:"POST",
            data:{
                suchbegriff:suchbegriff
                },
            success:function(data){
                $('#playerline').html(data);
            }
            })
     }
 }
</script>