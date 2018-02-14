<?php
$view = new stdClass();
session_start();
require_once ('models/Database.php');
require_once ('models/Advert.php');
$_dbHandle = Database::getInstance()->getdbConnection();
$advert = new Advert($_dbHandle);
$advert->expireAdverts();
$type="Adverts";
$_SESSION['filterName'] = "Adverts";
if(isset($_GET['filter']))
{
    $filter = $_GET['filter'];
    if ($filter == 1){
        $type = "Adverts";
        $_SESSION['filterName'] = "Adverts";
    } elseif ($filter == 2) {
        $type = "Users";
        $_SESSION['filterName'] = "Users";
    } elseif ($filter == 3) {
        $type="Other";
        $_SESSION['filterName'] = "Other";
    }
}
$view->adverts = $advert->adminFilter($type);
if(isset($_GET['search']))
{
    $query = (trim($_GET['query'], ENT_NOQUOTES));
    $view->adverts = $advert->searchAdverts($query);
}

$view->pageTitle = 'Admin';
require_once('Views/admin.phtml');

