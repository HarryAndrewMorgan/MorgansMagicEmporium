<?php
require_once ('models/Database.php');
require_once ('models/User.php');
session_start();
$_dbHandle = Database::getInstance()->getdbConnection();
$user = new User($_dbHandle);
if($user->is_loggedin()!="")
{
    $user->redirect('index.php');
}
if(isset($_POST['btn-login']))
{
    $username = (trim($_POST['username'], ENT_NOQUOTES));
    $email = (trim($_POST['email'], ENT_NOQUOTES));
    $pass = (trim($_POST['password'], ENT_NOQUOTES));
    $userMail = (trim($_POST['username'], ENT_NOQUOTES));



    if($user->login($username,$email,$pass))
    {
        session_start();
        $_SESSION['Username'] = $username;
        $_SESSION['Email'] = $userMail;
        $user->redirect('index.php');
    }
    else
    {
        $error = "Wrong Details !";
    }
}

$view = new stdClass();
$view->pageTitle = 'Login';
require_once('Views/login.phtml');
