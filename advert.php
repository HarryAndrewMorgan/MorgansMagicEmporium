<?php
require_once ('models/Database.php');
require_once ('models/User.php');
$_dbHandle = Database::getInstance()->getdbConnection();
$user = new User($_dbHandle);
$view = new stdClass();
$view->pageTitle = 'Advert';
require_once('views/advert.phtml');
