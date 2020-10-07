<div class="text-center">
    Single-Ranking
</div>
<?php
    if(!isset($no)) 
    {
?>
        <div class="d-flex justify-content-between">
            <div>
                restliche Versuche: <?php if($event) echo $event->attemps; ?>
            </div>
            <div>
                Läuft bis: <?php if($cur_event) echo $cur_event->expire; ?>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <div>
                Reset der Versuche: every day
            </div>
            <div>
                <?php
                    if($event == null)
                    {
                        echo $this->Html->link(
                        'join',
                        '/events/ranking/join',
                        ['class' => 'btn btn-primary']
                    );
                    }
                ?>
            </div>
        </div>

        <div class="table-responsive-md">
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">User</th>
                        <th scope="col">Points</th>
                        <th scope="col">Fights</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $rank = 1;
                        foreach ($players as $key => $player)
                        {
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
                                <?= $player->fights ?>
                            </td>
                            <td>
                                <?php   if($player->userid == $Auth["id"] || $event == null)
                                        {

                                        }
                                        elseif($event)
                                        {
                                            if($event->attemps < 1)
                                            {

                                            }
                                            else
                                            {
                                            ?>
                                                <?= $this->Form->postButton('Attack', ['controller' => 'events', 'action' => 'ranking'], ["data" => ["userid" => $player->userid], "class" => "btn btn-primary"]) ?>
                                            <?php
                                            }
                                        }
                                ?>
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
        echo "<div class='row'><div class='col-md'>your attacks:<br><div class='small'>";
        foreach ($fight_reps_a as $key => $rep) {
			echo $this->Time->format(
				  $rep->zeit,
				  'dd.MM. HH:mm',
				  null,
				  'Europe/Berlin'
				);
			echo " Uhr ";
            echo $this->Html->link(
                $rep->headline,
                '/fight/reade/'.$rep->md5.'',
                ['class' => 'button', 'target' => '_blank']
            ); echo "<br>";
        }echo"</div></div>
        <div class='col-md'>
            your defenses:<br><div class='small'>";
            foreach ($fight_reps_d as $key => $rep) {
				echo $this->Time->format(
				  $rep->zeit,
				  'dd.MM. HH:mm',
				  null,
				  'Europe/Berlin'
				);
				echo " Uhr ";
                echo $this->Html->link(
                    $rep->headline,
                    '/fight/reade/'.$rep->md5.'',
                    ['class' => 'button', 'target' => '_blank']
                ); echo "<br>";
            }echo"
			</div>
        </div>
        </div>";
    }
    else
    {
        echo "<div class='text-center'>
                currently no event
                </div>";
    }