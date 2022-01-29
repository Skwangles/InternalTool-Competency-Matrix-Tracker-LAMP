<?php
include_once 'includes/header.php';
?>
<?php

        if(isset($_SESSION["username"])){
            echo "<h2 class=\"centre\">Welcome, " . $_SESSION["name"] . "</h2>"; 
            include_once 'singleview.php';           
        }
        else{
            ?>
     <h1 class="centre">Login To See Your Competencies!</h1> 
     <hr class="seperator2">
<?php
        include_once 'login.php';
        }
        ?>

<?php
include_once 'includes/footer.php';
?>
