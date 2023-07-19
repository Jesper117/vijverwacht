<?php
require_once("../DataAccess/KeyAccess.php");

class KeyService
{
    private $KeyAccess;

    public function __construct()
    {
        $this->KeyAccess = new KeyAccess();
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

        return $Matches;
    }
}

?>