<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "testing";

if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
{
    die("Failed to connect!");
}
?>