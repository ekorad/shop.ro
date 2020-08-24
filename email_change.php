<?php

require('./includes/connect.php');
require('./includes/session.php');

// DELETE THIS ---------------------------------------------------------------
require('./mail_incl.php');
// DELETE THIS ---------------------------------------------------------------

if (!(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true)) {
    header("Location: index.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['token']) && !empty($_GET['token'])) {
        $sql = "SELECT `new_email`, `token` FROM `users_email_change` WHERE `id` = ?"
                . " AND `token` = ?";
        $sel_stmt = $conn->prepare($sql);
        $sel_stmt->bind_param("is", $_SESSION['uid'], $_GET['token']);
        $sel_stmt->execute();
        $stored_new_email = NULL;
        $stored_token = NULL;
        $sel_stmt->store_result();
        if ($sel_stmt->num_rows === 0) {
            echo "NO USER FOUND WITH THE SPECIFIED ID OR TOKEN";
            header("Location: index.php");
        }
        $sel_stmt->bind_result($stored_new_email, $stored_token);
        $sel_stmt->fetch();
        $sel_stmt->close();

        $sql = "UPDATE `users` SET `email` = ? WHERE `id` = ?";
        $upd_stmt = $conn->prepare($sql);
        $upd_stmt->bind_param("si", $stored_new_email, $_SESSION['uid']);
        $upd_stmt->execute();
        $upd_stmt->close();

        $sql = "DELETE FROM `users_email_change` WHERE `id` = ?";
        $del_stmt2 = $conn->prepare($sql);
        $del_stmt2->bind_param("i", $_SESSION['uid']);
        $del_stmt2->execute();
        $del_stmt2->close();
    } else {
        header("Location: index.php");
        exit();
    }
    header("Location: logout.php");
    exit();
} else if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

if (!isset($_POST['newEmail']) || empty($_POST['newEmail'])) {
    header("Location: index.php");
    exit();
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

$sql = "SELECT * FROM `users_email_change` WHERE `id` = ?";
$check_exist_stmt = $conn->prepare($sql);
$check_exist_stmt->bind_param("i", $_SESSION['uid']);
$check_exist_stmt->execute();
$check_exist_stmt->store_result();
if ($check_exist_stmt->num_rows !== 0) {
    $sql = "DELETE FROM `users_email_change` WHERE `id` = ?";
    $del_stmt = $conn->prepare($sql);
    $del_stmt->bind_param("i", $_SESSION['uid']);
    $del_stmt->execute();
    $del_stmt->close();
}

$check_exist_stmt->close();

$token = hash("sha1", $email . time());
$sql = "INSERT INTO `users_email_change` VALUES (?, ?, ?)";
$ins_stmt = $conn->prepare($sql);
$ins_stmt->bind_param("iss", $_SESSION['uid'], $_POST['newEmail'], $token);

if (!$ins_stmt->execute()) {
    echo $ins_stmt->errno;
    $ins_stmt->close();
    exit;
}


$msg = "Pentru a iți putea schimba adresa de email, te rugăm să accesezi link-ul următor:"
        . " 89.165.137.39/shop/email_change.php?token=$token. <br />"
        . "Imediat după accesarea link-ului, vei fi deconectat și va trebui să te conectezi din nou folosind noua adresa de email.";
$subject = "Activare cont";

// CHANGE THIS ---------------------------------------------------
$mail->AddAddress($_POST['newEmail']);
$mail->SetFrom('shop.offis@gmail.com', 'Shop.ro Office');
$mail->Subject = 'Schimbare adresa email';
$mail->Body = $msg;
// CHANGE THIS ---------------------------------------------------

if ($mail->Send()) {
    $_SESSION['email_change_link_send_success'] = true;
}

header("Location: account.php");
?>