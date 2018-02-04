<?php
session_start();
require_once ('models/connectdb.php');
require_once ('models/Advert.php');
$advert = new Advert($connection);

if(isset($_POST['btn-create']))
{
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $type = trim($_POST['type']);
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
