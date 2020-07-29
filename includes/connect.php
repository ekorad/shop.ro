<?php

$server = "localhost";
$username = "account_master";
$conn_pass = "YKV0xkF7Tsx3pSlLvZmu";
$database = "shop";

$conn = mysqli_connect($server, $username, $conn_pass) or die("Connection failed.");
mysqli_select_db($conn, $database) or die("Could not access database.");
?>