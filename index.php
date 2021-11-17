<?php
include_once 'header.php';
?>
<?php
        if(isset($_SESSION["username"])){
            echo "<p>Welcome, " . $_SESSION["name"] . "</p>";            
        }
        else{
            echo "<p>Login to see you competencies</p>";    
        }
        ?>

<?php
include_once 'footer.php';
?>

