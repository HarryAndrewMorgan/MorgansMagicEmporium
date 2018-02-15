<?php
require_once ('models/Database.php');
require_once ('models/User.php');
$_dbHandle = Database::getInstance()->getdbConnection();
$user = new User($_dbHandle);
session_start();
//if the user session isnt set then the user shouldnt be here and is redirected
if(!isset($_SESSION['Username']))
{
    $user->redirect('index.php');
    exit;
}
//sets some session variables
$username = $_SESSION['Username'];
$email = $_SESSION['Email'];
$user->fetchAUser($username);
//logs the user out
if(isset($_POST['logout']))
{
    $user->logout();
    echo "You are being logged out";
}
$view = new stdClass();
$view->pageTitle = 'Dashboard';
require_once('Views/dashboard.phtml');
