<?php
require_once ('models/Database.php');
require_once ('models/User.php');
require_once ('models/Advert.php');
session_start();
$_dbHandle = Database::getInstance()->getdbConnection();
$user = new User($_dbHandle);
$advert = new Advert($_dbHandle);
$view = new stdClass();
$view->pageTitle = 'Advert';
require_once('views/advert.phtml');
