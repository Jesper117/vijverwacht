<?php
require_once("../Services/RecordingService.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["logged_in"])) {
    header("Location: ../src/login.php");
    exit();
} else if (!isset($_GET["recording_id"])) {
    header("Location: ../src/library.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Opname #1 - Vijverwacht</title>
    <link rel="icon" href="content/logo_small.png" type="image/x-icon"/>
    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php include_once("../src/header.php") ?>

<?php
$RecordingService = new RecordingService();
$Recording = $RecordingService->GetRecordingById($_GET["recording_id"]);

echo "<div class='video-container'>";
echo "<video controls autoplay loop muted>";
echo "<source src='" . $Recording["video_path"] . "' type='video/mp4'>";
echo "Je browser heeft geen support voor de video tag.";
echo "</video>";
echo "</div>";

$Id = $Recording["id"];
$Size = $Recording["size"];
$Date = $Recording["created_at"];

echo "<div class='information'>";
echo "<h2>Opname #$Id</h2>";
echo "<label>Opgenomen: $Date</label> <br>";
echo "<label>Grootte: $Size</label>";
echo "</div>";
?>

</body>
</html>