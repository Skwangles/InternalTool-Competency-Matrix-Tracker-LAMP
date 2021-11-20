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
        <a href="index.php">Home</a>
        <a href="singleview.php">Your Competencies</a>
        
        <?php
        if(isset($_SESSION["username"])){
            if($_SESSION["role"] > 1){
                echo "<a href=\"managerview.php\">Your Department's Competencies</a>";
            }
            if($_SESSION["role"] == "3"){//If admin, shows admin options
                echo "<a href=\"admin.php\">Admin Panel</a>";
                
            }
            echo "<a href=\"includes/logout.inc.php\">Logout</a>";            
        }
        else{
           
           echo "<a href=\"login.php\">Log In</a>";
        }
        
        ?>
</ul>
</div>
</nav>
