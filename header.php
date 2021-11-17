<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="utf-8">
<title>Competency Charts</title>
<link rel="stylesheet" href="CSS/style.css">
</head>
<body>

<!--Nav Bar--> 
<nav>
<div class="wrapper">
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="singleview.php">Your Competencies</a></li>
        
        <?php
        if(isset($_SESSION["username"])){
            if($_SESSION["role"] != "1"){
                echo "<li><a href=\"managerview.php\">Your Department's Competencies</a></li>";
            }
            if($_SESSION["role"] == "3"){//If admin, shows admin options
                echo "<li><a href=\"admin.php\">Admin Panel</a></li>";
                
            }
            echo "<li><a href=\"includes/logout.inc.php\">Logout</a></li>";            
        }
        else{
           
           echo "<li><a href=\"login.php\">Log In</a></li>";
        }
        
        ?>
</ul>
</div>
</nav>
