<?php
//starts a session so that session values can be set and accessed
session_start();
//declares an empty string
$string = '';
//loops to 5 so 5 characters are generated for captcha
for ($i = 0; $i < 5; $i++)
{
    //numbers of the ascii table (lower case)
    $string .= chr(rand(97, 122));
}
//sets a session variable to store the random code so it can be validated when input by the user
$_SESSION['random_code'] = $string;
//declares which font file to use
$font = "arial.ttf";
//creates an image of given parameters
$image = imagecreatetruecolor(170, 60);
$black = imagecolorallocate($image, 0, 0, 0);
$color = imagecolorallocate($image, 200, 100, 90); // red
$white = imagecolorallocate($image, 255, 255, 255);
//inputs the string as a picture so that it can not be say, copy pasted by a robot to bypass captcha
imagefilledrectangle($image,0,0,399,99,$white);
imagettftext ($image, 30, 0, 10, 40, $color, $font, $_SESSION['random_code']);
//displays image
header("Content-type: image/png");
imagepng($image);
