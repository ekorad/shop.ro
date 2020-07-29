<?php
require('./includes/connect.php');
require('./includes/session.php');

$status_code = 0;

if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];

    if (!preg_match("/^[a-zA-Z0-9]+$/", $token)) {
        $status_code = 1;
    }

    if ($status_code === 0) {
        $id = NULL;
        $activated = 0;

        $sql = "SELECT `id`, `activated` FROM `users` WHERE `act_token` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);

        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $activated);
            $stmt->fetch();
            $stmt->close();
            
            if ($activated) {
                header("Location: index.php");
            }

            $sql_upd = "UPDATE `users` SET `activated` = true"
                    . " WHERE `id` = ?";
            $stmt_upd = $conn->prepare($sql_upd);
            $stmt_upd->bind_param("i", $id);
            if (!$stmt_upd->execute()) {
                $status_code = 1;
                $stmt_upd->close();
            } else {
                $status_code = -1;
                $stmt_upd->close();
            }
            
            if ($status_code === -1 && isset($_SESSION['loggedIn'])
                    && $_SESSION['loggedIn'] === true) {
                $_SESSION['activated'] = true;
                $status_code = -2;
            }
        } else {
            $status_code = 1;
            $stmt->close();
        }
    }
} else {
    header("Location: index.php");
}
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Activare cont</title>
        <link rel="stylesheet" type="text/css" href="css/generic.css" />
        <link rel="stylesheet" type="text/css" href="css/confirm.css" />
        <script>
            function onPageLoad() {
                var ok = <?php echo $status_code; ?>;
                if (ok < 0) {
                    setTimeout(function () {
                        window.location.href = "login.php";
                    }, 5000);
                }
            }
        </script>
    </head>
    <body onload="onPageLoad();">
        <div id="status-container">
            <h1>Shop</h1>
            <span id="status">
                <?php
                if ($status_code === 1) {
                    echo "A apărut o eroare! Te rugăm să încerci din nou.";
                } else if ($status_code === -1) {
                    echo "Contul a fost activat cu succes! În scurt timp vei fi redirecționat către pagina de logare. Click <a href='login.php'>aici</a> dacă nu vrei să aștepți.";
                } else if ($status_code === -2) {
                    echo "Contul a fost activat cu succes! În scurt timp vei fi redirecționat către pagina principală. Click <a href='index.php'>aici</a> dacă nu vrei să aștepți.";
                }
                ?>
            </span>
        </div>
    </body>
</html>