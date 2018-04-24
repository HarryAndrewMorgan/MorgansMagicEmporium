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
    $q = strtolower($q);
    $len=strlen($q);
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
echo $hint === "" ? "no suggestion" : $hint;



//} /for loop ends
//function autocomplete() {
//    var value = this.value;
//    document.getElementById('search').value = value;
//}
//document.getElementByTagName("option").addEventListener("click", autocomplete);
