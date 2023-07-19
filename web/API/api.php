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
    $KeyValid = $KeyService->VerifyKey($Key);

    if ($KeyValid) {
        $RequestEndpoint = $_GET["endpoint"];
        $Handler = $Endpoints[$RequestEndpoint];
        call_user_func($Handler);
    } else {
        http_response_code(401);
        echo "Invalid key.";
    }
} else {
    if (!isset($_GET["key"])) {
        http_response_code(400);
        echo "No key provided.";
    } else if (!isset($_GET["endpoint"])) {
        http_response_code(400);
        echo "No endpoint provided.";
    } else {
        http_response_code(400);
        echo "Invalid endpoint.";
    }
}

function Publish()
{
    if (isset($_FILES["video"])) {
        $RecordingService = new RecordingService();
        $RecordingService->Publish($_FILES["videos"]);
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