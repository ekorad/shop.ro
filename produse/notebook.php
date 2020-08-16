<?php
require('../includes/connect.php');

$cat = "laptop";
$subcat = "notebook";
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Shop</title>
        <link rel="stylesheet" type="text/css" href="../css/generic.css" />
        <link rel="stylesheet" type="text/css" href="../css/index.css" />
        <script type="text/javascript" src="../scripts/search.js"></script>
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
                <a href="index.php" id="header-home-link"
                   class="middle-v-aligned">Shop.ro</a>
                <div class="right-container middle-v-aligned">
                    <!--php here-->
                    <a href="#">
                        <span class="icon icon-user"></span>
                        <span class="mobile-hidden">Contul meu</span>
                    </a>
                    <a href="#">
                        <span class="icon icon-cart"></span>
                        <span class="mobile-hidden">Coșul meu</span>
                    </a>
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
        <div id="search-config">
            <span class="search-filter-label">Preț:</span><br />
            <?php

            function get_db_filters() {
                global $conn, $cat, $subcat;
                $sql = "SELECT MIN(`price`), MAX(`price`) FROM `products`"
                        . " WHERE `category` = ? AND `subcategory` = ?";
                $db_price_filter_stmt = $conn->prepare($sql);
                $db_price_filter_stmt->bind_param("ss", $cat, $subcat);
                $db_price_filter_stmt->execute();
                $db_price_filter_stmt->bind_result($db_filter['price_stored_min'], $db_filter['price_stored_max']);
                $db_price_filter_stmt->fetch();
                $db_price_filter_stmt->close();
                
                $sql = "SHOW COLUMNS FROM `$subcat`";
                $result = $conn->query($sql);
                $result->fetch_assoc();
                while ($row = $result->fetch_assoc()) {
                    
                }
                
                return $db_filter;
            }

            $db_filters = get_db_filters();

            $sql = "SELECT MIN(`price`), MAX(`price`) FROM `products`"
                    . " WHERE `category` = ? AND `subcategory` = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $cat, $subcat);
            $stmt->execute();
            $min_price = NULL;
            $max_price = NULL;
            $stmt->bind_result($min_price, $max_price);
            $stmt->fetch();
            $price_step = ($max_price - $min_price) / 5;
            $first_price_range = (string) $min_price;
            $last_price_range = (string) ($min_price + (4 * $price_step));
            $stmt->close();
            ?>            
            <label><input type = "radio" name = "price"
                          value="<?php echo "<" . $first_price_range; ?>" form = "searchForm"/><?php echo "Sub " . $first_price_range . " lei"; ?>
            </label><br />
            <?php
            for ($i = 1; $i < 4; $i++) {
                $price_string = ($min_price + ($i * $price_step)) . "-"
                        . ($min_price + (($i + 1) * $price_step));
                ?>
                <label><input type="radio" name="price"
                              value="<?php echo $price_string; ?>" form="searchForm"/><?php echo $price_string . " lei"; ?>
                </label><br />
                <?php
            }
            ?>
            <label><input type = "radio" name = "price"
                          value="<?php echo ">" . $last_price_range; ?>" form = "searchForm"/><?php echo "Peste " . $last_price_range . " lei"; ?>
            </label>
            <?php
            ?>
        </div>
        <div id="main-content">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchSub'])) {
                $filter['min_price'] = 0;
                $filter['max_price'] = 999999999;
                if (isset($_POST['price'])) {
                    $filter_price_range = str_replace('/\s+/', '', $_POST['price']);
                    if ($filter_price_range[0] === "<") {
                        $filter['max_price'] = intval(substr($filter_price_range, 1));
                    } else if ($filter_price_range[0] === ">") {
                        $filter['min_price'] = intval(substr($filter_price_range, 1));
                    } else {
                        $filter['min_price'] = intval(substr($filter_price_range, 0, strpos($filter_price_range, "-")));
                        $filter['max_price'] = intval(substr($filter_price_range, strpos($filter_price_range, "-") + 1));
                    }
                }
                // handle search filter

                $sql = "SELECT  `id`, `name`, `price`, `quantity` FROM `products` WHERE"
                        . " `price` > ? AND `price` < ?";

                $search_product_stmt = $conn->prepare($sql);
                $search_product_stmt->bind_param("ii", $filter['min_price'], $filter['max_price']);
                $search_product_stmt->execute();
                $search_product_result = $search_product_stmt->get_result();
                while ($row = $search_product_result->fetch_assoc()) {
                    echo $row['name'] . " " . $row['price'] . "<br />";
                }
                $search_product_stmt->close();

                echo $filter['min_price'] . " " . $filter['max_price'];
            }
            ?>
        </div>
    </body>
</html>