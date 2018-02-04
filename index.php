<?php


$view = new stdClass();
session_start();
require_once ('models/connectdb.php');
require_once ('models/Advert.php');
$advert = new Advert($connection);
$view->numberOfRows = $advert->countAdverts();
$view->adverts = $advert->returnAdverts();
$view->pageTitle = 'Homepage';
require_once('Views/index.phtml');

