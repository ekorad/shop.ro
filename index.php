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
            <form class="middle-content" method="post" onsubmit="onSearchSubmit()"
                  action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                  id="headerForm" name="searchForm">
                <a class="middle-v-aligned" id="header-home-link" href="index.php">Shop.ro</a>
                <a class="middle-v-aligned" id="header-short-home-link" href="index.php">S</a>
                <div id="right-header-container">
                    <a class="middle-v-aligned" href="#" style="float: left; margin-right: 15px;">
                        <span class="icon-user"></span>
                        <span class="large-width-text">Contul meu</span>
                    </a>
                    <a class="middle-v-aligned" href="#" style="float: right">
                        <span class="icon-cart"></span>
                        <span class="large-width-text">Coșul meu</span>
                    </a>
                </div>
                <div id="middle-header-container">
                    <div class="middle-v-aligned" id="header-input-wrapper">
                        <button title="Căutare" class="middle-v-aligned" type="submit" name="searchSubmit">
                            <span class="icon-search"></span></button>
                        <input type="text" name="searchVal" id="searchField"
                               placeholder="Descrie produsul căutat..." 
                               onfocus="onFieldFocus()" onblur="onFieldFocus()" />
                    </div>
                </div>
            </form>
        </div>
        <div id="adv-search-content">
            <div id="inner-search-content">
                <div class="card">
                    <span class="label-span">Categorie:</span>
                    <hr />
                </div>
                <div class="card">
                    
                </div>
                <div class="card">
                    
                </div>
                test
            </div>
        </div>
        <button title="Căutare avansată" onclick="expandAction()" id="adv-search-button">
            <span class="icon-chevron-down"></span>
        </button>
    </body>
</html>