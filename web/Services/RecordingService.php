<?php
require_once("../DataAccess/RecordingAccess.php");
require_once("../Services/SafetyService.php");

class RecordingService
{
    private $RecordingAccess;
    private $SafetyService;

    public function __construct()
    {
        $this->RecordingAccess = new RecordingAccess();
        $this->SafetyService = new SafetyService();
    }

    private function GetNextFileId()
    {
        $Result = $this->RecordingAccess->GetNextFileId();
        return $Result;
    }

    private function GenerateThumbnail($Path, $TempPath)
    {
        $Name = uniqid();
        $thumbnailPath = $TempPath . $Name . ".jpg";
        $Command = 'ffmpeg -i ' . $Path . ' -ss 00:00:00.001 -vframes 1 -vf "scale=200:-1" ' . $thumbnailPath;

        exec($Command);

        $Thumbnail = file_get_contents($thumbnailPath);
        $Thumbnail_Base64 = base64_encode($Thumbnail);

        unlink($thumbnailPath);

        return $Thumbnail_Base64;
    }

    function GetAllRecordings()
    {
        $Result = $this->RecordingAccess->GetAllRecordings();
        $FormattedResult = array();

        foreach ($Result as $Recording) {
            $Time = $Recording["created_at"];
            $Time = strtotime($Time);

            $Recording["created_at"] = str_replace("-", "/", $Recording["created_at"]);
            $Recording["created_at"] = date("d/m/Y", strtotime($Recording["created_at"]));
            $Recording["created_at"] = $Recording["created_at"] . " &nbsp; " . date("H:i", $Time);

            $Size = $Recording["size"];
            if ($Size >= 1000000000) {
                $Recording["size"] = round($Size / 1000000000, 2) . " GB";
            } else if ($Size >= 1000000) {
                $Recording["size"] = round($Size / 1000000, 2) . " MB";
            } else if ($Size >= 1000) {
                $Recording["size"] = round($Size / 1000, 2) . " KB";
            } else {
                $Recording["size"] = $Size . " B";
            }

            array_push($FormattedResult, $Recording);
        }

        return $FormattedResult;
    }

    function GetRecordingById($Id)
    {
        $this->SafetyService->StringCheck($Id);

        $Recording = $this->RecordingAccess->GetRecordingById($Id);
        $FormattedResult = array();

        if ($Recording !== null && count($Recording) > 0) {
            $Time = $Recording["created_at"];
            $Time = strtotime($Time);

            $Recording["created_at"] = str_replace("-", "/", $Recording["created_at"]);
            $Recording["created_at"] = date("d/m/Y", strtotime($Recording["created_at"]));
            $Recording["created_at"] = $Recording["created_at"] . " &nbsp; " . date("H:i", $Time);

            $Size = $Recording["size"];
            if ($Size >= 1000000000) {
                $Recording["size"] = round($Size / 1000000000, 2) . " GB";
            } else if ($Size >= 1000000) {
                $Recording["size"] = round($Size / 1000000, 2) . " MB";
            } else if ($Size >= 1000) {
                $Recording["size"] = round($Size / 1000, 2) . " KB";
            } else {
                $Recording["size"] = $Size . " B";
            }

            $Recording["video_path"] = str_replace("C:/xampp/htdocs/vijverwacht/", "http://localhost/vijverwacht/", $Recording["video_path"]);
            //$Recording["video_path"] = "content/abc.mp4";

            $Recording["thumbnail_base64"] = "";

            array_push($FormattedResult, $Recording);
        }

        return $FormattedResult[0];
    }

    public function Publish($Recording)
    {
        if (isset($Recording["name"], $Recording["type"], $Recording["tmp_name"], $Recording["error"], $Recording["size"])) {
            $this->SafetyService->StringCheck($Recording["name"]);
            $this->SafetyService->StringCheck($Recording["type"]);
            $this->SafetyService->StringCheck($Recording["tmp_name"]);
            $this->SafetyService->StringCheck($Recording["error"]);
            $this->SafetyService->StringCheck($Recording["size"]);

            if ($Recording["type"] === "video/mp4") {
                $Size = $Recording["size"];

                $NewName = $this->GetNextFileId() . ".mp4";

                $AbsoluteNewPath = realpath($_SERVER["DOCUMENT_ROOT"]) . "\\vijverwacht\\videos\\" . $NewName;
                $AbsoluteTempPath = realpath($_SERVER["DOCUMENT_ROOT"]) . "\\vijverwacht\\temp\\";

                move_uploaded_file($Recording["tmp_name"], $AbsoluteNewPath);
                $Thumbnail_Base64 = $this->GenerateThumbnail($AbsoluteNewPath, $AbsoluteTempPath);

                $DisplayPath = str_replace("\\", "/", $AbsoluteNewPath);

                $Result = $this->RecordingAccess->Publish($DisplayPath, $Size, $Thumbnail_Base64);

                if ($Result) {
                    return ["success" => true, "code" => 200];
                } else {
                    return ["success" => false, "message" => "Recording could not be published.", "code" => 500];
                }
            } else {
                return ["success" => false, "message" => "Invalid recording file type.", "code" => 400];
            }
        } else {
            return ["success" => false, "message" => "No recording file.", "code" => 400];
        }
    }
}

?>