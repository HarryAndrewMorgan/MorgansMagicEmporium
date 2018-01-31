<?php
require_once ('models/connectdb.php');
require_once ('models/User.php');
$user = new User($connection);
if($user->is_loggedin()!="")
{
    $user->redirect('index.php');
}
if(isset($_POST['btn-login']))
{
    $username = $_POST['username'];
    $email = $_POST['username'];
    $pass = $_POST['password'];
    $userMail = $_POST['email'];


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
