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

    if ($name == "") {
        $error[] = "Please provide a name";
    }
    if ($price == "") {
        $error[] = "Please provide a price";
    }
    if ($description == "") {
        $error[] = "Please provide a description";
    }
    if ($type == "") {
    $error[] = "Please provide a relevant type";
    }
    //check if details entered have been taken by another user
    else {
        try {
            if ($advert->createAdvert($name, $price, $description, $type)) {
                $advert->redirect('youradverts.php');
            }
        } catch (PDOException $exception) {
            echo $exception->getMessage();
        }
    }
}
$view = new stdClass();
$view->pageTitle = 'Create Advert';
require_once('Views/createadvert.phtml');
