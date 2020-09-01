<?php
require('./includes/connect.php');
require('./includes/session.php');

// DELETE THIS ---------------------------------------------------------------
require('./mail_incl.php');
// DELETE THIS ---------------------------------------------------------------

$status_code = 0;
$stored_id = NULL;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true
            && !isset($_POST['changeSub'])) {
        $id = $_SESSION['uid'];
        $sql = "SELECT `email` FROM `users` WHERE `id` = ?";
        $get_email_stmt = $conn->prepare($sql);
        $get_email_stmt->bind_param("i", $id);
        $get_email_stmt->execute();
        $get_email_stmt->store_result();
        if ($get_email_stmt->num_rows === 0) {
            echo "ERROR: NO USER FOUND WITH THE SPECIFIED EMAIL.";
            $get_email_stmt->close();
            header("Location: index.php");
            exit();
        }
        $stored_email = NULL;
        $get_email_stmt->bind_result($stored_email);
        $get_email_stmt->fetch();
        $get_email_stmt->close();

        $sql = "SELECT * FROM `users_pass_change` WHERE `id` = ?";
        $check_exist_stmt = $conn->prepare($sql);
        $check_exist_stmt->bind_param("i", $id);
        $check_exist_stmt->execute();
        $check_exist_stmt->store_result();

        $token = hash("sha1", $stored_email . time());
        $ins_upd_stmt = NULL;

        if ($check_exist_stmt->num_rows !== 0) {
            $sql = "UPDATE `users_pass_change` SET `token` = ? WHERE `id` = ?";
            $ins_upd_stmt = $conn->prepare($sql);
            $ins_upd_stmt->bind_param("si", $token, $id);
        } else {
            $sql = "INSERT INTO `users_pass_change` VALUES (?, ?)";
            $ins_upd_stmt = $conn->prepare($sql);
            $ins_upd_stmt->bind_param("is", $id, $token);
        }

        $check_exist_stmt->close();
        if ($ins_upd_stmt->execute()) {
            $msg = "Pentru a iți schimba parola, te rugăm să accesezi link-ul următor:"
                    . " 89.165.137.39/shop/pass_change.php?token=$token.";

// CHANGE THIS ---------------------------------------------------
            $mail->AddAddress($stored_email);
            $mail->SetFrom('shop.offis@gmail.com', 'Shop.ro Office');
            $mail->Subject = 'Schimbare parola';
            $mail->Body = $msg;
// CHANGE THIS ---------------------------------------------------
            $mail->Send();
        } else {
            $ins_upd_stmt->close();
            header("Location: index.php");
            exit();
        }
        $ins_upd_stmt->close();
        $status_code = -1;
    } else if (isset($_POST['newPass']) && !empty($_POST['newPass'])) {  
        var_dump($_POST);
        $pass = $_POST['newPass'];
        $salt = bin2hex(random_bytes(4));
        $pass_hash = hash("sha256", $pass . $salt);
        $newPassHash = substr($salt, 0, 4) . $pass_hash
                . substr($salt, 4, 4);
        $sql = "UPDATE `users` SET `password` = ? WHERE `id` = ?";
        $upd_pass_stmt = $conn->prepare($sql);
        $upd_pass_stmt->bind_param("si", $newPassHash, $_POST['uid']);
        $upd_pass_stmt->execute();
        $upd_pass_stmt->close();
        
        $sql = "DELETE FROM `users_pass_change` WHERE `id` = ?";
        $del_stmt3 = $conn->prepare($sql);
        $del_stmt3->bind_param("i", $_POST['uid']);
        $del_stmt3->execute();
        header("Location: logout.php");
        exit();
    } else {
        header("Location: index.php");
        exit();
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['token']) && !empty($_GET['token'])) {
        $token = $_GET['token'];
        $sql = "SELECT `id` FROM `users_pass_change` WHERE `token` = ?";
        $token_sel_stmt = $conn->prepare($sql);
        $token_sel_stmt->bind_param("s", $token);
        $token_sel_stmt->execute();
        $token_sel_stmt->store_result();
        if ($token_sel_stmt->num_rows === 0) {
            $status_code = 1;
        }
        $token_sel_stmt->bind_result($stored_id);
        $token_sel_stmt->fetch();
        $token_sel_stmt->close();
    } else {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Schimbare parolă</title>
        <link rel="stylesheet" type="text/css" href="css/generic.css" />
        <link rel="stylesheet" type="text/css" href="css/confirm.css" />
    </head>
    <body>
        <div id="status-container">
            <h1>Shop</h1>
            <?php if ($status_code === -1) { ?>
                <span id="status">În scurt timp vei primi un email cu link-ul pentru schimbarea parolei.</span>
            <?php } else if ($status_code === 1) { ?>
                <span id="status">A apărut o eroare! Te rugăm să încerci din nou.</span>
            <?php } else { ?>
                <span id="status">După schimbarea parolei, vei fi deconectat automat și va fi necesar să te conectezi din nou folosind noua parolă.</span>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                      method="post">
                    <label for="newPass">Noua parolă:</label>
                    <input type="password" name="newPass" />
                    <input type="text" name="uid" value="<?php echo $stored_id; ?>"
                           style="display: none" />
                    <br />
                    <input type="submit" value="Trimite" name="changeSub" />
                </form>
            <?php } ?>
        </span>
    </div>
</body>
</html>