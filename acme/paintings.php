<!DOCTYPE html>
<html>
<head>
    <title>Paintings Gallery</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Adjustments to fit the image in the column */
        .painting-img img {
            max-width: 100%;
            height: auto;
            
        }

        /* Reduce font size for painting information */
        .painting p {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5 mb-4">Paintings Gallery</h1>

        <?php
		
		$servername = "localhost";
		$username = "adminer";
		$password = "P@ssw0rd";
		$db = "ACME";
        // Database connection
        $pdo = new PDO('mysql:host=' . $servername . ';dbname=' . $db, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch all paintings
        $stmt = $pdo->query("SELECT * FROM Paintings");
        $paintings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Display paintings
        foreach ($paintings as $painting) {
            echo '<div class="row mb-4">';
            echo '<div class="col-md-4">';
            echo '<div class="painting-img">';
            echo '<img src="data:image/jpeg;base64,' . base64_encode($painting['Thumbnail']) . '" alt="' . $painting['Title'] . '" class="img-fluid">';
            echo '</div>';
            echo '</div>';
            echo '<div class="col-md-8">';
            echo '<div class="painting">';
            echo '<h5>' . $painting['Title'] . '</h5>';
            echo '<p><strong>Finished:</strong> ' . $painting['Completed'] . '</p>';
            echo '<p><strong>Media:</strong> ' . $painting['Media'] . '</p>';

            // Fetch artist name
            $artistStmt = $pdo->prepare("SELECT Name FROM Artists WHERE ID = ?");
            $artistStmt->execute([$painting['ArtistID']]);
            $artist = $artistStmt->fetchColumn();
            echo '<p><strong>Artist:</strong> ' . $artist . '</p>';

            // Fetch style name
            $styleStmt = $pdo->prepare("SELECT Name FROM Styles WHERE ID = ?");
            $styleStmt->execute([$painting['StyleID']]);
            $style = $styleStmt->fetchColumn();
            echo '<p><strong>Style:</strong> ' . $style . '</p>';

            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>

    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>