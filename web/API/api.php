<?php
require_once("../Services/RecordingService.php");
require_once("../Services/KeyService.php");
require_once("../Services/SafetyService.php");
require_once("../Services/LogService.php");

$LogService = new LogService();

$Endpoints = [
    "publish" => "Publish"
];

if ((isset($_GET["key"], $_GET["endpoint"])) && array_key_exists($_GET["endpoint"], $Endpoints)) {
    $SafetyService = new SafetyService();
    $KeyService = new KeyService();

    $Key = $_GET["key"];

    $SafetyService->StringCheck($Key);
    $SafetyService->StringCheck($_GET["endpoint"]);

    $LogService->Log("Requested api/" . $_GET["endpoint"]);

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
        $LogService->Log("API request without key.");

        http_response_code(400);
        Respond(["error" => "No key provided."]);
    } else if (!isset($_GET["endpoint"])) {
        $LogService->Log("API request without endpoint.");

        http_response_code(400);
        Respond(["error" => "No endpoint provided."]);
    } else {
        $LogService->Log("API request to invalid endpoint.");

        http_response_code(400);
        Respond(["error" => "Invalid endpoint."]);
    }
}

function Publish()
{
    $LogService = new LogService();

    if (isset($_FILES["video"])) {
        $RecordingService = new RecordingService();
        $Result = $RecordingService->Publish($_FILES["video"]);

        if ($Result["success"]) {
            $LogService->Log("Uploaded a recording.");
            Respond(["success" => "Recording uploaded successfully."]);
        } else {
            $LogService->Log("Failed to upload a recording (1).");
            http_response_code($Result["code"]);
            Respond($Result);
        }
    } else {
        $LogService->Log("Failed to upload a recording (2).");
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