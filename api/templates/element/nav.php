<?php
use Cake\Datasource\ConnectionManager;

$connection = ConnectionManager::get('default');

//Überprüfung auf Levelup auf jeder Seite aktiv
$user = $connection->execute('SELECT xp, level FROM jedi_user_skills WHERE userid = :id', ['id' => $this->request->getAttribute('identity')->id])->fetch('assoc');
	
	function calc_xp_next_lvl($level)
    {
        return round(((15 * ($level * $level)) + 100 + pow(4,($level/12))));
    }
	
	$xp_n_lvl = calc_xp_next_lvl($user["level"]);
	if($xp_n_lvl <= $user["xp"])
	{
		?>
		<script>
			location.replace('<?php echo "/character/overview"; ?>');
		</script>
		<?php
		return;
	}

$results = $connection
    ->execute('SELECT COUNT(*) FROM jedi_user_messages WHERE send_to = :id AND status = 0', ['id' => $this->request->getAttribute('identity')->id])
    ->fetchAll('assoc');
    $unread = $results[0]["COUNT(*)"];

$points = $connection->execute('SELECT rsp, rfp FROM jedi_user_skills WHERE userid = :id', ['id' => $this->request->getAttribute('identity')->id])
->fetchAll('assoc');
$rsp = $points[0]["rsp"];
$rfp = $points[0]["rfp"];
?>
<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
  <a class="navbar-brand" href=<?= $this->Url->build(['controller' => 'character','action' => 'overview']); ?>>JTG</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		Charakter <?php if($rsp > 0 || $rfp > 0) echo "<span class='text-danger'>!</span>"; ?>
		</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href=<?= $this->Url->build(['controller' => 'character','action' => 'overview']); ?>>Überblick</a>
          <a class="dropdown-item" href=<?= $this->Url->build(['controller' => 'character','action' => 'inventory']); ?>>Ausrüstung</a>
          <a class="dropdown-item" href=<?= $this->Url->build(['controller' => 'character','action' => 'abilities']); ?>>Fähigkeiten <?php if($rsp > 0) echo "<span class='text-danger'>!</span>"; ?></a>
		      <a class="dropdown-item" href=<?= $this->Url->build(['controller' => 'character','action' => 'forces']); ?>>Mächte <?php if($rfp > 0) echo "<span class='text-danger'>!</span>"; ?></a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		Stadt
		</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="#">Überblick</a>
          <a class="dropdown-item" href=<?= $this->Url->build(['controller' => 'city','action' => 'apa']); ?>>Apartment</a>
          <a class="dropdown-item" href=<?= $this->Url->build(['controller' => 'city','action' => 'bar']); ?>>Bar</a>
          <a class="dropdown-item" href=<?= $this->Url->build(['controller' => 'city','action' => 'arena']); ?>>Arena</a>
          <a class="dropdown-item" href=<?= $this->Url->build(['controller' => 'city','action' => 'layer']); ?>>Layer</a>
		  <a class="dropdown-item" href=<?= $this->Url->build(['controller' => 'city','action' => 'auction']); ?>>Auktionshaus</a>
		  <a class="dropdown-item" href=<?= $this->Url->build(['controller' => 'city','action' => 'store']); ?>>Händler</a>
		  <a class="dropdown-item" href="#">Werkstatt</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href=<?= $this->Url->build(['controller' => 'messages','action' => 'chat']); ?>>Nachrichten <?php if($unread != "0") echo $unread; ?></a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href=<?= $this->Url->build(['controller' => 'alliances','action' => 'index']); ?>>Allianz</a>
      </li>
	  <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Einstellungen
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href=<?= $this->Url->build(['controller' => 'preferences','action' => 'fight']); ?>>Kampf</a>
          <a class="dropdown-item" href="#">Ausbildung</a>
          <a class="dropdown-item" href="#">Account</a>
        </div>
      </li>
	  <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Bugs
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="#">auflisten</a>
          <a class="dropdown-item" href="#">melden</a>
        </div>
      </li>
	  <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Statistiken
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href=<?= $this->Url->build(['controller' => 'score','action' => 'player']); ?>>Spieler-Rangliste</a>
          <a class="dropdown-item" href="#">Allianz-Rangliste</a>
          <a class="dropdown-item" href="#">Statistiken</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Events
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href=<?= $this->Url->build(['controller' => 'events','action' => 'ranking']); ?>>Ranglisten-Wettkampf</a>
		  <a class="dropdown-item" href=<?= $this->Url->build(['controller' => 'events','action' => 'milestone']); ?>>Highscore-Wettkampf</a>
        </div>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href=<?= $this->Url->build(['controller' => 'quest']); ?>>Herausforderungen</a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href=<?= $this->Url->build(['controller' => 'accounts','action' => 'logout']); ?>>Logout</a>
      </li>
    </ul>
  </div>
  </div>
</nav>
<script>
		$(document).ready(function() {
  $('li.active').removeClass('active');
  $('a[href="' + location.pathname + '"]').closest('li').addClass('active'); 
});
</script>
<!--
<nav class="navbar fixed-bottom navbar-dark bg-dark justify-content-center">
		<a class="navbar-brand" href="#">Footer</a>
</nav>
-->