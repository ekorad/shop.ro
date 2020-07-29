<?php
require './includes/session.php';
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Shop</title>
        <link rel="stylesheet" type="text/css" href="css/generic.css" />
        <link rel="stylesheet" type="text/css" href="css/index.css" />
    </head>
    <body>
        <div id="main-body">
            <h1>Shop.ro</h1>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                  name="searchForm" method="post" onsubmit="return onSearchSubmit()"
                  id="searchForm">
                <input type="text" name="search-token" placeholder="Descrie produsul" />
                <button type="submit" name="searchSub">Căutare</button>
            </form>
            <div id="acc-info-container">
                <?php
                if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
                    ?>
                    <span><a href="account.php">Contul meu</a> (<?php echo $_SESSION['name']; ?>)</span>
                    <a href="logout.php">Deconectare</a>
                    <?php
                } else {
                    ?>
                    <a href="login.php">Conectare</a>
                    <a href="register.php">Înregistrare</a>
                    <?php
                }
                ?>
            </div>
        </div>
    </body>
</html>