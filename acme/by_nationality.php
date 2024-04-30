<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Artists by Nationality</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php include 'menu.php'; ?> 

<div class="container">
    <h3>Artists Gallery by Nationality</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Thumbnail</th>
                <th>Name</th>
                <th>Life Span</th>
                <th>Nationality</th>
            </tr>
        </thead>
        <tbody>
        <?php
        require_once 'config.php';
        $nationality = isset($_GET['nationality']) ? $_GET['nationality'] : '';

        $sql = "SELECT Name, LifeSpan, Nationality, thumbNailImage FROM Artists WHERE Nationality = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nationality]);

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td><img src="data:image/jpeg;base64,<?= base64_encode($row['thumbNailImage']) ?>" alt="<?= $row['Name'] ?>" style="width:100px;"></td>
                    <td><?= htmlspecialchars($row['Name']) ?></td>
                    <td><?= htmlspecialchars($row['LifeSpan']) ?></td>
                    <td><?= htmlspecialchars($row['Nationality']) ?></td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='4'>No artists found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<footer class="text-center">
    <a class="up-arrow" href="#myPage" title="TO TOP">
        <span class="glyphicon glyphicon-chevron-up"></span>
    </a>
</footer>

</body>
</html>