<?php

$servername = "localhost";
$databaseUsername = "root";
$databasePassword = "";
$databaseName = "competency";

$conn = mysqli_connect($servername, $databaseUsername, $databasePassword, $databaseName);

if (!$conn) {
    die("Connection Failed " . mysqli_connect_error());
}
