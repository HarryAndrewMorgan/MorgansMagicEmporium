<?php
$host = "helios.csesalford.com";
$dbname = "stc628";
$user = "stc628";
$pass = "21697abC123!";
$dbname = "stc628";

try {
    $connection = new PDO("mysql:host=$host;dbname=$dbname",$user,$pass);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
