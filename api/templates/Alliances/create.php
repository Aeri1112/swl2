<?php
	echo $this->Flash->render();
	
	if($no_alliance == true)
	{
		echo "To create a alliance you need to pay 5000 Credits<br>";
		
		echo $this->Form->create();
		echo $this->Form->label('name', 'Allianzname');
		echo $this->Form->text('a_name', ['id' => 'name', 'class' => 'form-control']);
		
		echo $this->Form->label('tag', 'Allianzkürzel');
		echo $this->Form->text('a_tag', ['id' => 'tag', 'class' => 'form-control']);
		
		echo $this->Form->label('pw', 'Passwort');
		echo $this->Form->text('password', ['id' => 'pw', 'class' => 'form-control']);
		echo $this->Form->submit('erstellen', ["class" => "mt-1 btn btn-primary"]);
		echo $this->Form->end();
	}
?>