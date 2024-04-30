<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

    
    <title>ACME ARTS PAINTINGS GALLERY</title>
    <link rel="stylesheet" href="styles.css">
    <style>

.search-box {
    display: none;
  }

        .navbar-default .navbar-nav .open .dropdown-menu > li > a {
            background-color: white !important; 
            color: black !important; 
        }

        .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover {
            background-color: #f8f8f8 !important; 
            color: black !important;
        }
		
        .dropdown-submenu {
            position: relative;
        }
        
        .dropdown-submenu .dropdown-menu {
            top: 0; 
            right: 100%; /* Changed to right */
            margin-top: 0; 
            position: absolute; 
        }
		
        .dropdown-submenu:hover .dropdown-menu {
            display: block;
        }
        .navbar-form.search-box {
    display: inline-block; /* Display the search boxes inline */
    margin-bottom: 0; /* Remove bottom margin */
    vertical-align: middle; /* Align them vertically */
}

    </style>
</head>
<body>
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
      <a class="navbar-brand" href="index.php">ACME ARTS PAINTINGS GALLERY</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">PAINTINGS<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="paintings.php">SHOW ALL</a></li>
            <li class="dropdown-submenu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">BY ARTIST</a>
              <ul class="dropdown-menu">
                <?php foreach ($artists as $artist): ?>
                  <li><a href="by_artist.php?artist_id=<?= $artist['ID'] ?>"><?= $artist['Name'] ?></a></li>
                <?php endforeach; ?>
              </ul>
            </li>
            <li class="dropdown-submenu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">BY STYLE</a>
              <ul class="dropdown-menu">
                <?php foreach ($styles as $style): ?>
                  <li><a href="by_style.php?style_id=<?= $style['ID'] ?>"><?= $style['Name'] ?></a></li>
                <?php endforeach; ?>
              </ul>
            </li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">ARTISTS<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="artists.php">SHOW ALL</a></li>
            <li class="dropdown-submenu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">BY PERIOD</a>
              <ul class="dropdown-menu">
                <li><a href="by_period.php?century=15">15th century</a></li>
                <li><a href="by_period.php?century=16">16th century</a></li>
                <li><a href="by_period.php?century=17">17th century</a></li>
                <li><a href="by_period.php?century=18">18th century</a></li>
                <li><a href="by_period.php?century=19">19th century</a></li>
                <li><a href="by_period.php?century=20">20th century</a></li>
              </ul>
            </li>
            <li class="dropdown-submenu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">BY NATIONALITY</a>
              <ul class="dropdown-menu">
                <li><a href="by_nationality.php?nationality=French">French</a></li>
                <li><a href="by_nationality.php?nationality=Italian">Italian</a></li>
                <li><a href="by_nationality.php?nationality=Dutch">Dutch</a></li>
                <li><a href="by_nationality.php?nationality=Spanish">Spanish</a></li>
              </ul>
            </li>
          </ul>
        </li>
        <!-- Painting search form -->
        <li>
          <form id="searchBoxPainting" class="navbar-form search-box" role="search" action="searchPaintings.php" method="GET" style="display: none;">
            <div class="input-group">
              <input id="searchInputPainting" type="text" class="form-control" placeholder="Search paintings" name="search">
              <!-- Search icon button -->
              <div class="input-group-btn">
                
              </div>
            </div>
          </form>
        </li>
        <!-- Artist search form -->
        <li>
          <form id="searchBoxArtist" class="navbar-form search-box" role="search" action="searchArtist.php" method="GET" style="display: none;">
            <div class="input-group">
              <input id="searchInputArtist" type="text" class="form-control" placeholder="Search artists" name="search">
              <!-- Search icon button -->
              <div class="input-group-btn">
                
              </div>
            </div>
          </form>
        </li>
        <!-- Search icon -->
        <li><a href="#" id="searchIcon"><span class="glyphicon glyphicon-search"></span></a></li>
      </ul>
    </div>
  </div>
</nav>

<script>
$(document).ready(function(){
    // Toggle search boxes visibility on search icon click
    $("#searchIcon").click(function(){
        $("#searchBoxArtist, #searchBoxPainting").toggle();
    });

    // Add event listener to search input for Enter key
    $("#searchInputArtist").keypress(function(event) {
        if (event.which === 13) {
            event.preventDefault();
            $("#searchBoxArtist").submit(); // Submit the artist search form
        }
    });

    $("#searchInputPainting").keypress(function(event) {
        if (event.which === 13) {
            event.preventDefault();
            $("#searchBoxPainting").submit(); // Submit the painting search form
        }
    });
});
</script>