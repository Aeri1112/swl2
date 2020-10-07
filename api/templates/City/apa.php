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

        el.innerHTML = hours + "h " + minutes + "m " + second + "s ";
        seconds--;
        if(seconds == 0) {
            if(minutes == 0) {
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
	if($open && !$sleep)
	{
		echo $this->Form->create();
		echo $this->Form->control('duration',['label' => 'how long do you want to sleep?', 'type' => 'number','min' => '1', 'max' => '6', 'value' => '1', 'class' => 'form-control']);
		echo $this->Form->button('sleep', ['type' => 'submit', 'class' => 'btn btn-primary']);
		echo $this->Form->end();
	}
	elseif($open)
	{
		echo "You are sleeping for the next: ";
		echo "<div id='timer'></div>";
		?>
		<script>
			countdown('timer', 0, <?= $timer ?>)
		</script>
		<?php		
	}
	else
	{
		echo "You are busy";
	}
?>
