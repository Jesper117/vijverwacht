<?php
require_once("../DataAccess/Database.php");

class RecordingAccess
{
    private $Database;

    public function __construct()
    {
        $this->Database = new db();
    }

    public function GetNextFileId()
    {
        $Query = "SELECT id FROM recordings ORDER BY id DESC LIMIT 1";
        $Result = $this->Database->Query($Query)->fetchAll();

        if (count($Result) === 0) {
            return 1;
        } else {
            return $Result[0]["id"] + 1;
        }
    }

    public function Publish($Path, $Size, $Thumbnail_Base64)
    {
        $Query = "INSERT INTO recordings (video_path, size, thumbnail_base64) VALUES ('$Path', '$Size', '$Thumbnail_Base64')";
        $Insert = $this->Database->Query($Query);

        if ($Insert->affectedRows() === 1) {
            return true;
        } else {
            return false;
        }
    }
}

?>