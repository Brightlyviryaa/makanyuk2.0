<?php
session_start();

function createCaptchaImage($text)
{
    $width = 200;
    $height = 100;
    $fontfile = "OpenSans-Regular.ttf";

    $image = imagecreatetruecolor($width, $height);

    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);

    imagefill($image, 0, 0, $white);
    imagettftext($image, 25, rand(-20, 20), $width / 4, 60, $black, $fontfile, $text);

    header("Content-type: image/jpeg");
    imagejpeg($image);
    imagedestroy($image);
}

$captcha = isset($_SESSION['captcha']) ? $_SESSION['captcha'] : '';
createCaptchaImage($captcha);
?>