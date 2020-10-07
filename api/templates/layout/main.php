<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
use Cake\ORM\TableRegistry;
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Londrina+Outline&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.1/normalize.css">
    <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Open+Sans'>
	<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.min.css'>
    <?= $this->Html->css('bootstrap.min.css') ?>
    <?= $this->Html->css('cake.css') ?>
    <?= $this->Html->css('neon.css') ?>
	<?= $this->Html->css('chat.css') ?>
    <?= $this->Html->css('font-awesome.min.css') ?>
    <?= $this->Html->css('jquery-ui.css') ?>
    <?= $this->Html->script('jquery') ?>
    <?= $this->Html->script('bootstrap.bundle') ?>
    <?= $this->Html->script("jquery-ui-1.12.1/jquery-ui.js") ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
	
		<script src='https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.concat.min.js'></script>
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

				el.innerHTML = minutes + 'm ' + second + 's ';
				
				seconds--;
				if(seconds == 0) {
					if(minutes <= 0) {
						(el.innerHTML = 'finish');     

						clearInterval(interval);
						return;
					} else {
						minutes--;
						seconds = 60;
					}
				}
			}, 1000);
		}
		function progress (progress, time, lefttime){
			var el = document.getElementById("progress");
			var width = progress;
			var id = setInterval(frame, 999);
			
			function frame(){
				if(width >= 100){
					clearInterval(id);
				}
				else{
					lefttime--;
					width = (time - lefttime) * 100 / time;
					el.style.width = width + "%";
				}
			}
		}
	</script>

    <?php echo $this->Html->scriptBlock(sprintf(
    'var csrfToken = %s;',
    json_encode($this->request->getAttribute('csrfToken'))
)); ?>
</head>
  <body>
    <?= $this->element('nav'); ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-9">

    <?= $this->fetch('content') ?>

	</div>
	<div class="col-md-3">
		<?= $this->element('chat2', ["userid" => $this->request->getAttribute('identity')->id]) ?>
	</div>
  </body>
</html>