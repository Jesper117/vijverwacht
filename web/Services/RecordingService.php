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

private function GenerateThumbnail($Path)
    {
        if (!shell_exec('command -v ffmpeg')) {
            throw new Exception("FFmpeg not found. Make sure FFmpeg is installed on the server.");
        }

        $thumbnailPath = tempnam(sys_get_temp_dir(), 'thumbnail');
        $thumbnailPath .= '.jpg';

        $ffmpegCmd = "ffmpeg -i " . escapeshellarg($Path) . " -ss 00:00:00.001 -vframes 1 -vf 'scale=-1:120' " . escapeshellarg($thumbnailPath) . " 2>&1";
        exec($ffmpegCmd, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new Exception("Thumbnail generation failed. FFmpeg output: " . implode("\n", $output));
        }

        $thumbnailData = file_get_contents($thumbnailPath);
        $base64Thumbnail = base64_encode($thumbnailData);
        unlink($thumbnailPath);

        return $base64Thumbnail;
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

                $NewPath = "../../videos/" . $NewName;
                $AbsoluteNewPath = realpath($NewPath);

                move_uploaded_file($Recording["tmp_name"], $AbsoluteNewPath);
                $Thumbnail_Base64 = $this->GenerateThumbnail($AbsoluteNewPath);

                $this->RecordingAccess->Publish($AbsoluteNewPath, $Size, $Thumbnail_Base64);
            } else {
                http_response_code(400);
                Respond(["error" => "Invalid file type."]);
            }
        } else {
            http_response_code(400);
            Respond(["error" => "Invalid recording."]);
        }
    }
}

?>