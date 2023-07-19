<?php
require_once("../DataAccess/Database.php");

class LogAccess
{
    private $Database;

    public function __construct()
    {
        $this->Database = new db();
    }

    public function AddLog($Ip, $Action)
    {
        $Query = "INSERT INTO logs (ip, action) VALUES ('$Ip', '$Action')";
        $Insert = $this->Database->Query($Query);

        if ($Insert->affectedRows() === 1) {
            return true;
        } else {
            return false;
        }
    }
}

?>