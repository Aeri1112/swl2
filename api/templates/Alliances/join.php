<?php
	echo $this->Flash->render();
	
	if($no_alliance == true)
	{
		echo "<div class='container'>";
			echo "Do you like to join ".$alliance->name."?<br>
					Please enter their password<br>";
		
			echo $this->Form->create();
			echo $this->Form->text('password', ['autocomplete' => 'off', 'id' => 'pw', 'class' => 'form-control']);
			echo $this->Form->submit('beitreten', ["class" => "mt-1 btn btn-primary"]);
			echo $this->Form->end();
		
		echo "</div>";
	}
?>