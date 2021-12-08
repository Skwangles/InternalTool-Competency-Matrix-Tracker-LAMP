<?php
session_start();//Destroys session variables, so the user is logged out
session_unset();
session_destroy();
header("location: ../index.php");
exit();