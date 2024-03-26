<?php

require_once 'config.php'; 

try {
    // Check if request method POST and operation 'edit'
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['operation']) && $_POST['operation'] == 'edit') {
        // Collect form data
        $paintingID = $_POST['paintingID'];
        $title = $_POST['editPaintingTitle'];
        $completed = $_POST['editFinished'];
        $media = $_POST['editMedia'];
        $artistID = $_POST['editArtist'];
        $styleID = $_POST['editStyle'];
        
        // SQL script for updating painting info
        $sql = "UPDATE Paintings SET Title = :title, Completed = :completed, Media = :media, ArtistID = :artistID, StyleID = :styleID";
        
        // Parameters for SQL script
        $params = [
            ':title' => $title,
            ':completed' => $completed,
            ':media' => $media,
            ':artistID' => $artistID,
            ':styleID' => $styleID,
            ':paintingID' => $paintingID
        ];
        
        // Check if thumbnail image uploaded, add it to script
        if ($_FILES['editThumbnailImage']['size'] > 0) {
            $thumbnailImage = file_get_contents($_FILES['editThumbnailImage']['tmp_name']);
            $sql .= ", Thumbnail = :thumbnailImage";
            $params[':thumbnailImage'] = $thumbnailImage;
        }
        
        // Check if painting image uploaded, add it to script
        if ($_FILES['editPaintingImage']['size'] > 0) {
            $paintingImage = file_get_contents($_FILES['editPaintingImage']['tmp_name']);
            $sql .= ", Image = :paintingImage";
            $params[':paintingImage'] = $paintingImage;
        }
        
        // Finalize update script
        $sql .= " WHERE ID = :paintingID";        
        $stmt = $pdo->prepare($sql); 
        $stmt->execute($params); 
		
        exit; 
    }

    // Get painting details for editing
    if(isset($_POST['id'])) {        
        $paintingId = $_POST['id']; 

        try {           
            // Prepare script to get details
            $stmt = $pdo->prepare("SELECT * FROM Paintings WHERE ID = :painting_id");
            $stmt->bindParam(':painting_id', $paintingId); 
            $stmt->execute(); 
           
            $painting = $stmt->fetch(PDO::FETCH_ASSOC); 
            
            if($painting) {
				?>
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Edit Painting</h4>
					</div>
					<div class="modal-body">
						<form id="editPaintingForm" method="POST" enctype="multipart/form-data">
							<input type="hidden" name="paintingID" value="<?php echo $painting['ID']; ?>">
							<div class="form-group">
								<label for="editPaintingTitle">Painting Title:</label>
								<input type="text" class="form-control" id="editPaintingTitle" name="editPaintingTitle" value="<?php echo $painting['Title']; ?>" required>
							</div>
							<div class="form-group">
								<label for="editFinished">Finished:</label>
								<input type="number" class="form-control" id="editFinished" name="editFinished" value="<?php echo $painting['Completed']; ?>" required>
							</div>
							<div class="form-group">
								<label for="editMedia">Media:</label>
								<input type="text" class="form-control" id="editMedia" name="editMedia" value="<?php echo $painting['Media']; ?>" required>
							</div>
							<div class="form-group">
								<label for="editArtist">Artist's Name:</label>
								<select class="form-control" id="editArtist" name="editArtist">
									<?php            
										$sql = "SELECT ID, Name FROM Artists";
										$stmt = $pdo->query($sql);
										while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
											$selected = ($row['ID'] == $painting['ArtistID']) ? "selected" : "";
											echo "<option value='" . $row['ID'] . "' $selected>" . $row['Name'] . "</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="editStyle">Style:</label>
								<select class="form-control" id="editStyle" name="editStyle">
									<?php            
										$sql = "SELECT ID, Name FROM Styles";
										$stmt = $pdo->query($sql);
										while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
											$selected = ($row['ID'] == $painting['StyleID']) ? "selected" : "";
											echo "<option value='" . $row['ID'] . "' $selected>" . $row['Name'] . "</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="editThumbnailImage">Thumbnail:</label>
								<input type="file" class="form-control-file" id="editThumbnailImage" name="editThumbnailImage">
							</div>
							<div class="form-group">
								<label for="editPaintingImage">Full Image:</label>
								<input type="file" class="form-control-file" id="editPaintingImage" name="editPaintingImage">
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary" id="editPaintingBtn">Save Painting</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
                </div>
				
<script>
    // Prevent form submission on button click and validate fields
    document.getElementById('editPaintingBtn').addEventListener('click', function(event) {
        event.preventDefault();
        const fieldIDs = ['editPaintingTitle', 'editFinished', 'editMedia', 'editArtist', 'editStyle'];
        if (fieldIDs.some(id => !document.getElementById(id).value.trim())) {
            alert('Please fill out all text fields.');
            return;
        }

        // Validate images
        validateImage('editThumbnailImage', 100, () => validateImage('editPaintingImage', 500, submitEditForm));
    });

    // Check if image width within specified maximum
    function validateImage(inputId, maxWidth, callback) {
        const input = document.getElementById(inputId);
        if (input.files.length) {
            const file = input.files[0];
            const img = new Image();
            img.onload = () => {
                if (img.width > maxWidth) {
                    alert(`Width of image must be ${maxWidth}px or less`);
                } else {
                    callback(); 
                }
            };
            img.onerror = () => alert('Invalid file type.'); 
            const reader = new FileReader();
            reader.onload = (e) => img.src = e.target.result; 
            reader.readAsDataURL(file);
        } else {
            callback(); 
        }
    }

    // Submit form with AJAX request, handle success/error response
    function submitEditForm() {
        const form = document.getElementById('editPaintingForm');
        const formData = new FormData(form);
        formData.append('operation', 'edit');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'edit.php', true);
        xhr.onload = () => {
            if (xhr.status === 200) {
                alert('Painting updated successfully'); 
                window.location.href = 'paintings.php'; 
            } else {
                console.error('Error:', xhr.responseText); 
            }
        };
        xhr.onerror = () => console.error('Error: Request failed'); 
        xhr.send(formData);
    }
</script>

<?php
            } 
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } 
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>