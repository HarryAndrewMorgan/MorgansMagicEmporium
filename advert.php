<?php
require_once ('models/Database.php');
require_once ('models/User.php');
require_once ('models/Advert.php');
session_start();
$_dbHandle = Database::getInstance()->getdbConnection();
$user = new User($_dbHandle);
$advert = new Advert($_dbHandle);
$view = new stdClass();
//fetches the id of the advert that has been clicke on and returns it as an object and puts it into $view for retrieval in the page
if(isset($_GET['id']))
{
    $view->adverts = $advert->returnAdvert($_GET['id']);
}
//saves the advert the user is browsing
if(isset($_POST['saveAdvert']))
{
    $advert->saveAdvert($_SESSION['UserID'], $_GET['id']);
}
//unsaves teh adver the user is browsing
if(isset($_POST['unsaveAdvert']))
{
    $advert->removeSaved($_GET['id'], $_SESSION['UserID']);
}
$view->pageTitle = 'Advert';
require_once('views/advert.phtml');

//test semester 2 commit
