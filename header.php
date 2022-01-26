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
    <nav>
        <div class="wrapper">
            <ul>
                <?php
                if (isset($_SESSION["name"])) {
                    echo "<p style=\"float:left;\"><b>User: " . $_SESSION["name"] . "</b></p>";
                }
                ?>
                <a href="index.php">Home</a>
                <a href="singleview.php">Your Competencies</a>

                <?php
                if (isset($_SESSION["username"])) {
                    if ($_SESSION["role"] > 1) {
                        echo "<a href=\"managerview.php\">Your Department's Competencies</a>";
                    }
                    if ($_SESSION["role"] == "3") { //If admin, shows admin options
                ?>
                        <a class="dropbtn" href="admin.php">Admin Panel</a>
                <?php
                    }
                    echo "<a href=\"settings.php\">Settings</a>";
                    echo "<a href=\"logout.php\">Logout</a>";
                } else {

                    echo "<a href=\"login.php\">Log In</a>";
                }

                ?>
            </ul>
        </div>
    </nav>