<?php
$view = new stdClass();
$view->pageTitle = 'Your Adverts';
session_start();
require_once ('models/connectdb.php');
require_once ('models/Advert.php');
$advert = new Advert($connection);
$view->numberOfRows = $advert->countAdverts();
$view->adverts = $advert->returnAdverts();
$view->pageTitle = 'Your Adverts';
require_once('Views/youradverts.phtml');
