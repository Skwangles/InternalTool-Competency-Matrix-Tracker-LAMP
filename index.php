<?php
include_once 'header.php';
?>
<?php
include_once 'error.php';

        if(isset($_SESSION["username"])){
            echo "<h2 class=\"centre\">Welcome, " . $_SESSION["name"] . "</h2>"; 
            include_once 'singleview.php';           
        }
        else{
            ?>
            <h1 class="centre">Login to see your competencies</h1>
            <form action="login.php"><button class="actionbuttons">Go To Login</button></form>
<?php
        }
        ?>

<?php
include_once 'footer.php';
?>
