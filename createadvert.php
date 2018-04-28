<?php
session_start();
require_once ('models/Database.php');
require_once ('models/Advert.php');
$_dbHandle = Database::getInstance()->getdbConnection();
$advert = new Advert($_dbHandle);
//when button is pressed input is stripped and removes specialchars then is validated in multiple ways for empty inputs
if(isset($_POST['btn-create']))
{
    $name = (trim($_POST['name'], ENT_NOQUOTES));
    $price = (trim($_POST['price'],ENT_NOQUOTES));
    $description = (trim($_POST['description'],ENT_NOQUOTES));
    $type = (trim($_POST['type'],ENT_NOQUOTES));
    if (count($_POST) && (strpos($_POST['img'], 'data:image') === 0)) {
        $img = $_POST['img'];
        if (strpos($img, 'data:image/jpeg;base64,') === 0) {
            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $ext = '.jpg';
        }
        if (strpos($img, 'data:image/png;base64,') === 0) {
            $img = str_replace('data:image/png;base64,', '', $img);
            $ext = '.png';
        }
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $file = 'img/'.date("YmdHis").$ext;
        if (file_put_contents($file, $data)) {
            echo "<p>The image was saved as $file.</p>";
        } else {
            echo "<p>The image could not be saved.</p>";
        }
    }



    $picture = $_FILES['file']['name'];
    $tempName = $_FILES['file']['tmp_name'];
    if(isset($picture)){
        if(!empty($picture))
        {
            $dir = "img/";
            move_uploaded_file($tempName, $dir. $picture);
        }
    }
    $userID = $_SESSION['UserID'];
    $date = date('Y-m-d');
    $expiry = date('Y-m-d', strtotime("+14 days"));
    if ($name == "") {
        echo "<script>alert('Please enter a name')</script>";
    }
    elseif ($price == "") {
        echo "<script>alert('Please enter a price')</script>";
    }
    elseif ($description == "") {
        echo "<script>alert('Please enter a description')</script>";
    }
    elseif ($picture == "") {
        echo "<script>alert('Please enter a picture')</script>";
    }
//tries to create an advert then redirect to your adverts
    try {
        if ($advert->createAdvert($name, $price, $description, $type, $userID, $picture, $date, $expiry)) {
            $advert->redirect('youradverts.php');
        }
    } catch (PDOException $exception) {
        echo $exception->getMessage();
    }
}
$view = new stdClass();
$view->pageTitle = 'Create Advert';
require_once('Views/createadvert.phtml');
