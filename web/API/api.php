<?php
require_once("../Services/RecordingService.php");
require_once("../Services/KeyService.php");
require_once("../Services/SafetyService.php");

$Endpoints = [
    "publish" => "Publish"
];

if ((isset($_GET["key"], $_GET["endpoint"])) && array_key_exists($_GET["endpoint"], $Endpoints)) {
    $SafetyService = new SafetyService();
    $KeyService = new KeyService();

    $Key = $_GET["key"];

    $SafetyService->StringCheck($Key);
    $SafetyService->StringCheck($_GET["endpoint"]);

    $KeyValid = $KeyService->VerifyKey($Key);

    if ($KeyValid) {
        $RequestEndpoint = $_GET["endpoint"];
        $Handler = $Endpoints[$RequestEndpoint];
        call_user_func($Handler);
    } else {
        http_response_code(401);
        Respond(["error" => "Invalid key."]);
    }
} else {
    if (!isset($_GET["key"])) {
        http_response_code(400);
        Respond(["error" => "No key provided."]);
    } else if (!isset($_GET["endpoint"])) {
        http_response_code(400);
        Respond(["error" => "No endpoint provided."]);
    } else {
        http_response_code(400);
        Respond(["error" => "Invalid endpoint."]);
    }
}

function Publish()
{
    if (isset($_FILES["video"])) {
        $RecordingService = new RecordingService();
        $Result = $RecordingService->Publish($_FILES["video"]);

        if ($Result["success"]) {
            Respond(["success" => "Recording uploaded successfully."]);
        } else {
            http_response_code($Result["code"]);
            Respond($Result);
        }
    } else {
        http_response_code(400);
        Respond(["error" => "No videos were uploaded."]);
    }
}

function Respond($Data)
{
    header("Content-Type: application/json");
    echo json_encode($Data);
}

?>