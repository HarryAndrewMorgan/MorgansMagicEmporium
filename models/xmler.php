<?php
require_once ('Advert.php');
require_once ('Database.php');
$_dbHandle = Database::getInstance()->getdbConnection();
$advert = new Advert($_dbHandle);
$q = $_REQUEST["q"];
$a = $advert->liveSearch($q);
$hint = "";
// lookup all hints from array if $q is different from ""
if ($q !== "") {
    //queries with entered string in lowercase
    $q = strtolower($q);
    //calculates q length
    $len=strlen($q);
    //Iterates through returned results and sets $name to each one
    foreach($a as $name) {
        if (stristr($q, substr($name, 0, $len))) {
            if ($hint === "") {
                $hint = $name;
            } else {
                $hint = " $name";
            }
        }
    }
}
//echoes no suggestion is no results are returned from livesearch()
echo $hint === "" ? "no suggestion" : $hint;
