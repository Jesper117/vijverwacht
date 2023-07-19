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
        $Query = "SELECT MAX(FileId) FROM recordings";
        $Result = $this->Database->ExecuteQuery($Query);
        $Result = $Result->fetch_assoc();
        $Result = $Result["MAX(FileId)"];
        $Result++;

        return $Result;
    }

    public function Publish($Recording)
    {
    }
}
?>