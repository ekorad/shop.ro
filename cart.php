<?php
require('includes/connect.php');
require('includes/session.php');

if (!(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true)) {
    header("Location: login.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_COOKIE['shoppingCart']) && !empty($_COOKIE['shoppingCart'])) {
    if (isset($_POST['removeAllFromBasket'])) {
        setcookie('shoppingCart', null, -1, '/');
        header("Refresh:0");
    } else if (isset($_POST['addIndivToCart']) && !empty($_POST['addIndivToCart'])) {
        $id = $_POST['addIndivToCart'];
        setcookie("shoppingCart", $_COOKIE['shoppingCart'] . " " . $id, time() + 60 * 60 * 24 * 30, "/");
        header("Refresh:0");
    } else if (isset($_POST['removeIndivFromCart']) && !empty($_POST['removeIndivFromCart'])) {
        $id = $_POST['removeIndivFromCart'];
        $ids = explode(" ", $_COOKIE['shoppingCart']);
        unset($ids[array_search($id, $ids)]);
        setcookie("shoppingCart", implode(" ", $ids), time() + 60 * 60 * 24 * 30, "/");
        header("Refresh:0");
    } else if (isset($_POST['removeTotalIndivFromCart']) && !empty($_POST['removeTotalIndivFromCart'])) {
        $id = $_POST['removeTotalIndivFromCart'];
        $ids = explode(" ", $_COOKIE['shoppingCart']);
        $indexes = array_keys($ids, $id);
        for ($i = 0; $i < count($indexes); $i++) {
            unset($ids[$indexes[$i]]);
        }
        setcookie("shoppingCart", implode(" ", $ids), time() + 60 * 60 * 24 * 30, "/");
        header("Refresh:0");
    }
}
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
        <div id="main-content" class="middle-content">
            <?php
            if (!empty($_COOKIE)) {
                if (isset($_COOKIE['shoppingCart']) && !empty($_COOKIE['shoppingCart'])) {
                    $ids = array_map('intval', explode(" ", $_COOKIE['shoppingCart']));
                    $ids_unique = array_unique($ids);
                    $ids_unique_str = "(" . implode(",", $ids_unique) . ")";
                    $ids_counted = array_count_values($ids);

                    $sql = "SELECT `id`, `name`, `price`, `subcategory` FROM `products`"
                            . " WHERE `id` IN " . $ids_unique_str;
                    $prod_sel_result = $conn->query($sql);
                    ?>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                          method="post" id="cartForm" name="cartForm">
                              <?php
                              while ($row = $prod_sel_result->fetch_assoc()) {
                                  ?>
                            <div class="cart-product-card">
                                <div class="cart-product-card-content">
                                    <img src="<?php echo "./res/img/products/notebook/" . $row['id'] . "/img1.png"; ?>" 
                                         height=100 width=150 />
                                    <span class="cart-product-name"><?php echo ucfirst($row['subcategory']) . " " . $row['name']; ?></span>
                                    <span class="cart-product-quantity">Cantitate: <?php echo $ids_counted[intval($row['id'])]; ?></span>
                                    <span class="cart-product-indiv-price">Preț unitar: <?php echo $row['price']; ?> lei</span>
                                    <span class="cart-product-total-price">Preț total: <?php echo ($ids_counted[intval($row['id'])] * floatval($row['price'])); ?> lei</span>
                                </div>
                                <div class="remove-from-cart-container">
                                    <button type="submit" name="addIndivToCart" value="<?php echo $row['id']; ?>">+</button>
                                    <button type="submit" name="removeIndivFromCart" value="<?php echo $row['id']; ?>">-</button>
                                    <button type="submit" name="removeTotalIndivFromCart" value="<?php echo $row['id']; ?>">X</button>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <button type="submit" name="removeAllFromBasket">Golește coșul</button>
                        <button type="submit" name="continueToCheckout">Plată</button>
                    </form>
                    <?php
                } else {
                    ?>
                    <div id="empty-basket-container">
                        <span>Nu ai niciun produs în coș.</span>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div id="empty-basket-container">
                    <span>Nu ai niciun produs în coș.</span>
                </div>
                <?php
            }
            ?>
        </div>
    </body>
</html>