<?php
    if(!empty($user))
    {
?>
        <li class="list-group-item">
            <?= $user->username ?>
            <button type="button" class="btn btn-info btn-xs start_chat" data-touserid="<?= $user->userid ?>" data-tousername="<?= $user->username ?>">Start Chat</button>
        </li> 
<?php
    }
    else
    {
        echo "No User found";
    }
?>