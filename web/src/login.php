<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["logged_in"])) {
    header("Location: ../src/library.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Vijverwacht</title>
    <link rel="icon" href="content/logo_small.png" type="image/x-icon"/>
    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php include_once("../src/header.php") ?>

<form class="information login" action="../Interface/Login.php" method="POST">
    <h2>Inloggen</h2>

    <label>Sleutel:</label>
    <input name="key" type="password">

    <?php
    if (isset($_SESSION["login_callback"])) {
        echo "<label class='error'>" . $_SESSION["login_callback"] . "</label>";
        unset($_SESSION["login_callback"]);
    }
    ?>

    <button type="submit">Login</button>
</form>

</body>
</html>