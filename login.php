<?php
require_once ('models/Database.php');
require_once ('models/User.php');
session_start();
$_dbHandle = Database::getInstance()->getdbConnection();
$user = new User($_dbHandle);
//if the user is logged in redirects them to index
if($user->is_loggedin()!="")
{
    $user->redirect('index.php');
}
//takes user input and strips of specialchars and whitespace for login validation
if(isset($_POST['btn-login']))
{
    $username = (trim($_POST['username'], ENT_NOQUOTES));
    $email = (trim($_POST['email'], ENT_NOQUOTES));
    $pass = (trim($_POST['password'], ENT_NOQUOTES));
    $userMail = (trim($_POST['username'], ENT_NOQUOTES));


//user info is taken and login is attempted, also setting session vars and redirecting to the users dashboard
    if($user->login($username,$email,$pass))
    {
        session_start();
        $_SESSION['Username'] = $username;
        $_SESSION['Email'] = $userMail;
        $user->redirect('dashboard.php');
    }
    else
    {
        echo "<script>alert('Wrong user details')</script>";
    }
}

$view = new stdClass();
$view->pageTitle = 'Login';
require_once('Views/login.phtml');
