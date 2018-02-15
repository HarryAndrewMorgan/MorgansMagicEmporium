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
    if ($username == "") {
        echo "<script>alert('Please enter a username')</script>";
    }
    elseif ($email == "") {
        echo "<script>alert('Please enter a email')</script>";
    }
    elseif ($pass == "") {
        echo "<script>alert('Please enter a password')</script>";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Please enter a valid email')</script>";
    }
    elseif (strlen($pass) < 8) {
        echo "<script>alert('Password must be longer than 8 characters')</script>";
    }
    elseif ($_POST['code'] != $_SESSION['random_code']) {
        echo "<script>alert('Incorrect captcha code')</script>";
    }
    elseif($user->login($username,$email,$pass))
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
