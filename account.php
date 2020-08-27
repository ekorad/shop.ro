<?php
require('includes/connect.php');
require('includes/session.php');
require('./mail_incl.php');

if (!(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true)) {
    header("Location: index.php");
} else {

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        if (isset($_POST['name']) && !empty($_POST['name'])) {
            $sql = "UPDATE `users` SET `name` = ? WHERE `id` = ?";
            $name_upd_statement = $conn->prepare($sql);
            $name_upd_statement->bind_param("si", $_POST['name'], $_SESSION['uid']);
            $name_upd_statement->execute();
        }
    }

    $sql = "SELECT `name`, `email`, `activated` FROM `users` WHERE `id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['uid']);
    $stmt->execute();
    $stored_name = NULL;
    $stored_email = NULL;
    $stored_act_status = NULL;
    $stmt->bind_result($stored_name, $stored_email, $stored_act_status);
    $stmt->fetch();
    $stmt->close();

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        if (isset($_POST['addressSub']) && !empty($_POST['addressSub']) && isset($_POST['newAddress']) && !empty($_POST['newAddress'])) {
            $addr = $_POST['newAddress'];
            $sql = "INSERT INTO `user_delivery_addresses`(`uid`, `address`) VALUES (?, ?)";
            $add_addr_stmt = $conn->prepare($sql);
            $add_addr_stmt->bind_param("is", $_SESSION['uid'], $addr);
            $add_addr_stmt->execute();
            $add_addr_stmt->close();
        } else if (isset($_POST['addressRemove']) && !empty($_POST['addressRemove'])) {
            $addr_rem_id = $_POST['addressRemove'];
            $sql = "DELETE FROM `user_delivery_addresses` WHERE `id` = ?";
            $del_stmt = $conn->prepare($sql);
            $del_stmt->bind_param("i", $addr_rem_id);
            $del_stmt->execute();
            $del_stmt->close();
        }
    }
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Contul meu</title>
        <link rel="stylesheet" type="text/css" href="css/generic.css" />
        <link rel="stylesheet" type="text/css" href="css/index.css" />
        <link rel="stylesheet" type="text/css" href="css/account.css" />
        <script type="text/javascript" src="scripts/search.js"></script>
        <script type="text/javascript" src="scripts/register.js"></script>
        <script type="text/javascript" src="scripts/account.js"></script>
        <script>
            setInterval(function () {
                if (window.innerWidth > 1300) {
                    var menu = document.getElementById("nav");
                    if (menu.classList.contains("expanded")) {
                        mobileMenuButtonAction();
                    }
                }
            }, 16);
        </script>
    </head>
    <body>
        <div id="overlay" onclick="mobileMenuButtonAction()"></div>
        <div id="header">
            <form action="index.php" method="post" name="searchForm"
                  class="middle-content" id="searchForm">
                <a class="middle-v-aligned" id="mobile-menu-button" 
                   href="javascript:void(0)" onclick="mobileMenuButtonAction()">
                    <span class="icon-menu"></span>
                </a>
                <a href="./index.php" id="header-home-link"
                   class="middle-v-aligned">Shop.ro</a>
                <div class="right-container middle-v-aligned">
                    <?php
                    if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
                        ?>
                        <a href="#">
                            <span class="icon icon-user"></span>
                            <span class="mobile-hidden">Contul meu</span>
                        </a>
                        <a href="./../cart.php">
                            <span style="position:relative" class="icon icon-cart">
                                <span style="font-size: 0.5em;" id="basketCount">
                                    <?php
                                    if (!empty($_COOKIE) && isset($_COOKIE['shoppingCart'])) {
                                        echo count(explode(" ", $_COOKIE['shoppingCart']));
                                    } else {
                                        echo "0";
                                    }
                                    ?>
                                </span>                                    
                            </span>
                            <span class="mobile-hidden">Coșul meu</span>
                        </a>
                        <?php
                    } else {
                        ?>
                        <a href="./../login.php">
                            <span class="icon icon-login"></span>
                            <span class="mobile-hidden">Conectare</span>
                        </a>
                        <a href="./../register.php">
                            <span class="icon icon-user"></span>
                            <span class="mobile-hidden">Înregistrare cont</span>
                        </a>
                        <?php
                    }
                    ?>
                </div>
                <div class="middle-container middle-v-aligned">
                    <button type="submit" name="searchSub">
                        <span class="icon-search"></span>
                    </button>
                    <input type="text" name="searchVal"
                           placeholder="Descrie produsul căutat..." />                    
                </div>                
            </form>
        </div>
        <div id="nav">
            <ul class="middle-content">
                <li>
                    <a class="btn-expand" href="javascript:void(0)" onclick="navExpandAction(this)">Laptopuri</a>
                    <div class="dropwdown-content">
                        <a href="./produse/notebook.php">Notebook-uri</a>
                        <a href="#">Ultrabook-uri</a>
                    </div>
                </li>
                <li>
                    <a class="btn-expand"  href="javascript:void(0)" onclick="navExpandAction(this)">Mobile</a>
                    <div class="dropwdown-content">
                        <a href="#">Telefoane</a>
                        <a href="#">Tablete</a>
                    </div>
                </li>
                <li>
                    <a class="btn-expand"  href="javascript:void(0)" onclick="navExpandAction(this)">Componente</a>
                    <div class="dropwdown-content">
                        <a href="#">Plăci video</a>
                        <a href="#">Plăci de bază</a>
                        <a href="#">Procesoare</a>
                        <a href="#">Memorii</a>
                        <a href="#">SSD</a>
                        <a href="#">HDD</a>
                        <a href="#">Surse</a>
                        <a href="#">Coolere</a>
                    </div>
                </li>
                <li>
                    <a class="btn-expand"  href="javascript:void(0)" onclick="navExpandAction(this)">Televizoare</a>
                    <div class="dropwdown-content">
                        <a href="#">Televizoare LED</a>
                    </div>
                </li>
                <li>
                    <a class="btn-expand"  href="javascript:void(0)" onclick="navExpandAction(this)">Imprimare</a>
                    <div class="dropwdown-content">
                        <a href="#">Imprimante</a>
                        <a href="#">Cartușe</a>
                    </div>
                </li>
                <li>
                    <a class="btn-expand"  href="javascript:void(0)" onclick="navExpandAction(this)">Audio/Video</a>
                    <div class="dropwdown-content">
                        <a href="#">Căști</a>
                        <a href="#">Boxe</a>
                        <a href="#">Camere video</a>
                    </div>
                </li>
                <li>
                    <a class="btn-expand"  href="javascript:void(0)" onclick="navExpandAction(this)">Rețelistică</a>
                    <div class="dropwdown-content">
                        <a href="#">Routere Wireless</a>
                        <a href="#">Switch-uri</a>
                        <a href="#">Plăci rețea</a>
                    </div>
                </li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>
        <div id="main-content">
            <div class="article">
                <div class="article-content middle-content">
                    <h1>Detalii generale cont:</h1>
                    <?php
                    if ($stored_act_status === 0) {
                        ?>
                        <p id = "inactive-account-info">Contul tău nu este activat! Vei putea vizualiza și alege produse, dar nu vei putea efectua plata. Te rugăm să iți verifici email-ul pentru link-ul de activare a contului. Nu ai primit nici un link? Apasă <a href="resend_act.php">aici</a> pentru a retrimite.</p>
                        <?php
                        if (isset($_SESSION['resend_success']) && $_SESSION['resend_success'] === true) {
                            unset($_SESSION['resend_success']);
                            ?>
                            <p id="account-act-success-info">Link-ul pentru activare a fost trimis. Te rugăm să iți verifici emailul.</p>
                            <?php
                        }
                    }
                    if (isset($_SESSION['email_change_link_send_success']) && $_SESSION['email_change_link_send_success'] === true) {
                        unset($_SESSION['email_change_link_send_success']);
                        ?>
                        <p id="email_change_success_info">Link-ul pentru schimbarea adresei de email a fost trimis. Te rugăm să iți verifici mesajele primite pe noua adresă de email.</p>
                        <?php
                    }
                    ?>
                    <form id="nameForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                          method="post" onsubmit="return nameFormSubmitAction()" name="nameForm">
                        <div class="account-detail-row">
                            <label for="name">Nume:</label>
                            <input onfocus="onAccountInputFocus(this)" onblur="onNameFieldBlur()" id="nameField" type="text" name="name" value="<?php echo $stored_name; ?>" disabled />
                            <button type="button" onclick="nameEditButtonAction()" class="edit-button"><span class="icon-edit"></span></button>
                            <span class="status-span"></span>
                        </div>
                    </form>
                    <form id="emailForm" action="email_change.php"
                          method="post" onsubmit="return emailFormSubmitAction()" name="emailForm">
                        <div class="account-detail-row">
                            <label for="email">Email:</label>
                            <input id="emailField" type="text" name="email" value="<?php echo $stored_email; ?>" disabled />
                            <button type="button" onclick="emailEditButtonAction()" class="edit-button"><span class="icon-edit"></span></button>
                            <span class="status-span"></span>
                        </div>
                        <div class="expandable">
                            <label for="newEmail">Noua adresa:</label>
                            <input onfocus="onAccountInputFocus(this)" id="newEmailField" type="text" name="newEmail" />
                            <br />
                            <input type="submit" value="Trimite" />
                        </div>
                    </form>                              
                </div>
            </div>
            <div class="article">
                <div class="article-content middle-content">
                    <h1>Securitate:</h1>
                    <form id="passForm" name="passForm" action="pass_change.php"
                          method="post">
                        <input type="submit" value="Schimbare parola" />
                    </form>
                </div>
            </div>
            <div class="article">
                <div class="article-content middle-content">
                    <h1>Adrese de livrare:</h1>
                    <form id="addressForm" name="addressForm" method="post"
                          action="">
                              <?php
                              $sql = "SELECT `id`, `address` FROM `user_delivery_addresses`"
                                      . " WHERE `uid` = ?";
                              $sel_stmt = $conn->prepare($sql);
                              $sel_stmt->bind_param("i", $_SESSION['uid']);
                              $sel_stmt->execute();
                              $sel_stmt->store_result();
                              if ($sel_stmt->num_rows > 0) {
                                  $i = 1;
                                  $addr_id = NULL;
                                  $addr_str = NULL;
                                  $sel_stmt->bind_result($addr_id, $addr_str);
                                  while ($sel_stmt->fetch()) {
                                      ?>
                                <div class="account-detail-row">
                                    <label for="newAddress">Adresa <?php echo $i; ?>:</label>
                                    <input type="text" value="<?php echo $addr_str; ?>" readonly/>
                                    <button class="address-remove-button" type="submit" name="addressRemove" value="<?php echo $addr_id; ?>">Șterge</button>
                                </div> 
                                <?php
                                $i++;
                            }
                        }
                        $sel_stmt->close();
                        ?>
                        <div class="account-detail-row">
                            <label for="newAddress">Adresă nouă:</label>
                            <input type="text" name="newAddress" />
                            <input type="submit" name="addressSub" value="Adaugă" />
                        </div> 
                    </form>
                </div>
            </div>
            <div class="article">
                <div class="article-content middle-content">
                    <h1>Deconectare:</h1>
                    <a href="logout.php">Deconectare</a>
                </div>
            </div>
        </div>
    </body>
</html>