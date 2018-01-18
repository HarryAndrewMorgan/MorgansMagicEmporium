<?php

function __autoload($classname)
{
    $filename = "./models" . $classname . ".php";
    include_once($filename);

}
?>