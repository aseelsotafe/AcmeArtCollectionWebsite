<?php
require_once 'config.php';

try {
    // Check if request method POST and operation 'edit'
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['operation']) && $_POST['operation'] == 'EditArtist') {
        // Collect form data
        $artistID = $_POST['artistID'];
        $name = $_POST['editName'];
        $lifeSpan = $_POST['editLifeSpan'];
        $nationality = $_POST['editNationality'];

        // SQL script for updating artist info
        $sql = "UPDATE Artists SET Name = :name, LifeSpan = :lifeSpan, Nationality = :nationality";

        // Parameters for SQL script
        $params = [
            ':name' => $name,
            ':lifeSpan' => $lifeSpan,
            ':nationality' => $nationality,
            ':artistID' => $artistID
        ];

        // Check if thumbnail image uploaded, add it to script
        if ($_FILES['editThumbnailImage']['size'] > 0) {
            $thumbnailImage = file_get_contents($_FILES['editThumbnailImage']['tmp_name']);
            $sql .= ", Thumbnail = :thumbnailImage";
            $params[':thumbnailImage'] = $thumbnailImage;
        }

        // Finalize update script
        $sql .= " WHERE ID = :artistID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        exit;
    }

    // Get artist details for editing
    if (isset($_POST['id'])) {
        $artistId = $_POST['id'];

        try {
            // Prepare script to get details
            $stmt = $pdo->prepare("SELECT * FROM Artists WHERE ID = :artist_id");
            $stmt->bindParam(':artist_id', $artistId);
            $stmt->execute();

            $artist = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($artist) {
?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Artist</h4>
                    </div>
                    <div class="modal-body">
                        <form id="editArtistForm" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="artistID" value="<?php echo $artist['ID']; ?>">
                            <div class="form-group">
                                <label for="editName">Artist Name:</label>
                                <input type="text" class="form-control" id="editName" name="editName" value="<?php echo $artist['Name']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="editLifeSpan">Life Span:</label>
                                <input type="text" class="form-control" id="editLifeSpan" name="editLifeSpan" value="<?php echo $artist['LifeSpan']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="editNationality">Nationality:</label>
                                <input type="text" class="form-control" id="editNationality" name="editNationality" value="<?php echo $artist['Nationality']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="editThumbnailImage">Thumbnail:</label>
                                <input type="file" class="form-control-file" id="editThumbnailImage" name="editThumbnailImage">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save Artist</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
<?php
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<script>
    // Prevent form submission on button click and validate fields
    document.getElementById('editArtistForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const fieldIDs = ['editName', 'editLifeSpan', 'editNationality'];
        if (fieldIDs.some(id => !document.getElementById(id).value.trim())) {
            alert('Please fill out all text fields.');
            return;
        }

        // Validate images
        validateImage('editThumbnailImage', 100, submitEditForm);
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
        const form = document.getElementById('editArtistForm');
        const formData = new FormData(form);
        formData.append('operation', 'EditArtist');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'editArtist.php', true);
        xhr.onload = () => {
            if (xhr.status === 200) {
                alert('Artist updated successfully');
                window.location.href = 'artists.php';
            } else {
                console.error('Error:', xhr.responseText);
            }
        };
        xhr.onerror = () => console.error('Error: Request failed');
        xhr.send(formData);
    }
</script>