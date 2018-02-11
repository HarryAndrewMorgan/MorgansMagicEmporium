<?php
$view = new stdClass();
session_start();
require_once ('models/Database.php');
require_once ('models/Advert.php');
$_dbHandle = Database::getInstance()->getdbConnection();
$advert = new Advert($_dbHandle);
$type="Singles";
if(isset($_GET['filter']))
{
    $filter = $_GET['filter'];
    if ($filter == 1){
        $type = "Singles";
        $_SESSION['filterName'] = "Singles";
    } elseif ($filter == 2) {
        $type = "Boosters";
        $_SESSION['filterName'] = "Boosters";
    } elseif ($filter == 3) {
        $type="Accessories";
        $_SESSION['filterName'] = "Accessories";
    }
}
$view->adverts = $advert->filterAdverts($type);
$view->pageTitle = 'Homepage';
require_once('Views/index.phtml');

