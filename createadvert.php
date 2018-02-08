<?php
session_start();
require_once ('models/Database.php');
require_once ('models/Advert.php');
$_dbHandle = Database::getInstance()->getdbConnection();
$advert = new Advert($_dbHandle);
if(isset($_POST['btn-create']))
{
    $name = (trim($_POST['name'], ENT_NOQUOTES));
    $price = (trim($_POST['price'],ENT_NOQUOTES));
    $description = (trim($_POST['description'],ENT_NOQUOTES));
    $type = (trim($_POST['type'],ENT_NOQUOTES));
    $picture = $_FILES['file']['name'];
    $userID = $_SESSION['UserID'];
    
    try {
        if ($advert->createAdvert($name, $price, $description, $type, $userID, $picture)) {
            $advert->redirect('youradverts.php');
        }
    } catch (PDOException $exception) {
        echo $exception->getMessage();
    }
}
$view = new stdClass();
$view->pageTitle = 'Create Advert';
require_once('Views/createadvert.phtml');
