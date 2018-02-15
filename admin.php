<?php
$view = new stdClass();
session_start();
require_once ('models/Database.php');
require_once ('models/Advert.php');
//fetches the db connection
$_dbHandle = Database::getInstance()->getdbConnection();
//declares new class advert passing the connection to db
$advert = new Advert($_dbHandle);
//expires adverts passed expiry date
$advert->expireAdverts();
//declares default filter type to be advert
$type="Adverts";
//sets the session variable filter name to be adverts by default to avoid undefined index
$_SESSION['filterName'] = "Adverts";
//Checks what the filter is to be displayed on the page as well as the name of filter to be displayed
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
//displays either adverts or users depending on admins choice of filter
$view->adverts = $advert->adminFilter($type);
//gets search query and executes it
if(isset($_GET['search']))
{
    $query = (trim($_GET['query'], ENT_NOQUOTES));
    $view->adverts = $advert->searchAdverts($query);
}
//deletes an advert by its id from href
if(isset($_GET['deleteAdvert']))
{
    $advert->deleteAdvert($_GET['deleteAdvert']);
}
//deletes a user by its id from href
if(isset($_GET['deleteUser']))
{
    $advert->deleteUser($_GET['deleteUser']);
}
$view->pageTitle = 'Admin';
require_once('Views/admin.phtml');

