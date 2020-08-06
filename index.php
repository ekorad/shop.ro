<?php
require('./includes/connect.php');
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Shop</title>
        <link rel="stylesheet" type="text/css" href="css/generic.css" />
        <link rel="stylesheet" type="text/css" href="css/index.css" />
        <script type="text/javascript" src="scripts/search.js"></script>
    </head>
    <body>
        <div id="header">
            <form action="search.php" method="post" name="searchForm"
                  class="middle-content">
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

        </div>
        <div id="main-content">
            <?php
                $sql = "SELECT DISTINCT `category` FROM `products`";
            ?>
        </div>
    </body>
</html>