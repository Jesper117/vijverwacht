<?php
require_once("../DataAccess/Database.php");

class KeyAccess
{
    private $Database;

    public function __construct()
    {
        $this->Database = new db();
    }

    public function GetAllKeys()
    {
        $Keys = $this->Database->Query("SELECT * FROM `keys`")->fetchAll();
        return $Keys;
    }
}

?>