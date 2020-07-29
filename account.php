<?php
require './includes/session.php';

if (!(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true)) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Contul meu</title>
        <link rel="stylesheet" type="text/css" href="css/generic.css" />
        <link rel="stylesheet" type="text/css" href="css/account.css" />
    </head>
    <body>
        <div id="main-body">
            <h1>Contul meu</h1>
            <div id="acc-info-container">
                <?php
                if ($_SESSION['activated'] === false) {
                    ?>
                    <span id="act-status">Atenție! Contul tău nu este activat. Pentru a iți putea continua cumpărăturile, te rugăm să iți verifici email-ul pentru activarea contul.</span>
                    <br />
                    <span>Nu ai primit emailul? Click <a href="resend_act.php">aici</a> pentru a retrimite link-ul de activare.</span>
                    <?php
                    if (isset($_SESSION['resend_success']) && $_SESSION['resend_success'] === true) {
                        ?>
                        <span>Link-ul pentru activare a fost trimis!</span>
                        <?php
                        unset($_SESSION['resend_success']);
                    }
                }
                ?>
            </div>
        </div>
    </body>
</html>