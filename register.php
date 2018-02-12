<?php
require_once ('models/Database.php');
require_once ('models/User.php');
session_start();
$_dbHandle = Database::getInstance()->getdbConnection();
$user = new User($_dbHandle);
//if user is logged in redirect them to index
if($user->is_loggedin()!="")
{
    $user->redirect('index.php');
}

//trimming the submitted data into post variables
if(isset($_POST['btn-signup']) && $_POST['code'] == $_SESSION['random_code']) {

        $username = (trim($_POST['username'], ENT_NOQUOTES));
        $email = (trim($_POST['email'], ENT_NOQUOTES));
        $pass = (trim($_POST['pass'], ENT_NOQUOTES));
        $address = (trim($_POST['address'], ENT_NOQUOTES));
        $phone = (trim($_POST['phone'], ENT_NOQUOTES));

//checking that the user has entered a value and/or a valid email address
        if ($username == "") {
            $errors[] = "Please provide a username";
        }
        if ($email == "") {
            $errors[] = "Please provide an email";
        }
        if ($pass == "") {
            $errors[] = "Please provide a password";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email";
        } else if (strlen($pass) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        } else if ($address == "") {
            $errors[] = "Please provide a postcode";
        } else if ($phone == "") {
            $errors[] = "Please provide a phone number";
        } else if ($_POST['code'] != $_SESSION['random_code']) {
            $errors[] = "Wrong Captcha code";
        } //check if details entered have been taken by another user
        elseif ($user->checkDuplicateUser($username, $email)) {

            //call register function if it passes then redirect them to homepage
            if ($user->register($username, $email, $pass, $address, $phone)) {
                $user->redirect('index.php');
            }
        }
    }


$view = new stdClass();
$view->pageTitle = 'Register';
require_once('Views/register.phtml');

