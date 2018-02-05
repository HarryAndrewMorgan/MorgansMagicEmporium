<?php
require_once ('models/Database.php');
require_once ('models/User.php');
$_dbHandle = Database::getInstance()->getdbConnection();
$user = new User($_dbHandle);
//if user is logged in redirect them to index
if($user->is_loggedin()!="")
{
    $user->redirect('index.php');
}

//trimming the submitted data into post variables
if(isset($_POST['btn-signup'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $pass = trim($_POST['pass']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);

//checking that the user has entered a value and/or a valid email address
    if ($username == "") {
        $error[] = "Please provide a username";
    }
    if ($email == "") {
        $error[] = "Please provide an email";
    }
    if ($pass == "") {
        $error[] = "Please provide a password";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = "Please enter a valid email";
    } else if (strlen($pass) < 8) {
        $error[] = "Password must be at least 8 characters long";
    } else if($address == "") {
        $error[] = "Please provide a postcode";
    } else if($phone == "") {
        $error[] = "Please provide a phone number";
    }
    //check if details entered have been taken by another user
    else {
        try {
            //prepare statement to find matching username and emails
            $sqlQuery = $_dbHandle->prepare("SELECT Username, Email FROM Users WHERE Username=:username OR Email=:email");
            //execute statement and enter values into an array for easy access
            $sqlQuery->execute(array(':username' => $username, ':email' => $email));
            //fetches the associated rows
            $row = $sqlQuery->fetch(PDO::FETCH_ASSOC);
            //the checks
            if ($row['Username'] == $username) {
                $error[] = "That username has already been taken";
            } else if ($row['Email'] == $email) {
                $error[] = "Sorry that email has already been taken";
            } else {
                //call register function if it passes then redirect them to homepage
                if ($user->register($username, $email, $pass, $address, $phone)) {
                    $user->redirect('index.php');
                }


            }
        } catch (PDOException $exception) {
            echo $exception->getMessage();
        }
    }
}

$view = new stdClass();
$view->pageTitle = 'Register';
require_once('Views/register.phtml');

