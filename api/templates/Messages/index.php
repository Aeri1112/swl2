<?php

?>
<script>
        function post()
    {
        var send_to = 6;
        var send_from = 4;
        var text = document.getElementById("text").value;
        var status = 0;
        var type = "send";

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': csrfToken }
        });
        
        $.ajax({
            type: 'post',
            url: "<?php echo $this->Url->build(['controller' => 'messages', 'action' => 'index', 6]); ?>",
            data:
            {
                 text:text,
                 type:"send"
            },
            success: function display()
            {
                $( 'text' ).each(function(){
                this.reset();
                });
                var type = "receive";

                $.ajaxSetup({
                    headers:
                    { 'X-CSRF-TOKEN': csrfToken }
                });
                
                $.ajax({
                    type: 'post',
                    url: "<?php echo $this->Url->build(['controller' => 'messages', 'action' => 'receive', 6]); ?>",
                    data:
                    {
                        text:text,
                        type:"receive"
                    },
                    success: function ( response )
                    {
                        $('.col-md-9').html(response);
                    }
                })
            }
        })
    }
</script>
<div class="row">
    <div class="col-md-3">
        <h3 class="bg-info p-1">Unterhaltungen</h3>
        <ul class="list-group list-group-flush">
                <?php
                    foreach ($chatList as $key => $value) {
                ?>
                    <li class="list-group-item">
                        <a href=<?= $this->Url->build(['controller' => 'Messages', 'action' => 'index', $value]) ?>><?php echo $JediUserChars->get($value)["username"]; ?></a>
                    </li> 
                <?php   
                    }
                ?>
        </ul>
    </div>
    <?php
    if($view == "ya")
    {
    ?>
    <div class="col-md-9" style='background-color: #ffffff;'>
        <div class="text-center">Unterhaltung mit <?= $JediUserChars->get($user)->username ?></div>
        <?php
        $i = 0;
        //Startschwierigkeiten in der ersten Runde
        $day[$i-1] = "01.01.1970";

        foreach ($messages as $key => $m) {

            $day[$i] = $m->send->i18nFormat('dd.MM.YYYY');
            
            if($day[$i] > $day[$i-1])
            {
                echo "<div class='d-flex justify-content-center'><span class='border rounded'>".$day[$i]."<span></div>";
            }          
            
            //Ich bin versender
            if($m->send_to == $user)
            {
                echo "<div class='border broder-info rounded mt-1 mb-1 pl-2 pr-2 d-flex justify-content-between' style='background-color:#ffffcc; margin-left:50%;'>";
                echo "<div class='d-inline text-right'>";
                echo $m->text;
                echo "</div>";
                echo "<div class='d-inline' style='font-size:10px;'>".$m->send->i18nFormat('HH:mm')."</div>";
                echo "</div>";
            }
            //Ich bin empfänger
            else
            {
                echo "<div class='border broder-info rounded mt-1 mb-1 pl-2 d-flex justify-content-between' style='background-color:#ffffcc; margin-right:50%;'>";
                echo "<div class='d-inline text-left'>";
                echo $m->text;
                echo "</div>";
                echo "<div class='d-inline' style='font-size:10px;'>".$m->send->i18nFormat('HH:mm')."</div>";
                echo "</div>";
            }
            $i++;
        }
        echo $this->form->create(null, ['onsubmit' => 'post(); return false', 'class' => 'mt-5']);
        echo "<div class='form-row align-items-center'>";
        echo "<div class='col-md-11'>";
        echo $this->form->text('text', ['autocomplete' => 'off', 'id' => 'text', 'class' => 'form-control mb-2']);
        echo "</div>";
        echo "<div class='col-md-1'>";
        echo $this->form->button('send', ['class' => 'btn btn-primary mb-2', 'type' => 'button', 'onClick' => 'return post();']);
        echo "</div>";
        echo "</div>";
        echo $this->form->end();
        ?>
    </div>
    <?php
    //Ende view    
    }
    ?>
</div>