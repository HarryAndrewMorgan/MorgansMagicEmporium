<?php
require_once ('models/Database.php');
require_once ('models/User.php');
session_start();
$_dbHandle = Database::getInstance()->getdbConnection();
$user = new User($_dbHandle);
//trimming the submitted data into post variables
if(isset($_POST['btn-signup'])) {

    $username = (trim($_POST['username'], ENT_NOQUOTES));
    $email = (trim($_POST['email'], ENT_NOQUOTES));
    $pass = (trim($_POST['pass'], ENT_NOQUOTES));
    $address = (trim($_POST['address'], ENT_NOQUOTES));
    $phone = (trim($_POST['phone'], ENT_NOQUOTES));

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
    elseif ($address == "") {
        echo "<script>alert('Please enter a postcode')</script>";
    }
    elseif ($phone == "") {
        echo "<script>alert('Please enter a phone number')</script>";
    }
    elseif ($_POST['code'] != $_SESSION['random_code']) {
        echo "<script>alert('Incorrect captcha code')</script>";
    }
    elseif ($user->checkDuplicateUser($username, $email)) {
        echo "<script>alert('User already exists')</script>";
    }
    elseif ($user->register($username, $email, $pass, $address, $phone)) {
        $user->redirect('login.php');
    }
}
$view = new stdClass();
$view->pageTitle = 'Register';
require_once('Views/register.phtml');

