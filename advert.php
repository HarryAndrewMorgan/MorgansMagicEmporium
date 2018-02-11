<?php
require_once ('models/Database.php');
require_once ('models/User.php');
require_once ('models/Advert.php');
session_start();
$_dbHandle = Database::getInstance()->getdbConnection();
$user = new User($_dbHandle);
$advert = new Advert($_dbHandle);
$view = new stdClass();
$advertID = "";
$view->adverts = $advert->returnAdvert("15");

//$view->adverts = $advert->fetchUserByID("40");
$view->pageTitle = 'Advert';
require_once('views/advert.phtml');
