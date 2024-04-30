<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Artists by Century</title>
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
    <h3>Artists Gallery by Century</h3>
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
        $centuryRequested = isset($_GET['century']) ? intval($_GET['century']) : 0;

        $sql = "SELECT Name, LifeSpan, Nationality, thumbNailImage FROM Artists";
        $stmt = $pdo->query($sql);

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $lifeSpan = $row['LifeSpan'];
                $years = explode('–', $lifeSpan);
                $birthYear = intval($years[0]);
                $deathYear = intval($years[1]);
                $birthCentury = (int)($birthYear / 100) + 1;
                $deathCentury = (int)($deathYear / 100) + 1;

                if ($birthCentury === $centuryRequested || $deathCentury === $centuryRequested) {
                    ?>
                    <tr>
                        <td><img src="data:image/jpeg;base64,<?= base64_encode($row['thumbNailImage']) ?>" alt="<?= htmlspecialchars($row['Name']) ?>" style="width:100px;"></td>
                        <td><?= htmlspecialchars($row['Name']) ?></td>
                        <td><?= htmlspecialchars($row['LifeSpan']) ?></td>
                        <td><?= htmlspecialchars($row['Nationality']) ?></td>
                    </tr>
                    <?php
                }
            }
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