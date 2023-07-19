<?php
require_once("../Services/RecordingService.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["logged_in"])) {
    header("Location: ../src/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Opnames - Vijverwacht</title>
    <link rel="icon" href="content/logo_small.png" type="image/x-icon"/>
    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php include_once("../src/header.php") ?>

<section class="video-library">
    <div class="container">
        <?php
        $RecordingService = new RecordingService();
        $Recordings = $RecordingService->GetAllRecordings();

        if (count($Recordings) === 0) {
            echo "<label>Er zijn nog geen opnames gemaakt.</label>";
        } else {
            foreach ($Recordings as $Recording) {
                $Id = $Recording["id"];
                $Path = $Recording["video_path"];
                $Thumbnail_Base64 = $Recording["thumbnail_base64"];
                $Size = $Recording["size"];
                $Date = $Recording["created_at"];

                echo "<div class='row'>";
                echo "<div class='col-md-4'>";
                echo "<div class='video-card'>";
                echo "<input id='recording_id' type='number' value='$Id' hidden readonly disabled>";
                echo "<img draggable='false' src='data:image/jpeg;base64,$Thumbnail_Base64'>";
                echo "<h2>Opname #$Id</h2>";
                echo "<label>Opgenomen: $Date</label> <br>";
                echo "<label>Grootte: $Size</label>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        }
        ?>
    </div>
</section>
</body>

<script src="js/navigation.js"></script>
</html>
