<?php
require('../includes/connect.php');
require('../includes/session.php');

$cat = "laptop";
$subcat = "notebook";

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['addToBasket'])) {
    if (!(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true)) {
        header("Location: ./../login.php");
        exit;
    } else {
        $shoppingCartStr = "";
        if (!empty($_COOKIE) && isset($_COOKIE['shoppingCart'])) {
            $shoppingCartStr = $_COOKIE['shoppingCart'];
        }
        if ($shoppingCartStr === "") {
            $shoppingCartStr = strval($_POST['addToBasket']);
        } else {
            $shoppingCartStr .= " " . strval($_POST['addToBasket']);
        }
        setcookie("shoppingCart", $shoppingCartStr, time() + 60 * 60 * 24 * 30, "/");
        header("Refresh:0");
    }
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Shop</title>
        <link rel="stylesheet" type="text/css" href="../css/generic.css" />
        <link rel="stylesheet" type="text/css" href="../css/index.css" />
        <link rel="stylesheet" type="text/css" href="../css/products.css" />
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
                <a href="./../index.php" id="header-home-link"
                   class="middle-v-aligned">Shop.ro</a>
                <div class="right-container middle-v-aligned">
                    <?php
                    if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
                        ?>
                        <a href="./../account.php">
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
            <div id="search-config">
                <div class="filter-card">
                    <span class="search-filter-label">Producător:</span>
                    <div class="filter-card-content">
                        <label>
                            <input type="radio" value="" name="manufacturer" form="searchForm" />Oricare<br />
                        </label>
                        <?php
                        $sql = "SELECT DISTINCT `manufacturer` FROM `products`";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_row()) {
                            ?>
                            <label>
                                <input type="radio" name="manufacturer"
                                       value="<?php echo $row[0] ?>" form="searchForm" />
                                       <?php echo $row[0]; ?>
                            </label>
                            <br />
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="filter-card">
                    <span class="search-filter-label">Preț:</span>
                    <div class="filter-card-content">
                        <label>
                            <input type="radio" value="" name="price" form="searchForm" />Oricât<br />
                        </label>
                        <?php
                        $sql = "SELECT MIN(`price`), MAX(`price`) FROM `products`"
                                . " WHERE `category` = ? AND `subcategory` = ?";
                        $price_range_stmt = $conn->prepare($sql);
                        $price_range_stmt->bind_param("ss", $cat, $subcat);
                        $price_range_stmt->execute();
                        $price_range_stmt->bind_result($db_range['min_price'], $db_range['max_price']);
                        $price_range_stmt->fetch();
                        $price_step = ($db_range['max_price'] - $db_range['min_price']) / 5;
                        $first_price_range = (string) $db_range['min_price'];
                        $last_price_range = (string) ($db_range['min_price'] + (4 * $price_step));
                        $price_range_stmt->close();
                        ?>
                        <?php
                        for ($i = 0; $i < 5; $i++) {
                            $price_string = ($db_range['min_price'] + ($i * $price_step)) . "-"
                                    . ($db_range['min_price'] + (($i + 1) * $price_step));
                            ?>
                            <label><input type="radio" name="price"
                                          value="<?php echo $price_string; ?>" form="searchForm"/><?php echo $price_string . " lei"; ?>
                            </label><br />
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="filter-card">
                    <span class="search-filter-label">Producător procesor:</span>
                    <div class="filter-card-content">
                        <label>
                            <input type="radio" value="" name="cpu_manufacturer" form="searchForm" />Oricare<br />
                        </label>
                        <?php
                        $sql = "SELECT DISTINCT `cpu_manufacturer` FROM `notebook`";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_row()) {
                            ?>
                            <label>
                                <input type="radio" name="cpu_manufacturer"
                                       value="<?php echo $row[0] ?>" form="searchForm" />
                                       <?php echo $row[0]; ?>
                            </label>
                            <?php
                        }

                        define("CPU_FREQ_FILTER_NUM_STEPS", 5);

                        $sql = "SELECT MIN(`cpu_freq`), MAX(`cpu_freq`) FROM `notebook`";
                        $result = $conn->query($sql);
                        $row = $result->fetch_row();
                        $db_range['min_freq'] = intval($row[0]);
                        $db_range['max_freq'] = intval($row[1]);
                        $freq_step = ($db_range['max_freq'] - $db_range['min_freq']) / CPU_FREQ_FILTER_NUM_STEPS;
                        ?>
                    </div>
                </div>
                <div class="filter-card">
                    <span class="search-filter-label">Frecvență procesor:</span>
                    <div class="filter-card-content">
                        <label>
                            <input type="radio" value="" name="cpu_frequency" form="searchForm" />Oricât<br />
                        </label>
                        <?php
                        for ($i = 0; $i < CPU_FREQ_FILTER_NUM_STEPS; $i++) {
                            $crt_freq_min = $db_range['min_freq'] + $i * $freq_step;
                            $crt_freq_max = $crt_freq_min + $freq_step;
                            $crt_freq_string = $crt_freq_min . "-" . $crt_freq_max;
                            ?>
                            <label>
                                <input type="radio" name="cpu_freq"
                                       value="<?php echo $crt_freq_string; ?>" form="searchForm" />
                                       <?php echo $crt_freq_string . " MHz"; ?>
                            </label>
                            <br />
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="filter-card">
                    <span class="search-filter-label">Număr nuclee procesor:</span>
                    <div class="filter-card-content">
                        <label>
                            <input type="radio" value="" name="cpu_cores" form="searchForm" />Oricât<br />
                        </label>
                        <?php
                        $sql = "SELECT DISTINCT `cpu_cores` FROM `notebook` ORDER BY `cpu_cores` ASC";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_row()) {
                            ?>
                            <label>
                                <input type="radio" name="cpu_cores"
                                       value="<?php echo $row[0] ?>" form="searchForm" />
                                       <?php echo $row[0]; ?>
                            </label>
                            <br />
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="filter-card">
                    <span class="search-filter-label">Producător placă video:</span>
                    <div class="filter-card-content">
                        <label>
                            <input type="radio" value="" name="gpu_manufacturer" form="searchForm" />Oricare<br />
                        </label>
                        <?php
                        $sql = "SELECT DISTINCT `gpu_manufacturer` FROM `notebook`";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_row()) {
                            ?>
                            <label>
                                <input type="radio" name="gpu_manufacturer"
                                       value="<?php echo $row[0] ?>" form="searchForm" />
                                       <?php echo $row[0]; ?>
                            </label>
                            <br />
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="filter-card">
                    <span class="search-filter-label">Model placă video:</span>
                    <div class="filter-card-content">
                        <label>
                            <input type="radio" value="" name="gpu_model" form="searchForm" />Oricare<br />
                        </label>
                        <?php
                        $sql = "SELECT DISTINCT `gpu_model` FROM `notebook`";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_row()) {
                            ?>
                            <label>
                                <input type="radio" name="gpu_model"
                                       value="<?php echo $row[0] ?>" form="searchForm" />
                                       <?php echo $row[0]; ?>
                            </label>
                            <br />
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="filter-card">
                    <span class="search-filter-label">Capacitate HDD:</span>
                    <div class="filter-card-content">
                        <label>
                            <input type="radio" value="" name="hdd_cap" form="searchForm" />Oricât<br />
                        </label>
                        <?php
                        $sql = "SELECT DISTINCT `hdd_cap` FROM `notebook` WHERE `hdd_cap` != 0 ORDER BY `hdd_cap` ASC";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_row()) {
                            ?>
                            <label>
                                <input type="radio" name="hdd_cap"
                                       value="<?php echo $row[0] ?>" form="searchForm" />
                                       <?php echo $row[0]; ?>
                            </label>
                            <br />
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="filter-card">
                    <span class="search-filter-label">Viteză HDD:</span>
                    <div class="filter-card-content">
                        <label>
                            <input type="radio" value="" name="hdd_speed" form="searchForm" />Oricât<br />
                        </label>
                        <?php
                        $sql = "SELECT DISTINCT `hdd_speed` FROM `notebook` WHERE `hdd_speed` != 0 ORDER BY `hdd_speed` ASC";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_row()) {
                            ?>
                            <label>
                                <input type="radio" name="hdd_speed"
                                       value="<?php echo $row[0] ?>" form="searchForm" />
                                       <?php echo $row[0]; ?>
                            </label>
                            <br />
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="filter-card">
                    <span class="search-filter-label">Capacitate SSD:</span>
                    <div class="filter-card-content">
                        <label>
                            <input type="radio" value="" name="ssd_cap" form="searchForm" />Oricât<br />
                        </label>
                        <?php
                        $sql = "SELECT DISTINCT `ssd_cap` FROM `notebook` WHERE `ssd_cap` != 0 ORDER BY `ssd_cap` ASC";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_row()) {
                            ?>
                            <label>
                                <input type="radio" name="ssd_cap"
                                       value="<?php echo $row[0] ?>" form="searchForm" />
                                       <?php echo $row[0]; ?>
                            </label>
                            <br />
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div id="product-content">
                <?php
                $specific_filter = array();

                $manufacturer = NULL;
                $min_query_price = 0;
                $max_query_price = 999999999;
                $min_cpu_freq = 0;
                $max_cpu_freq = 50000;

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchSub'])) {

                    if (isset($_POST['price']) && !empty($_POST['price'])) {
                        $price = $_POST['price'];
                        $min_query_price = intval(substr($price, 0, strpos($price, "-")));
                        $max_query_price = intval(substr($price, strpos($price, "-") + 1));
                    }

                    if (isset($_POST['manufacturer']) && !empty($_POST['manufacturer'])) {
                        $manufacturer = $_POST['manufacturer'];
                    }

                    // specific filters

                    if (isset($_POST['cpu_freq']) && !empty($_POST['cpu_freq'])) {
                        $cpu_freq = $_POST['cpu_freq'];
                        $min_cpu_freq = intval(substr($cpu_freq, 0, strpos($cpu_freq, "-")));
                        $max_cpu_freq = intval(substr($cpu_freq, strpos($cpu_freq, "-") + 1));

                        $specific_filter[] = "`cpu_freq` >= $min_cpu_freq AND `cpu_freq` <= $max_cpu_freq";
                    }

                    if (isset($_POST['cpu_manufacturer']) && !empty($_POST['cpu_manufacturer'])) {
                        $cpu_manufacturer = $_POST['cpu_manufacturer'];
                        $specific_filter[] = "`cpu_manufacturer` = '$cpu_manufacturer'";
                    }

                    if (isset($_POST['cpu_cores']) && !empty($_POST['cpu_cores'])) {
                        $cpu_cores = $_POST['cpu_cores'];
                        $specific_filter[] = "`cpu_cores` = $cpu_cores";
                    }

                    if (isset($_POST['gpu_manufacturer']) && !empty($_POST['gpu_manufacturer'])) {
                        $gpu_manufacturer = $_POST['gpu_manufacturer'];
                        $specific_filter[] = "`gpu_manufacturer` = '$gpu_manufacturer'";
                    }

                    if (isset($_POST['gpu_model']) && !empty($_POST['gpu_model'])) {
                        $gpu_model = $_POST['gpu_model'];
                        $specific_filter[] = "`gpu_model` = '$gpu_model'";
                    }

                    if (isset($_POST['hdd_cap']) && !empty($_POST['hdd_cap'])) {
                        $hdd_cap = $_POST['hdd_cap'];
                        $specific_filter[] = "`hdd_cap` = $hdd_cap";
                    }

                    if (isset($_POST['hdd_speed']) && !empty($_POST['hdd_speed'])) {
                        $hdd_speed = $_POST['hdd_speed'];
                        $specific_filter[] = "`hdd_speed` = $hdd_speed";
                    }

                    if (isset($_POST['ssd_cap']) && !empty($_POST['ssd_cap'])) {
                        $ssd_speed = $_POST['ssd_cap'];
                        $specific_filter[] = "`ssd_cap` = $ssd_speed";
                    }
                }

                $sql = "SELECT `id` FROM `notebook`";

                if (count($specific_filter) > 0) {
                    $sql .= " WHERE " . implode(" AND ", $specific_filter);
                }

//            echo $sql;

                $result = $conn->query($sql);
                $ids = array();
                while ($row = $result->fetch_assoc()) {
                    $ids[] = intval($row['id']);
                }

                if (!empty($ids)) {
                    $id_range = "(" . implode(",", $ids) . ")";

                    $sql = "SELECT `id`, `name`, `price`, `quantity` FROM `products`"
                            . " WHERE `id` IN " . $id_range . " AND `price` >= $min_query_price"
                            . " AND `price` <= $max_query_price";

                    if (!empty($manufacturer)) {
                        $sql .= " AND `manufacturer` = '$manufacturer'";
                    }

                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <div class="product-card">
                            <div class="product-card-content">
                                <a href="#">
                                    <img src="<?php echo "./../res/img/products/notebook/" . $row['id'] . "/img1.png"; ?>" 
                                         height=100 width=150 /><br />
                                    <span class="product-name">Notebook <?php echo $row['name']; ?></span>
                                </a>
                                <?php
                                if ($row['quantity'] > 0) {
                                    echo "<span class='in-stock-label' style='color:green'>În stoc</span>";
                                } else {
                                    echo "<span class='in-stock-label' style='color:red'>Nu este disponibil</span>";
                                }
                                ?>
                                <span class="product-price"><?php echo $row['price']; ?> lei</span>
                                <button class="product-add-button" form="addProductForm" type="submit" name="addToBasket" value="<?php echo $row['id']; ?>">Adaugă în coș</button>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <form id="addProductForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post"></form>
    </body>
</html>