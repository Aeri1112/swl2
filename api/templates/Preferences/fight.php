<?php
	echo $this->Form->create();
	
	echo $this->Form->label('stance', 'Übliche Lichtschwerthaltung');	
	echo $this->Form->select("stance", [
										0 => "Tief",
										1 => "Mittig",
										2 => "Hoch"
										], ["class" => "custom-select", "id" => "stance", "value" => $stance]);
	
	echo $this->Form->label('initiative', 'Initiative');										
	echo $this->Form->select("initiative", [
										0 => "Ruhig",
										1 => "Normal",
										2 => "Aggressiv"
										], ["class" => "custom-select", "id" => "initiative", "value" => $initiative]);
	
	echo $this->Form->label('heroic', 'Heldenhaftigkeit');
	echo $this->Form->select("heroic", [
										0 => "Feige",
										1 => "Normal",
										2 => "Blutdurstig",
										3 => "Riskant"
										], ["class" => "custom-select", "id" => "heroic", "value" => $heroic]);
	
	echo $this->Form->label('inno', 'Unschuldige beachten');
	echo $this->Form->select("innocent", [
										0 => "Nein",
										1 => "Ja"
										], ["class" => "custom-select", "id" => "inno", "value" => $inno]);
	
	echo $this->Form->label('surro', 'Umgebung beschädigen');
	echo $this->Form->select("surroundings", [
										0 => "Nein",
										1 => "Ja"
										], ["class" => "custom-select", "id" => "surro", "value" => $surro]);
	
	echo $this->Form->label('prefereddef', 'Bevorzugte Defensivmacht');
	echo $this->Form->select("prefereddef", $def_options, ["class" => "custom-select", "id" => "prefereddef", "value" => $prefereddef]);
	
	echo $this->Form->label('preferedoff', 'Bevorzugte Offensivmacht');
	echo $this->Form->select("preferedoff", $off_options, ["class" => "custom-select", "id" => "preferedoff", "value" => $preferedoff]);
	
	echo $this->Form->label('kill1', 'Macht unterdrücken');
	echo $this->Form->select("kill1", $options, ["class" => "custom-select", "id" => "kill1", "value" => $switchoff_1]);
	
	echo $this->Form->submit("ändern", ["class" => " mt-2 mb-2 btn btn-primary"]);
	echo $this->Form->end();
?>