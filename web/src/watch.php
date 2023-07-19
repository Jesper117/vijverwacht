<?php
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

    <title>Opname #1 - Vijverwacht</title>
    <link rel="icon" href="content/logo_small.png" type="image/x-icon"/>
    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php include_once("../src/header.php") ?>

<div class="video-container">
    <video controls autoplay loop muted>
        <source src="content/placeholder.mp4" type="video/mp4">
        Je browser heeft geen support voor de video tag.
    </video>
</div>

<div class="information">
    <h2>Opname #1</h2>
    <label>Opgenomen: 16/07/2023 &nbsp; 01:49</label> <br>
    <label>Lengte: 05:30</label>
</div>

</body>
</html>