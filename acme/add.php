<?php

require_once 'config.php'; 

try { 
    // Check if request method POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Collect form data
        $title = $_POST['paintingTitle'];
        $completed = $_POST['finished'];
        $media = $_POST['media'];
        $artist = $_POST['artist'];
        $style = $_POST['style']; 
        // Store image contents
        $thumbnailImage = file_get_contents($_FILES['thumbnailImage']['tmp_name']); 
        $paintingImage = file_get_contents($_FILES['paintingImage']['tmp_name']); 

        // SQL script for inserting new painting info
        $sql = "INSERT INTO Paintings (Title, Completed, Media, ArtistID, StyleID, Image, Thumbnail) 
                VALUES (:title, :completed, :media, (SELECT ID FROM Artists WHERE Name = :artist), 
                (SELECT ID FROM Styles WHERE Name = :style), :paintingImage, :thumbnailImage)";
        $stmt = $pdo->prepare($sql);
 
        // Bind form data to SQL parameters
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':completed', $completed);
        $stmt->bindParam(':media', $media);
        $stmt->bindParam(':artist', $artist);
        $stmt->bindParam(':style', $style);
        $stmt->bindParam(':paintingImage', $paintingImage, PDO::PARAM_LOB);
        $stmt->bindParam(':thumbnailImage', $thumbnailImage, PDO::PARAM_LOB);

        // Execute script
        $stmt->execute();     
        
        exit();
    }
} catch(PDOException $e) {
    // Handle database connection errors
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>

<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">Add New Painting</h4>
    </div>
    <div class="modal-body">
        <form id="addPaintingForm" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="paintingTitle">Painting Title:</label>
                <input type="text" class="form-control" id="paintingTitle" name="paintingTitle" required>
            </div>
            <div class="form-group">
                <label for="finished">Finished:</label>
                <input type="number" class="form-control" id="finished" name="finished" required>
            </div>
            <div class="form-group">
                <label for="media">Media:</label>
                <input type="text" class="form-control" id="media" name="media" required>
            </div>
            <div class="form-group">
                <label for="artist">Artist's Name:</label>
                <select class="form-control" id="artist" name="artist">
                    <?php 
                        $sql = "SELECT Name FROM Artists";
                        $stmt = $pdo->query($sql);
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $row['Name'] . "'>" . $row['Name'] . "</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="style">Style:</label>
                <select class="form-control" id="style" name="style">
                    <?php 
                        $sql = "SELECT Name FROM Styles";
                        $stmt = $pdo->query($sql);
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $row['Name'] . "'>" . $row['Name'] . "</option>";
                        }
                    ?>
                </select>
            </div>
			<div class="form-group">
                <label for="thumbnailImage">Thumbnail:</label>
                <input type="file" class="form-control-file" id="thumbnailImage" name="thumbnailImage" required>
            </div>
            <div class="form-group">
                <label for="paintingImage">Full Image:</label>
                <input type="file" class="form-control-file" id="paintingImage" name="paintingImage" required>
            </div>

        </form>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="addPaintingBtn">Add Painting</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
<script>
document.getElementById('addPaintingBtn').addEventListener('click', function(event) {
    event.preventDefault();

    // Check for required text fields
    const fieldIDs = ['paintingTitle', 'finished', 'media', 'artist', 'style'];
    if (fieldIDs.some(id => !document.getElementById(id).value.trim())) {
        alert('All text fields should be filled out');
        return;
    }

    // Ensure both images selected
    if (!document.getElementById('thumbnailImage').files.length || !document.getElementById('paintingImage').files.length) {
        alert('Both thumbnail and painting images should be selected');
        return;
    }

    // Validate thumbnail image and main image width
    validateImage('thumbnailImage', 100, () => validateImage('paintingImage', 500, submitForm));
});

// Function to validate images
function validateImage(inputId, maxWidth, callback) {
    const input = document.getElementById(inputId);
    const file = input.files[0];
    const img = new Image();
    img.onload = () => {
        if (img.width > maxWidth) {
            alert(`Width of ${inputId.replace('Image', '')} must be ${maxWidth}px or less`);
        } else {
            callback();
        }
    };
    img.onerror = () => alert('Invalid file type');
    const reader = new FileReader();
    reader.onload = (e) => img.src = e.target.result;
    reader.readAsDataURL(file);
}

// Submit form with AJAX
function submitForm() {
    const form = document.getElementById('addPaintingForm');
    const formData = new FormData(form);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'add.php', true);
    xhr.onload = () => {
        if (xhr.status === 200) {
            alert('Painting added successfully');
            window.location.href = 'paintings.php';
        } else {
            console.error('Error:', xhr.responseText);
        }
    };
    xhr.onerror = () => console.error('Error: Request failed');
    xhr.send(formData);
}
</script>