<div class="d-flex justify-content-center col-auto p-0">	
	<?= $alliance->name ?>
	 - <?= $alliance->short ?>
</div>
<br>
	<div class="d-flex justify-content-center">
		<div class="m-1">
			Leader: 
		</div>
		<div class="m-1">
			<?= $leader->username ?>
		</div>
	</div>
	<div class="d-flex justify-content-center">
		<div class="m-1">
			Co-Leader: 
		</div>
		<div class="m-1">
			<?php if(isset($coleader)) echo $coleader->username; else echo "No";?>
		</div>
	</div>
	<br>
	Members:
	<div class="row">
	<?php
		foreach($members as $key => $member)
		{
			if($char->userid == $alliance->leader OR $char->userid == $alliance->coleader)
			{
				echo "<div class='col' data-toggle='modal' data-target='#exampleModal' data-userid='".$member->userid."' data-whatever='".$member->username."'>";
					if($member->online_status == 1) echo $this->Html->image("gruen.png", ['style' => 'width:10px; height:10px;', 'pathPrefix' => 'webroot/img/']);
					else echo $this->Html->image("rot.png", ['style' => 'width:10px; height:10px;', 'pathPrefix' => 'webroot/img/']);
					echo "<a href='#'> ".$member->username."</a>";
				echo "</div>";
			}
			else
			{
				echo "<div class='col'>";
					echo $member->username;
				echo "</div>";
			}
		}
	?>
	</div>
	<br>
	<?php
	if($user_alliance == $alliance->id)
	{
		echo "<div class='row d-flex justify-content-center'>";
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'Überblick',
				'/alliances'
			);
			echo "</div>";
			
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'Jagd',
				'/alliances/raid'
			);
			echo "</div>";
		
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'Mitglieder',
				'/alliances/view/'.$alliance->id.''
			);
			echo "</div>";
			
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'auflisten',
				'/alliances/all'
			);
			echo "</div>";
			
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'Forschung',
				'alliances/research'
			);
			echo "</div>";
			
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'verlassen',
				'alliances/leave'
			);
			echo "</div>";
		echo "</div>";
	}
	elseif($user_alliance != "" && $user_alliance != 0)
	{
		echo "<div class='d-flex justify-content-center'>";
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'zurück',
				'/alliances/all'
			);
			echo "</div>";
		echo "</div>";
	}
	else
	{
		echo "<div class='d-flex justify-content-center'>";
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'zurück',
				'/alliances'
			);
			echo "</div>";
		echo "</div>";
	}
	?>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?= $this->Form->create() ?>
		<input type="hidden" id="userid" name="userid">
			
			<?php
				if($char->userid == $alliance->leader)
				{
			?>
			<div class="custom-control custom-radio">
			  <input type="radio" id="customRadio1" name="leader" class="custom-control-input">
			  <label class="custom-control-label" for="customRadio1">Promote to leader</label>
			</div>
			<?php
				}
				
				if($char->userid == $alliance->leader OR $char->userid == $alliance->coleader)
				{
			?>
			<div class="custom-control custom-radio">
			  <input type="radio" id="customRadio2" name="coleader" class="custom-control-input">
			  <label class="custom-control-label" for="customRadio2">Promote to co-leader</label>
			</div>
			<div class="custom-control custom-radio">
			  <input type="radio" id="customRadio3" name="kick" class="custom-control-input">
			  <label class="custom-control-label" for="customRadio3">kick this member</label>
			</div>
			<?php
				}
			?>
      </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
				<button type="submit" class="btn btn-primary">save</button>
		</form>
			</div>
    </div>
  </div>
</div>
<script>
	$('#exampleModal').on('show.bs.modal', function (event) {
	  var button = $(event.relatedTarget) // Button that triggered the modal
	  var recipient = button.data('whatever') // Extract info from data-* attributes
	  var userid = button.data('userid')
	  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
	  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
	  var modal = $(this)
	  modal.find('.modal-title').text('modify ' + recipient)
	  modal.find('#userid').val(userid)
	})
</script>