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

        el.innerHTML = days + "d " + hours + "h " + minutes + "m " + second + "s ";
        seconds--;
        if(seconds <= 0) {
            if(minutes <= 0) {
                (el.innerHTML = "finish");     

                clearInterval(interval);
                return;
            } else {
                minutes--;
                seconds = 60;
            }
        }
    }, 1000);
}
</script>
<?php
	echo $this->Flash->render();
	if(($view == "" OR $view == "selling" OR $view == "bids") && !isset($id))
	{
		?>
		<div class="table-responsive-md">
			<table class="table table-sm table-striped">
				<thead>
					<tr>
						<th scope="col">Item</th>
						<th scope="col">Stats</th>
						<th scope="col">Verkäufer</th>
						<th scope="col">Gebot</th>
						<th scope="col">Sofortkauf</th>
						<th scope="col">Direktkauf</th>
						<th scope="col">Höchstbietender</th>
						<th scope="col">Ablauf</th>
						<th scope="col"></th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($all as $key => $auction)
						{
							$stat1 = null;
							$stat2 = null;
							$stat3 = null;
							$stat4 = null;
							$stat5 = null;
							$dmg = null;
							
							if($auction->item->mindmg != 0)
							{
								$dmg = "Dmg: ".$auction->item->mindmg." - ".$auction->item->maxdmg."<br>";
							}
							if($auction->item->stat1 != "") $stat1 = $auction->item->stat1."<br>";
							if($auction->item->stat2 != "") $stat2 = $auction->item->stat2."<br>";
							if($auction->item->stat3 != "") $stat3 = $auction->item->stat3."<br>";
							if($auction->item->stat4 != "") $stat4 = $auction->item->stat4."<br>";
							if($auction->item->stat5 != "") $stat5 = $auction->item->stat5;
				
							if($auction->instant_price == "0") 
							{
								$instant_price = "-";
							}
							else
							{
								$instant_price = $auction->instant_price." Cr.";	
							}
							
							if($auction->direct_price == "0") 
							{
								$direct_price = "-";
							}
							else
							{
								$direct_price = $auction->direct_price." Cr.";	
							}
							
							if($auction->act_price_user == "0") 
							{
								$max_bet_user = "-";
							}
							else
							{
								$max_bet_user = $auction->max_bet_user->username;
							}
					?>
						<tr>
							<td>
								<?= $auction->item->name ?>
							</td>
							<td>
								<?php echo "$dmg $stat1 $stat2 $stat3 $stat4 $stat5"; ?>
							</td>
							<td>
								<?= $auction->seller->username ?>
							</td>
							<td>
								<?= $auction->act_price." Cr. (".$auction->bids.")" ?>
							</td>
							<td>
								<?= $instant_price ?>
							</td>
							<td>
								<?= $direct_price ?>
							</td>
							<td>
								<?= $max_bet_user ?>
							</td>
							<td>
								<?php
								echo "<div id=".$auction->auctionid."></div>";
								?>
								<script>
									countdown(<?= $auction->auctionid ?>, 0, <?php echo $auction->endtime-time(); ?>)
								</script>
							</td>
							<td>
								<?= $this->Html->link(
									'go-to',
									'city/auction?id='.$auction->auctionid.''
								) ?>
							</td>						
						</tr>
					<?php
						}
					?>
				</tbody>
			</table>
		</div>
	<?php
	}
	elseif($view == "bids")
	{
		
	}
	elseif($view == "bought")
	{
		?>
		<table class="table table-striped">
		  <thead>
			<tr>
			  <th scope="col">Item</th>
			  <th scope="col">Verkäufer</th>
			  <th scope="col">Preis</th>
			  <th scope="col">Art</th>
			  <th scope="col">Zeit</th>
			</tr>
		  </thead>
		  <tbody>
		  <?php
		foreach($bought as $key => $bought_item)
		{
			?>
				<tr>
				  <td><?= $bought_item->item ?></td>
				  <td><?= $bought_item->seller ?></td>
				  <td><?= $bought_item->price ?></td>
				  <td><?= $bought_item->type_of_buy ?></td>
				  <td><?= $bought_item->time ?></td>
				</tr>
			<?php
		}
			?>
			</tbody>
		</table>
		<?php
	}
	elseif($view == "sold")
	{
		?>
		<table class="table table-striped">
		  <thead>
			<tr>
			  <th scope="col">Item</th>
			  <th scope="col">Käufer</th>
			  <th scope="col">Preis</th>
			  <th scope="col">Art</th>
			  <th scope="col">Zeit</th>
			</tr>
		  </thead>
		  <tbody>
		  <?php
		foreach($sold as $key => $sold_item)
		{
			?>
				<tr>
				  <td><?= $sold_item->item ?></td>
				  <td><?= $sold_item->buyer ?></td>
				  <td><?= $sold_item->price ?></td>
				  <td><?= $sold_item->type_of_buy ?></td>
				  <td><?= $sold_item->time ?></td>
				</tr>
			<?php
		}
			?>
			</tbody>
		</table>
		<?php
	}
	elseif($view == "new")
	{
		if(!isset($itemid))
		{
			?>
			<ul class="nav nav-tabs" id="myTab" role="tablist">
			  <li class="nav-item">
				<a class="nav-link" id="tab1" href="<?= $this->Url->build(["controller" => "city","action" => "auction", "?" => ["i" => "weapons", "view" => "new"]]) ?>">Weapons</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" id="tab2" href="<?= $this->Url->build(["controller" => "city","action" => "auction", "?" => ["i" => "rings", "view" => "new"]]) ?>">Rings</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" id="tab3" href="<?= $this->Url->build(["controller" => "city","action" => "auction", "?" => ["i" => "bots", "view" => "new"]]) ?>">Drohnen</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" id="tab4" href="<?= $this->Url->build(["controller" => "city","action" => "auction", "?" => ["i" => "books", "view" => "new"]]) ?>">Bücher</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" id="tab5" href="<?= $this->Url->build(["controller" => "city","action" => "auction", "?" => ["i" => "misc", "view" => "new"]]) ?>">Verschiedenes</a>
			  </li>
			</ul>
				<?php if(isset($img)){ ?>
					<div class="table-responsive-md">    
						<table class="table table-sm">
						<thead>
							<tr>
								<th>
									<?php echo $this->Paginator->sort('name', 'name', ['direction' => 'desc', 'escape' => false]); ?>
								</th>
								<?php if($img == "weapons") { ?>
								<th>
									<?php echo $this->Paginator->sort('mindmg', 'mindmg', ['direction' => 'desc', 'escape' => false]); ?>
								</th>
								<th>
									<?php echo $this->Paginator->sort('maxdmg', 'maxdmg', ['direction' => 'desc', 'escape' => false]); ?>
								</th>
								<?php } ?>
								<th>
									<?php echo $this->Paginator->sort('price', 'price', ['direction' => 'desc', 'escape' => false]); ?>
								</th>
								<th>
									<?php echo $this->Paginator->sort('qlvl', 'quality', ['direction' => 'desc', 'escape' => false]); ?>
								</th>
								<th>
									<?php echo $this->Paginator->sort('reql', 'req. level', ['direction' => 'desc', 'escape' => false]); ?>
								</th>
								<th>
									<?php echo $this->Paginator->sort('reqs', 'req. skill', ['direction' => 'desc', 'escape' => false]); ?>
								</th>
								<th>
									<?php echo $this->Paginator->sort('stat1_value', 'stat1', ['direction' => 'desc', 'escape' => false]); ?>
								</th>
								<th>
									<?php echo $this->Paginator->sort('stat2_value', 'stat2', ['direction' => 'desc', 'escape' => false]); ?>
								</th>
								<th>
									<?php echo $this->Paginator->sort('stat3_value', 'stat3', ['direction' => 'desc', 'escape' => false]); ?>
								</th>
								<th>
									<?php echo $this->Paginator->sort('stat4_value', 'stat4', ['direction' => 'desc', 'escape' => false]); ?>
								</th>
								<th>
									<?php echo $this->Paginator->sort('stat5_value', 'stat5', ['direction' => 'desc', 'escape' => false]); ?>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($items as $key => $item): ?>
							<tr>
								<td>
									<?= $this->Html->image($item->img.".gif", ['width' => '19px', 'style' => 'vertical-align:sub;', 'pathPrefix' => 'webroot/img/items/'.$img.'/']) ?>  
										<?= 
											$this->Form->postLink(
											$item->name,
											['action' => 'auction?view=new'],
											['data' => ["img" => $img, "itemid" => $item->itemid]]) 
									?>
								</td>
								<?php if($img == "weapons") { ?>
									<td>
										<?= $item->mindmg ?>
									</td>
									<td>
										<?= $item->maxdmg ?>
									</td>
								<?php } ?>
								<td>
									<?= $item->price ?>
								</td>
								<td>
									<?= $item->qlvl ?>
								</td>
								<td>
									<?php if($char->skills->level < $item->reql) {echo "<span class=text-danger>";} echo $item->reql; ?>
								</td>
								<td>
									<?php if($char->skills->dex < $item->reqs) {echo "<span class=text-danger>";} echo $item->reqs; ?>
								</td>
								<td>
									<?= $item->stat1_mod ?> <?= $item->stat1_stat ?> <?php if($item->stat1_value != 0) echo $item->stat1_value; ?>
								</td>
								<td>
									<?= $item->stat2_mod ?> <?= $item->stat2_stat ?> <?php if($item->stat2_value != 0) echo $item->stat2_value; ?>
								</td>
								<td>
									<?= $item->stat3_mod ?> <?= $item->stat3_stat ?> <?php if($item->stat3_value != 0) echo $item->stat3_value; ?>
								</td>
								<td>
									<?= $item->stat4_mod ?> <?= $item->stat4_stat ?> <?php if($item->stat4_value != 0) echo $item->stat4_value; ?>
								</td>
								<td>
									<?= $item->stat5_mod ?> <?= $item->stat5_stat ?> <?php if($item->stat5_value != 0) echo $item->stat5_value; ?>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
						</table>
					</div>
					<?php
						$paginator = $this->Paginator->setTemplates([
							'number' => '<li class="page-item"><a href="{{url}}" class="page-link">{{text}}</a></li>',
							'current' => '<li class="page-item active"><a href="{{url}}" class="page-link">{{text}}</a></li>',
							'first' => '<li class="page-item"><a href="{{url}}" class="page-link">&laquo;</a></li>',
							'last' => '<li class="page-item"><a href="{{url}}" class="page-link">&raquo;</a></li>',
							'prevActive' => '<li class="page-item"><a href="{{url}}" class="page-link">&lt;</a></li>',
							'nextActive' => '<li class="page-item"><a href="{{url}}" class="page-link">&gt;</a></li>'
						]);
					?>
					<div class="d-flex justify-content-start">
						<nav aria-label="Page navigation example">
							<ul class="pagination">
								<?php
								echo $paginator->first();

								if($paginator->hasPrev())
								{
									echo $paginator->prev();
								}

								echo $paginator->numbers(['modulus' => 3]);

								if($paginator->hasNext())
								{
									echo $paginator->next();
								}
								
								echo $paginator->last();
								?>
							</ul>
						</nav>
					</div>
				<?php }
				else
				{
					echo "Bitte wähle eine Kategorie aus der du ein Item verkaufen möchtest";
				}
		}
		elseif(isset($second_page) && $second_page == true) //zweite seite der Gebotserstellung zur bestätigung
		{
			echo "Bitte überprüfe deine Angaben";
			echo "<div class='row pt-1'>";
				echo $this->element("item", ["loot" => $item, "type" => $type, "class" => "col-md-3"]);
				echo "<div class='col-md-9'>
					Dauer: {$data["days"]} Tag(e)<br>
					Startpreis: {$data["start"]} Cr. <br>
					Preisschritte: {$data["step"]} Cr.<br>
					Sofortkauf: {$data["insta"]} Cr.<br>
					Direktkauf: {$data["direct"]} Cr.";
					
					echo $this->Form->create();
						echo $this->Form->hidden('duration', ["value" => $data["duration"]]);
						echo $this->Form->hidden('start', ["value" => $data["start"]]);
						echo $this->Form->hidden('insta', ["value" => $data["insta"]]);
						echo $this->Form->hidden('direct', ["value" => $data["direct"]]);
						echo $this->Form->hidden('step', ["value" => $data["step"]]);
						echo $this->Form->hidden('itemid', ["value" => $item->id]);
						echo $this->Form->hidden('itemtype', ["value" => $type]);
						echo $this->Form->button('verkaufen!', ['id' => 'sell', 'name' => 'sell', 'value' => 'sell', 'type' => 'submit', 'class' => 'btn btn-primary mt-1']);
					echo $this->Form->end();
				echo "</div>";
			echo "</div>";
		}
		else //item gewählt form seite ausgeben
		{
			echo "Der geschätze Wert liegt bei: $value Cr.";
			echo $this->Form->create(null, ["onsubmit" => "return validateForm()", "name" => "weiter"]);
			
			echo $this->Form->label('duration', 'Dauer');
			echo $this->Form->select('duration', ['86400' => '1 Tag','172800' => '2 Tage', '259500' => '3 Tage', '604800' => '7 Tage'], ['class' => 'custom-select', 'id' => 'duration']);

			echo $this->Form->control('start',['label' => 'Startpreis', 'type' => 'number','min' => $startprice, 'value' => $startprice, 'class' => 'form-control']);
			
			echo $this->Form->label('step', 'Preisschritte');
			echo $this->Form->select('step', ['100' => '100 Cr.','1000' => '1.000 Cr.', '10000' => '10.000 Cr.'], ['class' => 'custom-select', 'id' => 'step']);
			
			echo $this->Form->control('insta',['required' => 'true', 'label' => 'Sofortkauf', 'type' => 'number','min' => '0', 'value' => '0', 'class' => 'form-control']);
			
			echo $this->Form->control('direct',['required' => 'true', 'label' => 'Direktkauf', 'type' => 'number','min' => '0', 'value' => '0', 'class' => 'form-control']);
			
			echo $this->Form->button('weiter', ['type' => 'submit', 'class' => 'btn btn-primary mt-1']);
			
			echo $this->Form->hidden('itemid');
			
			echo $this->Form->hidden('itemtype', ["value" => $itemtype]);

			echo $this->Form->end();
		}
	}
	elseif($action == "cancel")
	{
		if($cancel_auc == true)
		{
			echo "Als Verkäufer kannst du diese Auktion aktuell noch beenden<br>";
			echo "<a href='auction?&id=".$auction->auctionid."&action=cancel&a=yes'>bestätigen</a>";
		}
		elseif($cancel_auc == false && $auction->act_price_user != "0")
		{
			echo "Es sind Gebote eingegangen, die Auktion kann nicht mehr vorzeitig beendet werden";
		}
		else
		{
			echo "Not your auction";
		}
	}
	//einzelne Auktion
	elseif(isset($id))
	{
		if($instant == true && $my_act == false)
		{
			$instant = "<a href='auction?id=".$auction->auctionid."&buy=instant'>kaufen</a>";
		}
		elseif($my_act == true)
		{
			$instant = "";
		}
		else
		{
			$instant = "nicht mehr verfügbar";
		}
		
		if($direct == true && $my_act == false)
		{
			$direct = "<a href='auction?id=".$auction->auctionid."&buy=direct'>kaufen</a>";
		}
		elseif($my_act == true)
		{
			$direct = "";
		}
		else
		{
			$direct = "nicht mehr verfügbar";
		}
		
		echo"<div class='row pt-1'>";
			echo $this->element("item", ["loot" => $loot, "type" => $auction->itemtype, "class" => "col-md-3"]);
			
			echo "<div class='col-md-9'>";
				echo"Verkäufer: ".$auction->seller->username."<br>";
				echo"Startpreis: ".$auction->startprice." Cr.";
				
				if($auction->act_price != 0)
				{
					echo"<br>aktueller Preis: ".$auction->act_price." Cr. von ".$auction->act_price_user->username."";
				}
				
				if($auction->direct_price != 0)
				{
					echo "<br>Direktkauf: ".$auction->direct_price." Cr. $direct";
				}
				
				if($auction->instant_price != 0)
				{
					echo "<br>Sofortkauf: ".$auction->instant_price." Cr. $instant";
				}
		
				echo"<br>Preisschritte: ".$auction->inc_price ." Cr.";
				
				if($cancel == true && $my_act == true)
				{
					echo "<br>keine Gebote bisher - ";
					echo $this->Html->link(
						'cancel',
						'/city/auction?id='.$auction->auctionid.'&action=cancel'
					);
				}
				
				if($cancel == false && $my_act == false)
				{
					echo "<br>";
					echo "<br>";
					echo $this->Form->create(null, ["name" => "bet", "onsubmit" => "return validateBet()"]);
					echo $this->Form->control('gebot',['required' => 'true', 'label' => 'Gebot (mind. '.$new_price.' Cr.)', 'type' => 'number','min' => $new_price, 'value' => $new_price, 'class' => 'form-control']);
					echo $this->Form->button('bieten', ['value' => 'bet', 'id' => 'bet', 'name' => 'bet', 'type' => 'submit', 'class' => 'btn btn-primary mt-1']);
					echo $this->Form->end();
				}
				
				if($max_bet == true)
				{
					echo "<br>Dein Maximalgebot liegt bei: ".$max_bet_credits['max_bet']." Cr.";
				}
			echo "</div>";
		echo "</div>";
	}
	echo "<br>";
	echo "<br>";
	echo "<div class='row d-flex justify-content-center'>";
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'Überblick',
				'/city/auction'
			);
			echo "</div>";
			
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'bietend',
				'/city/auction?view=bids'
			);
			echo "</div>";
			
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'gekauft',
				'/city/auction?view=bought'
			);
			echo "</div>";
			
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'verkaufend',
				'city/auction?view=selling&seller='.$char->username.''
			);
			echo "</div>";
			
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'verkauft',
				'city/auction?view=sold'
			);
			echo "</div>";
			
			echo "<div class='m-1'>";
			echo $this->Html->link(
				'neu',
				'city/auction?view=new'
			);
			echo "</div>";
		echo "</div>";

?>
<script>
function validateForm() {
  var insta = document.forms["weiter"]["insta"].valueAsNumber;
  var direct = document.forms["weiter"]["direct"].valueAsNumber;
  var start = document.forms["weiter"]["start"].valueAsNumber;
  
  if(insta == "") var insta = 0;
  if(direct == "") var direct = 0;
  
  if (start > direct || start > insta) {
	  if(insta != 0 || direct != 0)
	  {
    alert("Sofortkauf oder Direktkauf kann nicht kleiner als Startpreis sein, ausgenommen 0");
    return false;
	  }
  }
} 
function validateBet() {
  var geb = document.forms["bet"]["gebot"].value;
  
	return confirm('Willst du wirklich '+geb+' bieten?');
} 
</script>