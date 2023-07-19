<?php
require_once("../Services/SafetyService.php");
require_once("../Services/KeyService.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST["key"])) {
    $SafetyService = new SafetyService();
    $KeyService = new KeyService();

    $Key = $_POST["key"];

    $SafetyService->StringCheck($Key);

    $KeyValid = $KeyService->VerifyKey($Key);

    if ($KeyValid) {
        $_SESSION["logged_in"] = true;

        header("Location: ../src/library.php");
        exit();
    } else {
        $_SESSION["login_callback"] = "Onjuiste sleutel.";

        header("Location: ../src/login.php");
        exit();
    }
}
?>