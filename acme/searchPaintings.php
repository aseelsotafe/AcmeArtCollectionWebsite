<?php

require_once 'config.php';

try {
    // Check if search term provided in URL
    if(isset($_GET['search'])) {
        $search = $_GET['search'];
        
        // Prepare and execute SQL query to get paintings based on search term
        $sql = "SELECT p.*, a.Name AS Artist, s.Name AS Style 
                FROM Paintings p
                INNER JOIN Artists a ON p.ArtistID = a.ID
                INNER JOIN Styles s ON p.StyleID = s.ID
                WHERE p.Title LIKE :search";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        $stmt->execute();
        $foundRows = $stmt->rowCount();
    } else {
        // If no search term provided, redirect to the home page or display an error message
        header("Location: index.php");
        exit();
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Search Results</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="path/to/navbar-styles.css"> 
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
<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="50">


<?php include 'menu.php'; ?>

<div class="container">
    <h2>Search Results</h2>
    <?php if ($foundRows > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                <th>Painting's Image</th>
                    <th>Painting's Title</th>
                    <th>Artist</th>
                    <th>Media</th>
                    <th>Style</th>
                    <th>Actions</th>
                
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                    <td><img src="data:image/jpeg;base64,<?php echo base64_encode($row['Thumbnail']); ?>" alt="Artist Image"/></td>
                        <td><?php echo htmlspecialchars($row['Title'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['Artist'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['Media'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['Style'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <!-- Edit button -->
                            <a href="edit.php?id=<?php echo $row['ID']; ?>" class="btn btn-primary">Edit</a>
                            <!-- Delete button -->
                            <a href="delete.php?id=<?php echo $row['ID']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No paintings found</p>
    <?php endif; ?>
</div>
<div class="col-md-2"></div> <!-- Add an empty column to the right -->
    </div>
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

