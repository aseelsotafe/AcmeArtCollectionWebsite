<?php

require_once 'config.php'; 

try { 
    // Check if request method POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Collect form data
        $Name = $_POST['Name'];
        $LifeSpan = $_POST['LifeSpan'];
        $Nationality = $_POST['Nationality'];
       
        // Store image contents
        $thumbNailImage = file_get_contents($_FILES['thumbNailImage']['tmp_name']); 

        // SQL script for inserting new artist info
        $sql = "INSERT INTO Artists (Name, LifeSpan, Nationality, thumbNailImage) 
                VALUES (:Name, :LifeSpan, :Nationality, :thumbNailImage)";
        $stmt = $pdo->prepare($sql);
 
        // Bind form data to SQL parameters
        $stmt->bindParam(':Name', $Name);
        $stmt->bindParam(':LifeSpan', $LifeSpan);
        $stmt->bindParam(':Nationality', $Nationality);
        $stmt->bindParam(':thumbNailImage', $thumbNailImage, PDO::PARAM_LOB);

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
        <h4 class="modal-title">Add New Artist</h4>
    </div>
    <div class="modal-body">
        <form id="addArtistForm" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="Name">Artist Name:</label>
                <input type="text" class="form-control" id="Name" name="Name" required>
            </div>
            <div class="form-group">
                <label for="LifeSpan">Life Span:</label>
                <input type="text" class="form-control" id="LifeSpan" name="LifeSpan" required>
            </div>
            <div class="form-group">
                <label for="Nationality">Nationality:</label>
                <input type="text" class="form-control" id="Nationality" name="Nationality" required>
            </div>
            <div class="form-group">
                <label for="thumbNailImage">Thumbnail:</label>
                <input type="file" class="form-control-file" id="thumbNailImage" name="thumbNailImage" required>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="addArtistBtn">Add Artist</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>

<script>
document.getElementById('addArtistBtn').addEventListener('click', function(event) {
    event.preventDefault();

    // Check for required text fields
    const fieldIDs = ['Name', 'LifeSpan', 'Nationality'];
    if (fieldIDs.some(id => !document.getElementById(id).value.trim())) {
        alert('All text fields should be filled out');
        return;
    }

    // Ensure thumbnail image selected
    if (!document.getElementById('thumbNailImage').files.length) {
        alert('Please select the thumbnail image');
        return;
    }

    // Validate thumbnail image
    validateImage('thumbNailImage', 100, submitForm);
});

// Function to validate images
function validateImage(inputId, maxWidth, callback) {
    const input = document.getElementById(inputId);
    const file = input.files[0];
    const img = new Image();
    img.onload = () => {
        if (img.width > maxWidth) {
            alert(`Width of thumbnail must be ${maxWidth}px or less`);
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
    const form = document.getElementById('addArtistForm');
    const formData = new FormData(form);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'addArtist.php', true);
    xhr.onload = () => {
        if (xhr.status === 200) {
            alert('Artist added successfully');
            window.location.href = 'artists.php';
        } else {
            console.error('Error:', xhr.responseText);
        }
    };
    xhr.onerror = () => console.error('Error: Request failed');
    xhr.send(formData);
}
</script>