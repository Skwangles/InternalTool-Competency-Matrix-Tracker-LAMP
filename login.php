<?php
include_once 'header.php';
include_once 'includes/login.inc.php';
?>


<h1 class="centre">Login</h1>
<form class="centre" action="" method="post">
    <input type="text" name="username" placeholder="Username">
    <br>
    <input type="password" name="pwd" placeholder="password">
    <br>
    <button class="actionbuttons" type="submit" name="submit">Login</button>
</form>



<?php
include_once 'footer.php';
?>