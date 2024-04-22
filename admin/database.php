<?php

$hostName = "localhost";
$dbUser = "root";
$dbpassword = "";
$dbName = "login_register";
$conn = mysqli_connect($hostName, $dbUser, $dbpassword, $dbName);
if (!$conn) {
    die("Something went wrong;");
}

?>