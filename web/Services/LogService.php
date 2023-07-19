<?php
require_once("../DataAccess/LogAccess.php");
require_once("../Services/SafetyService.php");

class LogService
{
    private $LogAccess;
    private $SafetyService;

    public function __construct()
    {
        $this->LogAccess = new LogAccess();
        $this->SafetyService = new SafetyService();
    }

    public function Log($Action)
    {
        $Ip = $_SERVER["REMOTE_ADDR"];

        $this->SafetyService->StringCheck($Action);
        $this->SafetyService->StringCheck($Ip);
    }
}

?>