<?php
require('./includes/connect.php');
require('./includes/session.php');

// DELETE THIS ---------------------------------------------------------------
require('./mail_incl.php');
// DELETE THIS ---------------------------------------------------------------

if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    header("Location: index.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['forgotSub']) && isset($_POST['email'])
            && !empty($_POST['email'])) {
        $email = $_POST['email'];
        $sql = "SELECT `id` FROM `users` WHERE `email` = ?";
        $sel_stmt = $conn->prepare($sql);
        $sel_stmt->bind_param("s", $email);
        $sel_stmt->execute();
        $sel_stmt->store_result();
        if ($sel_stmt->num_rows === 0) {            
            $sel_stmt->close();
            header("Location: index.php");
            exit();
        }
        $id = NULL;
        $sel_stmt->bind_result($id);
        $sel_stmt->fetch();
        $sel_stmt->close();        

        $sql = "SELECT * FROM `users_pass_change` WHERE `id` = ?";
        $check_exist_stmt = $conn->prepare($sql);
        $check_exist_stmt->bind_param("i", $id);
        $check_exist_stmt->execute();
        $check_exist_stmt->store_result();

        $token = hash("sha1", $email . time());
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
            $mail->AddAddress($email);
            $mail->SetFrom('shop.offis@gmail.com', 'Shop.ro Office');
            $mail->Subject = 'Schimbare parola';
            $mail->Body = $msg;
// CHANGE THIS ---------------------------------------------------
            $mail->Send();
        }
    }
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
            <form id="emailPassForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                  method="post">
                <label for="email">Adresă email:</label>
                <input type="text" name="email">
                <input type="submit" value="Trimite" name="forgotSub" />
            </form>
        </div>
    </body>
</html>