<div class="text-center">
    Highscore-Ranking
</div>
<?php
    if(!isset($no)) 
    {
?>
		<div class="d-flex justify-content-between">
            <div>
                Typ: <?php if($event) echo $highscore_type; ?>
            </div>
            <div>
                Läuft bis: <?php if($cur_event) echo $cur_event->expire; ?>
            </div>
        </div>
        <div class="d-flex justify-content-between">
			<div>
			</div>
			<div>
                <?php
                    if($event == null)
                    {
                        echo $this->Html->link(
							'join',
							'/events/milestone/join',
							['class' => 'btn btn-primary']
						);
                    }
					else
					{
						echo"
						<button class='btn btn-primary mb-1' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>
							prices
						</button>";
					}
                ?>
            </div>
        </div>
		<?php
		echo "
		<div class='collapse' id='collapseExample'>
		<div class='row'>";
		
		foreach($loot as $key => $loot)
		{
			echo"".$this->element("item", ["loot" => $loot, "type" => $cur_event->price_type])."";
		}
		echo"  
		</div>
		</div>";
		?>
		<div class="table-responsive-md">
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">User</th>
                        <th scope="col">Kills</th>
                        <th scope="col">joined</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $rank = 1;
                        foreach ($players as $key => $player)
                        {
							$player->joined = $player->joined->i18nFormat('dd.MM.yyyy HH:mm:ss','Europe/Berlin', 'de-DE');
                    ?>
                        <tr <?php if($player->userid == $Auth["id"]) echo "class='table-danger'"; ?>>
                            <td>
                                <?= $rank ?>
                            </td>
                            <td>
                                <?= $player->JediUserChars["username"] ?>
                            </td>
                            <td>
                                <?= $player->points ?>
                            </td>
                            <td>
                                <?= $player->joined ?>
                            </td>  
                        </tr>
                    <?php
                            $rank++;
                        }
                    ?>
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
<?php
	}
    else
    {
        echo "<div class='text-center'>
                currently no event
                </div>";
    }