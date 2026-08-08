<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "siddha_art";

global $connect;
$connect = mysqli_connect($servername, $username, $password, $database);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
