<?php
require('includes/connect.php');
require('includes/session.php');
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Cosul meu</title>
        <link rel="stylesheet" type="text/css" href="css/generic.css" />
        <link rel="stylesheet" type="text/css" href="css/index.css" />
        <link rel="stylesheet" type="text/css" href="css/products.css" />
        <link rel="stylesheet" type="text/css" href="css/cart.css" />
        <script type="text/javascript" src="scripts/search.js"></script>
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
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="searchForm"
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
                        <a href="account.php">
                            <span class="icon icon-user"></span>
                            <span class="mobile-hidden">Contul meu</span>
                        </a>
                        <a href="cart.php">
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
                        <a href="login.php">
                            <span class="icon icon-login"></span>
                            <span class="mobile-hidden">Conectare</span>
                        </a>
                        <a href="register.php">
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
                        <a href="#">Notebook-uri</a>
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
        <div id="main-content" class="middle-content">

        </div>
    </body>
</html>