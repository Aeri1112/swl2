<style type="text/css">
		#messages {
			height: 400px;
			background: whitesmoke;
			overflow: auto;
		}
		#chat-room-frm {
			margin-top: 10px;
		}
	</style>
	<div class="container">
		<div class="row">
			<div class="col-md">
				<div id="messages">
					<table id="chats" class="table table-striped">
					  <tbody>
						  <?php 
						  $userId = $_SESSION["Auth"]["User"]["id"];
					  		foreach ($chatrooms as $key => $chatroom) {

					  			if($userId == $chatroom['send_from']) {
					  				$from = "Me";
					  			} else {
					  				$from = $chatroom['send_from_name'];
					  			}
					  			echo '<tr><td valign="top" class="pt-0 pl-1"><div><strong>'.$from.'</strong></div><div>'.$chatroom['text'].'</div><td align="right" valign="top" class="pt-0 pl-0"><small><em>'.$chatroom['send'].'</em></small></td></tr>';
					  		}
					  	 ?>
					  </tbody>
					</table>
				</div>
					
				<form id="chat-room-frm" method="post" action="">
				<input type="hidden" name="userId" id="userId" value=<?= $userId ?>>
					<div class="form-group">
                    	<textarea class="form-control" id="msg" name="msg" placeholder="Enter Message"></textarea>
	                </div>
	                <div class="form-group">
	                    <input type="button" value="Send" class="btn btn-success btn-block" id="send" name="send">
	                </div>
			    </form>
			</div>
		</div>
	</div>
<script>
	var chatMessages = document.getElementById("messages");
	//Scroll down
	chatMessages.scrollTop = chatMessages.scrollHeight;
</script>
<script type="text/javascript">
	$(document).ready(function(){
		var conn = new WebSocket('wss://localhost:8091');
		conn.onopen = function(e) {
		    console.log("Connection established!");
		};

		conn.onmessage = function(e) {
		    console.log(e.data);
		    var data = JSON.parse(e.data);
		    var row = '<tr><td valign="top" class="pt-0 pl-1"><div><strong>' + data.from +'</strong></div><div>'+data.msg+'</div><td align="right" valign="top" class="pt-0 pl-0"><small><em>'+data.dt+'</em></small></td></tr>';
		    $('#chats > tbody').append(row);

			//Scroll down
			chatMessages.scrollTop = chatMessages.scrollHeight;
		};

		conn.onclose = function(e) {
			console.log("Connection Closed!");
		}

		$("#send").click(function(){
			var userId 	= $("#userId").val();
			var msg 	= $("#msg").val();
			var data = {
				userId: userId,
				msg: msg
			};
			conn.send(JSON.stringify(data));
			$("#msg").val("");
		});

		$("#leave-chat").click(function(){
			var userId 	= $("#userId").val();
			$.ajax({
				url:"action.php",
				method:"post",
				data: "userId="+userId+"&action=leave"
			}).done(function(result){
				var data = JSON.parse(result);
				if(data.status == 1) {
					conn.close();
					location = "index.php";
				} else {
					console.log(data.msg);
				}
				
			});
			
		})

	})
</script>