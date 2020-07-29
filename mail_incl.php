<?php

use PHPMailer\PHPMailer\PHPMailer;

require "./PHPMailer/src/PHPMailer.php";
require "./PHPMailer/src/SMTP.php";

$mail = new PHPMailer();
$mail->ISSMTP();

$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Host = "smtp.gmail.com";
$mail->Port = 465;
$mail->Username = "shop.offis@gmail.com";
$mail->Password = "caloriferul";
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
?>