<?php
require_once ('models/connectdb.php');
require_once ('models/User.php');
$user = new User($connection);
$view = new stdClass();
$view->pageTitle = 'Advert';
require_once('views/advert.phtml');
