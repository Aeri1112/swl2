<?php
    foreach ($players as $key => $player) {
?>
        <ul class="list-group">
            <li class="list-group-item <?php if($player["userid"] == $userid) echo "active"; ?>">
                <?= $player["username"] ?>
            </li>
        </ul>
<?php
    }
?>
