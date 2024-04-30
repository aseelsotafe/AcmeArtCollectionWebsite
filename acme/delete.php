<?php

require_once 'config.php';

try {
    // Check if AJAX request
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        // Check if painting ID in request
        if (isset($_POST['id'])) {
            $paintingId = $_POST['id'];

            // Script to delete by ID
            $sql = "DELETE FROM Paintings WHERE ID = :id";
            $stmt = $pdo->prepare($sql);

            // Bind ID parameter
            $stmt->bindParam(':id', $paintingId, PDO::PARAM_INT);

            // Execute script and print success or error
            if ($stmt->execute()) {
                echo "Success";
            } else {
                echo "Error";
            }
            exit();
        }
    }
} catch (PDOException $e) {
    // Display PDO exceptions
    echo "Connection failed: " . $e->getMessage();
    exit();
}

?>

<div class="modal-content">
  <div class="modal-header">
    <h4 class="modal-title">Confirm Delete</h4>
  </div>
  <div class="modal-body">
    <p>Are you sure you want to delete this painting?</p>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
  </div>
</div>

<script>
$(document).ready(function() {
  // Set painting ID in modal
  $('#deleteModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget); 
    var paintingId = button.data('id'); 
    $('#confirmDeleteBtn').data('id', paintingId); 
  });

  // Click event to confirm delete
  $('#confirmDeleteBtn').click(function() {
    var paintingId = $(this).data('id');

    // AJAX request with painting ID
    $.ajax({
      url: 'delete.php',
      type: 'POST',
      data: {id: paintingId},
      success: function(response) {
        // Handle success/error response
        if (response.trim() === "Success") {
          alert('Painting deleted successfully');
          window.location.href = 'paintings.php'; 
        } else {
          alert('Deletion failed: ' + response);
        }
      },
      error: function(xhr, status, error) {
        // Display AJAX errors
        alert('Deletion failed with error: ' + error);
      }
    });
  });
});
</script>