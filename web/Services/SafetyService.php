<?php
require_once("../Services/LogService.php");

class SafetyService
{
    private $LogService;

    public function __construct()
    {
        $this->LogService = new LogService();
    }

    public function StringContainsSQLInjections($String)
    {
        $pattern = '/\b(union|select|insert|update|delete|drop|truncate|create|alter)\b/i';

        if (preg_match($pattern, $String)) {
            return true;
        } else {
            return false;
        }
    }

    public function StringContainsXSS($String)
    {
        $pattern = '/<script\b[^>]*>(.*?)<\/script>/i';

        if (preg_match($pattern, $String)) {
            return true;
        } else {
            return false;
        }
    }

    public function StringContainsHTML($String)
    {
        $pattern = '/<[^>]*>/i';

        if (preg_match($pattern, $String)) {
            return true;
        } else {
            return false;
        }
    }

    public function StringContainsPHP($String)
    {
        $pattern = '/<\?php\b[^>]*>/i';

        if (preg_match($pattern, $String)) {
            return true;
        } else {
            return false;
        }
    }

    public function VerifyStringIntegrity($String)
    {
        if ($this->StringContainsSQLInjections($String) || $this->StringContainsXSS($String) || $this->StringContainsHTML($String) || $this->StringContainsPHP($String)) {
            return false;
        } else {
            return true;
        }
    }

    public function StringCheck($String)
    {
        $String = (string)$String;
        $Result = $this->VerifyStringIntegrity($String);

        if (!$Result) {
            $this->LogService->Log("Attempt to inject SQL or XSS.");

            header("Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");
            exit();
        } else {
            $this->LogService->Log("Passed StringCheck.");

            return true;
        }
    }
}

?>