<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Competency Charts</title>
    <link rel="stylesheet" href="CSS/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="JS/update.ajax.js"></script>
    
    <!-- CSS only -->
    
<body>

    <!--Nav Bar-->
    <?php
                if (isset($_SESSION["name"])) {
                    echo "<div id='currentUserTag'><p>Logged In As: </p><p><b>" . $_SESSION["name"] . "</b></p></div>";
                }
                ?>
        <div id="wrapper" class="wrapper">
       
            <ul>
               <?php 
               if (isset($_SESSION["username"])) {
               ?>
                <a href="singleview.php">Your Competencies</a>

                <?php
                
                    if ($_SESSION["role"] == 2 || $_SESSION["isAdmin"] == 1) {
                        echo "<a href=\"managerview.php\">Department Competencies</a>";
                    }
                    if ($_SESSION["isAdmin"] == "1") { //If admin, shows admin options
                ?>
                        <a class="dropbtn" href="admin.php">Admin Panel</a>
                <?php
                    }
                    echo "<a href=\"settings.php\">Settings</a>";
                    echo "<a href=\"logout.php\">Logout</a>";
                } 
                ?>
            </ul>
        </div>
        <div class ="content">
    <?php 
    include_once 'error.php';