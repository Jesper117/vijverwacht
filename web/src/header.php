<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

echo "<header>";
echo "<img draggable='false' src='content/logo_large.png'>";

if (isset($_SESSION["logged_in"])) {
    echo "<a class='logout' href='../Interface/Logout.php'>Uitloggen</a>";
}

echo "</header>";
?>