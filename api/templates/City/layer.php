<?php
 $location = explode("_",$char->location2);
?>
<style type="text/css">
   @media (min-width: 950px) {
   /* breite Browserfenster */
   .img
	 {
		width: 400px; 
	 }
 }
 @media (min-width: 450px) and 
  (max-width: 950px) {
   /* Darstellung auf Netbooks */ 
	.img
	{
		width: 400px;
	}
   } 
 @media (max-width: 450px)
 {
	 /* mobile Geräte */
	 .img
	 {
		width: 100%; 
	 }
 }
 </style>
<div class="container-fluid p-0 m-0">
    <div class="row justify-content-center">
        <div class="col-auto">
            <?php
			if($_SESSION["Auth"]["User"]["id"] == 20)
			{
				?>
					<a href=<?= $this->Url->build(['controller' => 'city','action' => 'layer2']); ?>>Layer2</a>
				<?php
			}
            if(!empty($fight_report))
            {
              echo $fight_report["report"];
			  
			  if($_SESSION["Auth"]["User"]["id"] == 14)
				{
					?>
					<a href=<?= $this->Url->build(['controller' => 'city','action' => 'bar']); ?>>Bar</a>
					<?php
				}
				echo "<br>";
				echo $this->Html->link(
					'verwerfen',
					'/city/layer'
				);
            }
            elseif($doing == "no")
            {
				
              ?>
              <div id="carouselExampleInterval" class="carousel slide carousel-fade" data-ride="carousel" data-touch=true data-interval="false">
                <div class="carousel-inner">
                  <?php for($x = 0; $x <= 18; $x++) { 
							$a = rand(1,3); $b = rand(1,6);
							if(isset($cast) && $cast == true && $x == 0)
							{
								$a = $location[0]; $b = $location[1];
							}
					?>                    
                  <div class="carousel-item data-image-a=<?=$a?> data-image-b=<?=$b?> <?php if($x == 0) echo "active"; ?>">
				  
                    <?php echo $this->Html->image("layer2_".$a."_".$b.".jpg", ['class' => 'd-block mx-auto img', 'id' => 'image', 'data-image-a' => "$a", 'data-image-b' => "$b", 'pathPrefix' => 'webroot/img/monster/neu/']); ?>
                    
					<div class="carousel-caption d-md-block pb-0" style="bottom:0px;">
                        <?php
                            if($char->targettime > time())
                            {
                                //currently doing something else
                            }
                            else
                            {
                                echo
                                $this->Form->postLink(
                                'attack',
                                ['action' => 'layer', 'attack'],
                                ['data' => ['a' => $a, 'b' => $b, 'fight' => 'y'], 'class' => 'text-white']);
                            }   
                        ?>
                    </div>
                </div>
                <?php
                }
                ?>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleInterval" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleInterval" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
              </div>
              <?php
            }
            if(isset($message))
              { ?>
                <div class="row justify-content-center text-danger"><?= $message ?></div>
                <?php
              }
			elseif(isset($cast) && $cast == false)
			{
				?>
                <div class="row justify-content-center text-danger"><?php echo "You cannot cast this force"; ?></div>
                <?php
			}
            elseif($doing == "yes" && empty($fight_report))
              {
                echo $this->Html->image("layer2_".$char->location2.".jpg", ['class' => 'd-block mx-auto img', 'pathPrefix' => 'webroot/img/monster/neu/']);
              }
			
			if($char->tmpcast > 0)
			{
				?>
                <div class="row justify-content-center text-primary"><?php echo "Through Force Seeing you have eagle-eyes (".$char->tmpcast." %)"; ?></div>
                <?php
			}
			 ?>
        </div>
		<?php
		echo "<div class='container-fluid d-flex justify-content-center p-0'>";
		
				if($doing == "yes" && empty($fight_report))
				{
					echo $this->Html->image("fseei.gif", ['class' => 'd-block m-1', 'pathPrefix' => 'webroot/img/']);
					echo $this->Html->image("fpush_g.gif", ['class' => 'd-block m-1', 'pathPrefix' => 'webroot/img/']);
					echo $this->Html->image("fpull_g.gif", ['class' => 'd-block m-1', 'pathPrefix' => 'webroot/img/']);
				}
				elseif(empty($fight_report))
				{
					echo $this->Form->create(null, ['url' => false,]);
					echo $this->Form->hidden('cast', ['value' => 1]);
					echo $this->Form->hidden('a', ['value' => $a]);
					echo $this->Form->hidden('b', ['value' => $b]);
					echo $this->Form->submit("fseei.gif", ['class' => 'd-block m-1']);
					echo $this->Form->end();
					
					echo $this->Html->image("fpush_g.gif", ['style' => 'max-height:48px;', 'class' => 'd-block m-1', 'pathPrefix' => 'webroot/img/']);
					echo $this->Html->image("fpull_g.gif", ['style' => 'max-height:48px;', 'class' => 'd-block m-1', 'pathPrefix' => 'webroot/img/']);
				}
			  echo "</div>";
		?>
    </div>
</div>
<?php
    if($doing == "yes" && empty($fight_report))
    { ?>
        <div id="current" class="row justify-content-center"><?php echo "currently fighting "; ?></div>
        <div class="row justify-content-center" id="timer"></div>
    <?php
    }
		if(empty($fight_report))
		{
			echo $this->element('bar', [
							"img" => "health"
						]);
			echo $this->element('bar', [
							"img" => "mana"
						]);
			echo $this->element('bar', [
							"img" => "energy"
						]);
		}
?>


<script type="text/javascript">
v=new Date();
var timer=document.getElementById('timer');
function t(){
  n=new Date();
  ss="<?= $char->targettime_diff ?>";
  s=ss-Math.round((n.getTime()-v.getTime())/1000.);
  m=0;h=0;
  if(s<0){
    timer.innerHTML="<a href=<?= $this->Url->build(['controller' => 'city','action' => 'layer', 'fight']); ?>>Bericht</a>"
    current.innerHTML=""
	document.title="JtG - Done!"
  }else{
  if(s>59){
    m=Math.floor(s/60);
    s=s-m*60
  }
  if(m>59){
    h=Math.floor(m/60);
    m=m-h*60
  }
  if(s<10){
    s="0"+s
  }
  if(m<10){
    m="0"+m
  }
  if(h==0)
  {
    timer.innerHTML=m+":"+s+" "
  }
  else
  {
    timer.innerHTML=h+":"+m+":"+s+" "
  }
	document.title="fighting - "+h+":"+m+":"+s;
  }
  window.setTimeout("t();",999);
}
window.onload=t;
</script>