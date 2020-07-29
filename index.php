<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Shop</title>
        <link rel="stylesheet" type="text/css" href="css/generic.css" />
        <link rel="stylesheet" type="text/css" href="css/index.css" />
        <script type="text/javascript" src="scripts/search.js"></script>
    </head>
    <body>
        <div id="header">
            <form class="middle-content" method="post" onsubmit="onSearchSubmit()"
                  action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                  id="searchForm">
                <a id="header-link" href="index.php">Shop.ro</a>
                <a class="right-link" href="#">Contul meu</a>
                <a class="right-link" href="#">Coșul meu</a>
                <div id="searchInputWrapper">
                    <input id="searchField" type="text" name="searchToken" 
                           placeholder="Descrie produsul căutat..." />
                </div>                
            </form>
        </div>
    </body>
</html>