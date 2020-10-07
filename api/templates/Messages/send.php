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