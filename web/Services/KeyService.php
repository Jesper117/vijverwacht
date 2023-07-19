<?php
require_once("../DataAccess/KeyAccess.php");
require_once("../Services/LogService.php");

class KeyService
{
    private $KeyAccess;
    private $LogService;

    public function __construct()
    {
        $this->KeyAccess = new KeyAccess();
        $this->LogService = new LogService();
    }

    public function VerifyKey($Key)
    {
        $Keys = $this->KeyAccess->GetAllKeys();
        $Matches = false;

        foreach ($Keys as $StoredKey) {
            $HashCheck = password_verify($Key, $StoredKey["key"]);
            if ($HashCheck) {
                $Matches = true;
            }
        }

        if ($Matches) {
            $this->LogService->Log("Passed key validation.");
            return true;
        } else {
            $this->LogService->Log("Failed key validation.");
            return false;
        }
    }
}

?>