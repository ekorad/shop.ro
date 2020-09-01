<?php
require('./includes/connect.php');
require('./includes/session.php');

if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    header("Location: index.php");
    exit;
}

$status_code = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loginSub'])) {
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $status_code = 1;
        }
    }

    if (!$status_code) {
        $password = $_POST['password'];

        $sql = "SELECT `id`, `name`, `password`, `activated` FROM `users` WHERE `email` = ?";
        $stmt = $conn->prepare($sql);
        $db_hash = NULL;
        $db_id = NULL;
        $db_act = 0;
        $db_name = NULL;
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($db_id, $db_name, $db_hash, $db_act);
            $stmt->fetch();

            $salt = substr($db_hash, 0, 4) . substr($db_hash, 68, 4);
            $hash = hash("sha256", $password . $salt);
            
            $db_pass_hash = substr($db_hash, 4, 64);
            if ($hash !== $db_pass_hash) {
                $status_code = 3;
            } else {
                $_SESSION['loggedIn'] = true;
                $_SESSION['activated'] = ($db_act ? true : false);
                $db_name = substr($db_name, 0, strpos($db_name, " "));
                $_SESSION['name'] = $db_name;
                $_SESSION['uid'] = $db_id;
                
                $stmt->close();
                header("Location: index.php");
            }
        } else {
            $status_code = 2;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Conectare</title>
        <link rel="stylesheet" type="text/css" href="css/generic.css" />
        <link rel="stylesheet" type="text/css" href="css/login.css" />
        <script type="text/javascript" src="scripts/login.js"></script>
    </head>
    <body>
        <form id = "loginForm" method = "post"
              onsubmit = "return onLoginSubmit()"
              action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <h1>Shop</h1>
            <span id="status">
                <?php
                    if ($status_code === 1) {
                        echo "A apărut o eroare! Te rugăm să încerci din nou.";
                    } else if ($status_code === 2) {
                        echo "Contul cu emailul specificat nu a fost găsit!";
                    } else if ($status_code === 3) {
                        echo "Combinația email-parolă este incorectă! Te rugăm să încerci din nou.";
                    }
                ?></span>
            <div class = "inputWrapper" style="margin-top: 15px">
                <input type = "text" name = "email" id = "emailField"
                       placeholder = "Email" onclick = "onInputClick(this)" />
                <span class = "tooltip">Email-ul introdus este invalid!</span>
            </div>
            <div class = "inputWrapper">
                <input type = "password" id = "passwordField" name="password"
                       placeholder = "Parolă" onclick = "onInputClick(this)" />
            </div>
            <button type = "submit" name = "loginSub">Conectare</button>
            <a href = "register.php" style = "float: left">Înregistrare cont nou</a>
            <a href = "forgot_pass.php" style = "float: right">Mi-am uitat parola</a>
        </form>
    </body>
</html>