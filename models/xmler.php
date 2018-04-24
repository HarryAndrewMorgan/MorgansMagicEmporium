<?php
require_once ('models/Advert.php');
require_once ('models/Database.php');
$_dbHandle = Database::getInstance()->getdbConnection();
$advert = new Advert($_dbHandle);







$q = $_REQUEST["q"];
$a = array($advert->liveSearch("A"));
echo implode(",",$a);
$hint = "";
// lookup all hints from array if $q is different from ""
if ($q !== "") {
    $q = strtolower($q);
    $len=strlen($q);
    foreach($a as $name) {
        if (stristr($q, substr($name, 0, $len))) {
            if ($hint === "") {
                $hint = $name;
            } else {
                $hint = ", $name";
            }
        }
    }
}
echo $hint === "" ? "no suggestion" : $hint;