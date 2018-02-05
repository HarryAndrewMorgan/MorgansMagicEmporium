<?php
session_start();
require_once ('models/Database.php');
require_once ('models/Advert.php');
$_dbHandle = Database::getInstance()->getdbConnection();
$advert = new Advert($_dbHandle);
if(isset($_POST['btn-create']))
{
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $type = trim($_POST['type']);
    //$picture = $_FILES['file']['name'];
    //$data = file_get_contents($_FILES["file"]["tmp_name"]);
    $imgtype = $_FILES["file"]["type"];
    $userID = $_SESSION['UserID'];
    try {
        if ($advert->createAdvert($name, $price, $description, $type, $userID)) {
            $advert->redirect('youradverts.php');
        }
    } catch (PDOException $exception) {
        echo $exception->getMessage();
    }
}
$view = new stdClass();
$view->pageTitle = 'Create Advert';
require_once('Views/createadvert.phtml');
