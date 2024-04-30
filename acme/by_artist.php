<?php

require_once 'config.php'; 

try {
    // Check if artist ID provided in URL
    if(isset($_GET['artist_id'])) {
        $artist_id = $_GET['artist_id'];
        
        // Prepare and execute SQL query to get paintings by specified artist
        $sql = "SELECT p.*, a.Name AS Artist, s.Name AS Style 
                FROM Paintings p
                INNER JOIN Artists a ON p.ArtistID = a.ID
                INNER JOIN Styles s ON p.StyleID = s.ID
                WHERE p.ArtistID = :artist_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':artist_id', $artist_id, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        // If no artist ID provided, get all paintings
        $stmt = $pdo->query("SELECT p.*, a.Name AS Artist, s.Name AS Style 
                             FROM Paintings p
                             INNER JOIN Artists a ON p.ArtistID = a.ID
                             INNER JOIN Styles s ON p.StyleID = s.ID");
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Acme Arts Painting Gallery</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
  body {
    font: 400 20px/1.8 Lato, sans-serif;
    color: #777;
  }
  h3, h4 {
    margin: 20px 0 30px 0;
    letter-spacing: 10px;      
    font-size: 35px;
    color: #111;
  }
  .container {
    padding: 80px 120px;
	
  }
  .person {
    border: 10px solid transparent;
    margin-bottom: 25px;
    width: 80%;
    height: 80%;
    opacity: 0.7;
  }
  .person:hover {
    border-color: #f1f1f1;
  }
  .carousel-inner img { 
    width: 100%; /* Set width to 100% */
    margin: auto;
  }
  .carousel-caption h3 {
    color: #fff !important;
  }
  @media (max-width: 600px) {
    .carousel-caption {
      display: none; /* Hide the carousel text when the screen is less than 600 pixels wide */
    }
  }
  .bg-1 {
    background: #2d2d30;
    color: #bdbdbd;
  }
  .bg-1 h3 {color: #fff;}
  .bg-1 p {font-style: italic;}
  .list-group-item:first-child {
    border-top-right-radius: 0;
    border-top-left-radius: 0;
  }
  .list-group-item:last-child {
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }
  .thumbnail {
    padding: 0 0 15px 0;
    border: none;
    border-radius: 0;
  }
  .thumbnail p {
    margin-top: 15px;
    color: #555;
  }
  .btn {
    padding: 10px 20px;
    background-color: #333;
    color: #f1f1f1;
    border-radius: 0;
    transition: .2s;
  }
  .btn:hover, .btn:focus {
    border: 1px solid #333;
    background-color: #fff;
    color: #000;
  }
  .modal-header, h4, .close {
    background-color: #333;
    color: #fff !important;
    text-align: center;
    font-size: 30px;
  }
  .modal-header, .modal-body {
    padding: 40px 50px;
  }
  .nav-tabs li a {
    color: #777;
  }
  #googleMap {
    width: 100%;
    height: 400px;
    -webkit-filter: grayscale(100%);
    filter: grayscale(100%);
  }  
  .navbar {
    font-family: Montserrat, sans-serif;
    margin-bottom: 0;
    background-color: #2d2d30;
    border: 0;
    font-size: 16px !important;
    letter-spacing: 4px;
    opacity: 0.9;
  }
  .navbar li a, .navbar .navbar-brand { 
    color: #d5d5d5 !important;
  }
  .navbar-nav li a:hover {
    color: #fff !important;
  }
  .navbar-nav li.active a {
    color: #fff !important;
    background-color: #29292c !important;
  }
  .navbar-default .navbar-toggle {
    border-color: transparent;
  }
  .open .dropdown-toggle {
    color: #fff;
    background-color: #555 !important;
  }
  .dropdown-menu li a {
    color: #000 !important;
  }
  .dropdown-menu li a:hover {
    background-color: green !important;
  }
  footer {
    background-color: #2d2d30;
    color: #f5f5f5;
    padding: 32px;
  }
  footer a {
    color: #f5f5f5;
  }
  footer a:hover {
    color: #777;
    text-decoration: none;
  }  
  .form-control {
    border-radius: 0;
  }
  textarea {
    resize: none;
  }
  .search-box {
  display: none;
  margin-top: 8px;
  }
  .search-box input[type="text"] {
  width: 200px;
  border-radius: 0;
  border: 1px solid #ccc;
  padding: 6px 12px;
  outline: none;
  }
  </style>
</head>
<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="50">

<?php include 'menu.php'; ?>

<div class="container-fluid" style="padding-top: 80px; padding-left: 15px; padding-right: 15px; padding-bottom: 50px">
    
	
    
    <table class="table table-striped" style="width: 50%; margin: 0 auto">
    <thead>
        <tr>
            <th style="width: 5%;"></th>            
            <th style="width: 17%">Painting Title</th>
            <th style="width: 5%">Finished</th>               
            <th style="width: 12%">Style</th>
            <th style="width: 10%"></th>
        </tr>
    </thead>
        <?php

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <tr>
                <td><img src="data:image/jpeg;base64,<?php echo base64_encode($row['Thumbnail']); ?>" /></td>
                <td><?php echo $row['Title']; ?></td>
                <td><?php echo $row['Completed']; ?></td>
                <td><?php echo $row['Style']; ?></td>
                <td>
					<button type="button" class="btn btn-primary btn-sm edit-btn" data-id="<?php echo $row['ID']; ?>" data-toggle="modal" data-target="#editModal">Edit</button>
					<button type="button" class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $row['ID']; ?>" data-toggle="modal" data-target="#deleteModal">Delete</button>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

<!-- Modal Inclusions -->
<div id="editModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <?php include 'edit.php'; ?>
  </div>
</div>

<div id="deleteModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <?php include 'delete.php'; ?>
  </div>
</div>

<script>
// Ensure document fully loaded
$(document).ready(function() {

  // Edit button click for selected painting
  $('.edit-btn').click(function() {
    var paintingID = $(this).data('id');
    $.ajax({
      url: 'edit.php',
      method: 'POST',
      data: { id: paintingID },
      dataType: 'html',
      success: function(response) {
        // Show edit modal
        $('#editModal .modal-dialog').html(response);
        $('#editModal').modal('show');
      },
      error: function(xhr, status, error) {
        // Log errors
        console.error(xhr.responseText);
      }
    });
  });
});
</script>

</body>
</html>