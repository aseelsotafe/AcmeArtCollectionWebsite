<?php

require_once 'config.php';

// Get artists
$artistStmt = $pdo->query("SELECT * FROM Artists");
$artists = $artistStmt->fetchAll(PDO::FETCH_ASSOC);

// Get styles
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
        <li><a href="index.php">HOME</a></li>
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
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">ARTISTS
          <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <?php foreach ($artists as $artist): ?>
              <li><a href="by_artist.php?artist_id=<?= $artist['ID'] ?>"><?= $artist['Name'] ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>        
        <li><a href="#" id="searchIcon"><span class="glyphicon glyphicon-search"></span></a></li>
      </ul>
<form class="navbar-form navbar-right search-box" id="searchBox" action="search_result.php" method="GET">
    <div class="form-group">
        <input type="text" class="form-control" placeholder="Search" name="search" id="searchInput">
    </div>
</form>
<script>
$(document).ready(function(){
  // Toggle search box visibility on search icon click
  $("#searchIcon").click(function(){
    $("#searchBox").toggle();
  });

  // Add smooth scrolling to all links
  $(".navbar a, footer a[href='#myPage']").on('click', function(event) {
    if (this.hash !== "") {
      event.preventDefault();
      var hash = this.hash;
      $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 900, function(){
        window.location.hash = hash;
      });
    }
  });

  // Add event listener to search input for Enter key
  $("#searchInput").keypress(function(event) {
    if (event.which === 13) {
      event.preventDefault();
      $("#searchBox").submit();
    }
  });
});
</script>
</div>
</div>
</nav>