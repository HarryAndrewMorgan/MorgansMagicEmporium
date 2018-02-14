<?php
$view = new stdClass();
$view->pageTitle = 'Saved Adverts';
session_start();
require_once ('models/Database.php');
require_once ('models/Advert.php');
$_dbHandle = Database::getInstance()->getdbConnection();
$advert = new Advert($_dbHandle);
$view->adverts = $advert->returnSavedAdverts($_SESSION['UserID']);
require_once('Views/savedadverts.phtml');
