<?php
$view = new stdClass();
session_start();
require_once ('models/Database.php');
require_once ('models/Advert.php');
//gets db connection
$_dbHandle = Database::getInstance()->getdbConnection();
//creates new advert with db conn
$advert = new Advert($_dbHandle);

//expires any adverts past expiry date
$advert->expireAdverts();
//sets default filter to be singles for default display
$type="Singles";
$_SESSION['filterName'] = "Singles";
//filters adverts via their type and sets session variable for display of filter type on page
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
    } elseif ($filter == 4) {
        $type = "A-Z";
        $_SESSION['filterName'] = "Name A-Z";
    } elseif ($filter == 5) {
        $type = "High-Low";
        $_SESSION['filterName'] = "Price High-Low";
    }
}
//filters the adverts with a query
$view->adverts = $advert->filterAdverts($type);
//takes a search query and executes it returning any results
if(isset($_GET['search']))
{
    $query = (trim($_REQUEST['query'], ENT_NOQUOTES));
    $view->adverts = $advert->searchAdverts($query);
}
$view->pageTitle = 'Homepage';
require_once('Views/index.phtml');

