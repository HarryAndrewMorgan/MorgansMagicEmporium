<?php
require_once ('models/Database.php');
require_once ('models/User.php');
$_dbHandle = Database::getInstance()->getdbConnection();
$user = new User($_dbHandle);
session_start();
if(!isset($_SESSION['Username']))
{
    $user->redirect('index.php');
    exit;
}
$username = $_SESSION['Username'];
$email = $_SESSION['Email'];
$sqlQuery = $_dbHandle->prepare("SELECT * FROM Users WHERE Username=".$username);
$sqlQuery->execute(array(":user"=>$username));
$userRow=$sqlQuery->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['logout']))
{
    $user->logout();
    echo "You are being logged out";
}
$view = new stdClass();
$view->pageTitle = 'Dashboard';
require_once('Views/dashboard.phtml');
