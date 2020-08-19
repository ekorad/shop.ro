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
            <span class="search-filter-label">Producător:</span><br />
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
            <br />
            <span class="search-filter-label">Frecvență:</span><br />
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
            <br />
            <span class="search-filter-label">Număr nuclee:</span>
            <br />
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
            <br />
            <span class="search-filter-label">Producător:</span>
            <br />
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
            <br />
            <span class="search-filter-label">Model:</span>
            <br />
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
            <br />
            <span class="search-filter-label">Capacitate HDD:</span>
            <br />
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
            <br />
            <span class="search-filter-label">Viteză HDD:</span>
            <br />
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
                <br />
            <span class="search-filter-label">Capacitate SSD:</span>
            <br />
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
        <div id="main-content">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchSub'])) {
                $filter['min_price'] = 0;
                $filter['max_price'] = 999999999;
                $filter['min_freq'] = 0;
                $filter['max_freq'] = 50000;
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
                        . " `price` >= ? AND `price` <= ?";

                $search_product_stmt = $conn->prepare($sql);
                $search_product_stmt->bind_param("ii", $filter['min_price'], $filter['max_price']);
                $search_product_stmt->execute();
                $search_product_result = $search_product_stmt->get_result();
                while ($row = $search_product_result->fetch_assoc()) {
                    echo $row['name'] . " " . $row['price'] . "<br />";
                }
                $search_product_stmt->close();
            }
            ?>
        </div>
    </body>
</html>