<?php
$view = new stdClass();
session_start();
require_once ('models/Database.php');
require_once ('models/Advert.php');
$_dbHandle = Database::getInstance()->getdbConnection();
$advert = new Advert($_dbHandle);
$view->numberOfRows = $advert->countAdverts();
$view->adverts = $advert->returnAdverts();
$view->pageTitle = 'Homepage';
require_once('Views/index.phtml');

