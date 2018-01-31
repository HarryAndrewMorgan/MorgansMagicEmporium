<?php
require_once ('models/connectdb.php');
require_once ('models/User.php');
$user = new User($connection);
session_start();
if(!isset($_SESSION['username']))
{
    $user->redirect('index.php');
    exit;
}
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$sqlQuery = $connection->prepare("SELECT * FROM Users WHERE Username=".$username);
$sqlQuery->execute(array(":user"=>$username));
$userRow=$sqlQuery->fetch(PDO::FETCH_ASSOC);
echo $userRow;
if(isset($_POST['logout']))
{
    $user->logout();
    echo "logoutbeingcalled";
}
$view = new stdClass();
$view->pageTitle = 'Dashboard';
require_once('Views/dashboard.phtml');
