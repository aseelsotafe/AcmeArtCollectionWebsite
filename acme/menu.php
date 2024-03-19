<?php
$servername = "localhost";
		$username = "adminer";
		$password = "P@ssw0rd";
		$db = "ACME";
        // Database connection
        $pdo = new PDO('mysql:host=' . $servername . ';dbname=' . $db, $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch artists
$artistStmt = $pdo->query("SELECT * FROM Artists");
$artists = $artistStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch styles
$styleStmt = $pdo->query("SELECT * FROM Styles");
$styles = $styleStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#myPage">ACME ARTS PAINTINGS GALLERY</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#myPage">HOME</a></li>
        <li><a href="paintings.php">PAINTINGS</a></li>
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">STYLES
          <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <?php foreach ($styles as $style): ?>
              <li><a href="by_style.php?style_id=<?= $style['ID'] ?>"><?= $style['Name'] ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">ARTIST
          <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <?php foreach ($artists as $artist): ?>
              <li><a href="by_artist.php?artist_id=<?= $artist['ID'] ?>"><?= $artist['Name'] ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
        <li><a href="#aboutus">ABOUT US</a></li>
        <li><a href="#contact">CONTACT</a></li>
        <li><a href="#" id="searchIcon"><span class="glyphicon glyphicon-search"></span></a></li>
      </ul>
      <form class="navbar-form navbar-right search-box" id="searchBox">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
      </form>
    </div>
  </div>
</nav>