<?php

require('./includes/connect.php');
require('./includes/session.php');

// DELETE THIS ---------------------------------------------------------------
require('./mail_incl.php');
// DELETE THIS ---------------------------------------------------------------

if (!(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true)) {
    header("Location: index.php");
}

$sql = "SELECT `email` FROM `users` WHERE `id` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['uid']);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo "USER WITH ID " . $_SESSION['uid'] . " NOT FOUND";
    $stmt->close();
    exit;
}

$email = NULL;
$stmt->bind_result($email);
$stmt->fetch();
$stmt->close();

$token = hash("sha1", $email . time());
$sql = "UPDATE `users` SET `act_token` = ? WHERE `id` = ?";
$upd_stmt = $conn->prepare($sql);
$upd_stmt->bind_param("si", $token, $_SESSION['uid']);

if (!$upd_stmt->execute()) {
    echo "ERROR";
    $upd_stmt->close();
    exit;
}


$msg = "Pentru a iți putea accesa contul, te rugăm să accesezi link-ul următor pentru activare:"
        . " 89.165.137.39/shop/confirm.php?token=$token.";
$subject = "Activare cont";

// CHANGE THIS ---------------------------------------------------
$mail->AddAddress($email);
$mail->SetFrom('vladzahiu28@gmail.com', 'Shop.ro Office');
$mail->Subject = 'Activare cont';
$mail->Body = $msg;
// CHANGE THIS ---------------------------------------------------

if ($mail->Send()) {
    $_SESSION['resend_success'] = true;
}

header("Location: account.php");
?>