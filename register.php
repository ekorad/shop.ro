<?php
require('./includes/connect.php');
require('./includes/session.php');

if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    header("Location: index.php");
    exit;
}

// DELETE THIS ---------------------------------------------------------------
require('./mail_incl.php');
// DELETE THIS ---------------------------------------------------------------

$ud_error = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['regSub'])) {
    if (empty($_POST['email']) || empty($_POST['name']) || empty($_POST['gender']) || empty($_POST['password'])) {
        $ud_error = 1;
    }

    if (!$ud_error) {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $ud_error = 1;
        }
    }

    if (!$ud_error) {
        $sql = "SELECT `id` FROM `users` WHERE `email` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows !== 0) {
            $ud_error = 2;
        }

        $stmt->close();
    }

    if (!$ud_error) {
        $name = $_POST['name'];
        $gender = $_POST['gender'];
        $password = $_POST['password'];

        $salt = bin2hex(random_bytes(4));
        $pass_hash = hash("sha256", $password . $salt);
        $stored_pass = substr($salt, 0, 4) . $pass_hash
                . substr($salt, 4, 4);

        $sql = "INSERT INTO `users`(`name`, `gender`, `email`, `password`, `act_token`)"
                . "VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $token = hash("sha1", $email . time());
        $stmt->bind_param("sssss", $name, $gender, $email, $stored_pass, $token);

        if (!$stmt->execute()) {
            $ud_error = 1;
        }

        if (!$ud_error) {
            $msg = "Pentru a iți putea accesa contul, te rugăm să accesezi link-ul următor pentru activare:"
                    . " 89.165.137.39/shop/confirm.php?token=$token.";
            $subject = "Activare cont";

            // CHANGE THIS ---------------------------------------------------
            $mail->AddAddress($email);
            $mail->SetFrom('vladzahiu28@gmail.com', 'Shop.ro Office');
            $mail->Subject = 'Activare cont';
            $mail->Body = $msg;
            // CHANGE THIS ---------------------------------------------------

            if (!$mail->Send()) {
                $ud_error = 1;
            } else {
                $ud_error = -1;
            }
        }
    }
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Înregistrare</title>
        <script type="text/javascript" src="scripts/register.js"></script>
        <link rel="stylesheet" type="text/css" href="css/generic.css" />
        <link rel="stylesheet" type="text/css" href="css/register.css" />
    </head>
    <body>
        <form id = "registerForm" method = "post"
              onsubmit = "return onRegisterSubmit()"
              action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <h1>Shop</h1>
            <span id="status">
                <?php
                if ($ud_error === 1) {
                    echo "A apărut o eroare! Te rugăm să încerci din nou.";
                } else if ($ud_error === 2) {
                    echo "Un utilizator cu acest email există deja!";
                } else if ($ud_error === -1) {
                    echo "Contul a fost înregistrat cu succes! Te rugăm să iți verifici email-ul pentru activarea contului.";
                }
                ?></span>
            <div class = "inputWrapper" style="margin-top: 15px;">
                <input type = "text" name = "name" id = "nameField"
                       placeholder = "Nume" onclick = "onInputFocus(this)" />
                <span class = "tooltip">Numele nu poate conține decât litere!</span>
            </div>
            <div class = "inputWrapper">
                <div id = "gendersContainer">
                    <div class = "genderContainer">
                        <input type = "radio" name = "gender" id = "radioMale"
                               onchange = "onRadioChecked()" value="male" />
                        <label for = "radioMale">Dl.</label>
                    </div>
                    <div class = "genderContainer">
                        <input type = "radio" name = "gender" id = "radioFemale"
                               onchange = "onRadioChecked()" value="female"/>
                        <label for = "radioFemale">Dna. / Dra.</label>
                    </div>
                </div>
                <span class = "tooltip">Vă rugam să selectați o opțiune.</span>
            </div>
            <div class = "inputWrapper">
                <input type = "text" name = "email" id = "emailField"
                       placeholder = "Email" onclick = "onInputFocus(this)" />
                <span class = "tooltip">Email-ul introdus este invalid!</span>
            </div>
            <div class = "inputWrapper">
                <input type = "password" id = "passwordField" name="password"
                       placeholder = "Parolă" onclick = "onInputFocus(this)" />
                <span class = "tooltip"></span>
            </div>
            <button type = "submit" name = "regSub">Înregistrare</button>
            <a href = "login.php" style = "float: left">Conectare</a>
            <a href = "forgotten.php" style = "float: right">Mi-am uitat parola</a>
        </form>
    </body>
</html>