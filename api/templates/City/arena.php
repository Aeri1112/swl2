<script>
function countdown(element, minutes, seconds) {
    // Fetch the display element
    var el = document.getElementById(element);
    var second = seconds;
    // Set the timer
    var interval = setInterval(function() {


        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(seconds / (60 * 60 * 24));
        var hours = Math.floor((seconds % (60 * 60 * 24)) / (60 * 60));
        var minutes = Math.floor((seconds % (60 * 60)) / (60));
        var second = Math.floor((seconds % (60)));
		
		if(second < 10)
		{
			second = "0"+second;
		}
		if(minutes < 10)
		{
			minutes = "0"+minutes;
		}
		
        el.innerHTML = minutes + "m " + second + "s ";
		
		if(element == <?= $user_fight_id_counter ?>)
		{
			document.title="fighting - "+minutes+":"+second;
		}
		
        seconds--;
        if(seconds == 0) {
            if(minutes == 0) {
                (el.innerHTML = "finish");     
				document.title="finish";
                clearInterval(interval);
                return;
            } else {
                minutes--;
                seconds = 60;
            }
        }
    }, 1000);
}

function calclvl()
{
    var lower = document.getElementById("lower");
    var higher = document.getElementById("higher");
    var lower_text = document.getElementById("lower_text");
    var higher_text = document.getElementById("higher_text");
    var choosen_res = document.getElementById("restriction").options["selectedIndex"];

    if(lower.checked == true && choosen_res == 1)
    {
        lower_text.innerHTML = " => Leveluntergrenze: <?php if(isset($res["1_low"])) echo $res["1_low"]; ?>";
    }
    else if(lower.checked == true && choosen_res == 2)
    {
        lower_text.innerHTML = " => Leveluntergrenze: <?php if(isset($res["2_low"])) echo $res["2_low"]; ?>";
    }
    else if(lower.checked == true && choosen_res == 3)
    {
        lower_text.innerHTML = " => Leveluntergrenze: <?php if(isset($res["3_low"])) echo $res["3_low"]; ?>";
    }
    else
    {
        lower_text.innerHTML = "";
    }

    if(higher.checked == true && choosen_res == 1)
    {
        higher_text.innerHTML = " => Levelobergrenze: <?php if(isset($res["1_high"])) echo $res["1_high"]; ?>";
    }
    else if(higher.checked == true && choosen_res == 2)
    {
        higher_text.innerHTML = " => Levelobergrenze: <?php if(isset($res["2_high"])) echo $res["2_high"]; ?>";
    }
    else if(higher.checked == true && choosen_res == 3)
    {
        higher_text.innerHTML = " => Levelobergrenze: <?php if(isset($res["3_high"])) echo $res["3_high"]; ?>";
    }
    else
    {
        higher_text.innerHTML = "";
    }
}

function calcleasecost() {

  var cost = 0;
  var choosen_res = document.getElementById("restriction").options["selectedIndex"];
  var choosen_type = document.getElementById("type").options["selectedIndex"];
  var lower = document.getElementById("lower");
  var higher = document.getElementById("higher");

  if (choosen_type == 0) { cost += 2; }
  if (choosen_type == 1) { cost += 10; }

  if (choosen_res == 0) { cost += 0; }
  if (choosen_res == 1 && (lower.checked || higher.checked)) { cost += 5; }
  if (choosen_res == 2 && (lower.checked || higher.checked)) { cost += 3; }
  if (choosen_res == 3 && (lower.checked || higher.checked)) { cost += 1; }

  var coststring = (Math.round(cost*100)/100).toFixed(0);
  document.getElementById("costs").innerHTML=coststring;
}
</script>

<?php 
    if(!empty($fight_report))
    {
        echo $fight_report["report"];
        echo $this->Html->link('verwerfen','/city/arena/clear');
        return;
    }
    if(!empty($open) && $open == "yes")
    {
        echo $this->Form->create();
            echo $this->Form->select('type', ['duel' => 'Duel','coop' => 'Coop'], ['class' => 'custom-select', 'id' => 'type', 'onchange' => 'calcleasecost();', 'style' => 'width:51%;']);
            echo $this->Form->select('restriction', ['keine', 'level bis 15%','level bis 27%','level bis 39%'], ['onchange' => 'calclvl(); calcleasecost();', 'id' => 'restriction', 'class' => 'custom-select', 'style' => 'width:51%;']);
            echo "<div class='custom-control custom-checkbox'>";
                echo $this->Form->checkbox('lower', ['hiddenField' => false, 'id' => 'lower', 'class' => 'custom-control-input', 'onchange' => 'calclvl(); calcleasecost();']);
                echo $this->Form->label('lower', null, ['class'=>"custom-control-label"]);
                echo"<div class=d-inline id=lower_text>";
                echo"</div>";
            echo "</div>";
            echo "<div class='custom-control custom-checkbox'>";
                echo $this->Form->checkbox('higher', ['hiddenField' => false, 'id' => 'higher', 'class' => 'custom-control-input', 'onchange' => 'calclvl(); calcleasecost();']);
                echo $this->Form->label('higher', null, ['class'=>"custom-control-label"]);
                echo"<div class=d-inline id=higher_text>";
                echo"</div>";
            echo "</div>";
            echo "<div>";
                echo "Kosten: <div class=d-inline id=costs>2</div>";
            echo "</div>";

        echo $this->Form->button('Fight!', ['type' => 'submit', 'class' => 'btn btn-primary']);
        echo $this->Form->end();
        return;
    }
 ?>
<?= $this->Flash->render() ?>
<div class="table-responsive-sm">
    <table class="table table-striped">
        <caption>List of Fights</caption>
        <thead>
            <tr>
                <th scope="col">
                    Name
                </th>
                <th scope="col">
                    Typ
                </th>
                <th scope="col">
                    Wetteinsatz
                </th>
                <th scope="col">
                    Status
                </th>
                <th scope="col">
                    Aktion
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(isset($fights))
            {
                foreach ($fights as $key => $fight)
                {
                ?>
                <tr>
                    <td scope="row">
                        <?php
                        if($fight->type == "duel")
                        {
                            foreach ($fighters as $key => $fighter)
                            {
                                if($fighter["fightid"] == $fight->fightid)
                                {
                                    echo $fighter["char"]["username"]." (".$fighter["skills"]["level"].")";
                                    if($fighter["teamid"] == 0) echo " vs. ";
                                }
                            }
                        }
                        elseif($fight->type == "coop")
                        {
                            foreach ($fighters as $key => $fighter)
                            {
                                if($fighter["fightid"] == $fight->fightid)
                                {
									if($_SESSION["Auth"]["User"]["id"] == 20) {}
                                    if($fighter["teamid"] == 0 && $fighter["position"] == 0)
                                    {
                                        $pos_0_0[$fight->fightid] = true;
										
                                        $string_line_0_0 = $fighter["char"]["username"]." (".$fighter["skills"]["level"]."), ";
                                        $username_0_0[$fight->fightid] = $fighter["char"]["username"];
                                    }
                                    if($fighter["teamid"] == 0 && $fighter["position"] == 1)
                                    {
                                        $pos_0_1[$fight->fightid] = true;
                                        $string_line_0_1 = $fighter["char"]["username"]." (".$fighter["skills"]["level"].") vs. ";
                                        $username_0_1[$fight->fightid] = $fighter["char"]["username"];
                                    }
                                    if($fighter["teamid"] == 1 && $fighter["position"] == 0)
                                    {
                                        $pos_1_0[$fight->fightid] = true;
                                        $string_line_1_0 = $fighter["char"]["username"]." (".$fighter["skills"]["level"]."), ";
                                        $username_1_0[$fight->fightid] = $fighter["char"]["username"];
                                    }
                                    if($fighter["teamid"] == 1 && $fighter["position"] == 1)
                                    {
                                        $pos_1_1[$fight->fightid] = true;
                                        $string_line_1_1 = $fighter["char"]["username"]." (".$fighter["skills"]["level"].")";
                                        $username_1_1[$fight->fightid] = $fighter["char"]["username"];
                                    }
                                }
                            }
                            $string_line = "";
                            if(isset($pos_0_0[$fight->fightid])) $string_line .= $string_line_0_0; else $string_line .= "..., ";
                            if(isset($pos_0_1[$fight->fightid])) $string_line .= $string_line_0_1; else $string_line .= "... vs. "; 
                            if(isset($pos_1_0[$fight->fightid])) $string_line .= $string_line_1_0; else $string_line .= "..., "; 
                            if(isset($pos_1_1[$fight->fightid])) $string_line .= $string_line_1_1; else $string_line .= "..."; 
                            
                            echo $string_line;
                        }
                        ?>
                    </td>
                    <td>
                        <?= $fight->type ?>
                    </td>
                    <td>
                        <?= $fight->bet ?>
                    </td>
                    <td>
                        <?= $fight->status ?>
                    </td>
                    <td>
                        <?php 
                            if($fight->type == "duel" && $fight->status == "open") 
                            {
                                $join = "attack";
                                echo $this->Html->link(''.$join.'','/city/arena/join/'.$fight->fightid);
                            }
                            elseif($fight->type == "duel" && ($fight->status == "preparing") OR $fight->status == "fighting")
                            {
                                $sec[$fight->fightid] = (($fight->opentime + $fight->startin)-time());
                            
                                echo "<div id=".$fight->fightid."></div>";
                                ?>
                                <script>
                                    countdown(<?= $fight->fightid ?>, 0, <?= $sec[$fight->fightid] ?>)
                                </script>
                                <?php
                            }
                            
                            if($fight->type == "coop" && $fight->status == "open")
                            {
								//Link team 1
                                if(!isset($pos_0_0[$fight->fightid]) && !isset($pos_0_1[$fight->fightid]))
                                {
                                    $join = "join Team 1";
                                    $side = 0;
                                }
                                else
                                {
                                    if(!isset($pos_0_0[$fight->fightid]))
									{
										$join = "join ".$username_0_1[$fight->fightid];
										$side = 0;
									}
                                    if(!isset($pos_0_1[$fight->fightid]))
									{
										$join = "join ".$username_0_0[$fight->fightid];
										$side = 0;
									}
                                }

                                if(isset($pos_0_0[$fight->fightid]) && isset($pos_0_1[$fight->fightid]))
                                {
                                    
                                }
                                else
                                {
                                    echo $this->Html->link(''.$join.'','/city/arena/join/'.$fight->fightid.'/'.$side.'');
                                }
                                
								//LInk Team2
                                //zweiter Join-Link notwendig
                                if(!isset($pos_1_0[$fight->fightid]) && isset($pos_1_1[$fight->fightid]))
                                {
									if(!isset($pos_0_0[$fight->fightid]) || !isset($pos_0_1[$fight->fightid])) echo " | ";
                                    echo $this->Html->link('join '.$username_1_1[$fight->fightid].'','/city/arena/join/'.$fight->fightid.'/1');
                                }
                                elseif(!isset($pos_1_1[$fight->fightid]) && isset($pos_1_0[$fight->fightid]))
                                {
                                    if(!isset($pos_0_0[$fight->fightid]) || !isset($pos_0_1[$fight->fightid])) echo " | ";
                                    echo $this->Html->link('join '.$username_1_0[$fight->fightid].'','/city/arena/join/'.$fight->fightid.'/1');
                                }
								elseif(!isset($pos_1_0[$fight->fightid]) && !isset($pos_1_1[$fight->fightid]))
								{
									if(!isset($pos_0_0[$fight->fightid]) || !isset($pos_0_1[$fight->fightid])) echo " | ";
									echo $this->Html->link('join Team 2','/city/arena/join/'.$fight->fightid.'/1');
								}
                            }
                            elseif($fight->type == "coop" && ($fight->status == "preparing") OR $fight->status == "fighting")
                            {
                                $sec[$fight->fightid] = (($fight->opentime + $fight->startin)-time());
                            
                                echo "<div id=".$fight->fightid."></div>";
                                ?>
                                <script>
                                    countdown(<?= $fight->fightid ?>, 0, <?= $sec[$fight->fightid] ?>)
                                </script>
                                <?php
                            }
                        ?>
                    </td>
                </tr>
                <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>

<div class="text-right">
    <?= $this->Html->link('Kampf eröffnen','/city/arena/open') ?> - <?= $this->Html->link('Kampf abbrechen','/city/arena/cancel') ?>
</div>
<?php 
        echo "last fights:<br><div class='small'>";
        foreach ($fight_reps as $key => $rep)
		{
			echo $this->Time->format(
				  $rep->zeit,
				  'dd.MM. HH:mm',
				  null,
				  'Europe/Berlin'
				);
			echo " Uhr ";
            echo $this->Html->link($rep->headline,'/fight/reada/'.$rep->md5.'',['class' => 'button', 'target' => '_blank']);
			echo "<br>";
        }		
		echo "</div>";
?>
