<?php
session_start();
require_once ('models/Database.php');
require_once ('models/Advert.php');
$_dbHandle = Database::getInstance()->getdbConnection();
$advert = new Advert($_dbHandle);
//when button is pressed input is stripped and removes specialchars then is validated in multiple ways for empty inputs
if(isset($_POST['btn-create']))
{
    $name = (trim($_POST['name'], ENT_NOQUOTES));
    $price = (trim($_POST['price'],ENT_NOQUOTES));
    $description = (trim($_POST['description'],ENT_NOQUOTES));
    $type = (trim($_POST['type'],ENT_NOQUOTES));
    $picture = $_FILES['file']['name'];
    $userID = $_SESSION['UserID'];
    $date = date('Y-m-d');
    $expiry = date('Y-m-d', strtotime("+14 days"));
    if ($name == "") {
        echo "<script>alert('Please enter a name')</script>";
    }
    elseif ($price == "") {
        echo "<script>alert('Please enter a price')</script>";
    }
    elseif ($description == "") {
        echo "<script>alert('Please enter a description')</script>";
    }
    elseif ($picture == "") {
        echo "<script>alert('Please enter a picture')</script>";
    }
//tries to create an advert then redirect to your adverts
    try {
        if ($advert->createAdvert($name, $price, $description, $type, $userID, $picture, $date, $expiry)) {
            $advert->redirect('youradverts.php');
        }
    } catch (PDOException $exception) {
        echo $exception->getMessage();
    }
}
$view = new stdClass();
$view->pageTitle = 'Create Advert';
require_once('Views/createadvert.phtml');
