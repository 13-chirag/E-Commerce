<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$hostname = "localhost:3306";
$username = "root";
$password = "root";
$database_name = "project_ecom";

// $hostname = "localhost:8111";
// $username = "chirag";
// $password = "";
// $database_name = "project_ecom";

$db_con = mysqli_connect($hostname, $username, $password, $database_name);

if (mysqli_connect_error()) {
    die("Connection Failed");
}

?>