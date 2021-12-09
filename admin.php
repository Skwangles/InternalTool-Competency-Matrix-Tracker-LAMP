
<?php
include_once 'header.php';

//----------Not sure if this is secure, this is for the loading of the group list, have a look at alternative ways------
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "3") {
    header("location: index.php?error=invalidcall");
    exit();
}
?>

<br>
<form class="centre" action="staffedit.php" method="post"><button class="block" type="submit" name="submit">Modify Staff Accounts</button></form>
<form class="centre" action="gcedit.php" method="post"><button class="block" type="submit" name="submit">Modify Groups & Competencies</button></form>
<form class="centre" action="manageredit.php" method="post"><button class="block" type="submit" name="submit">Modify Managers</button></form>

<?php
include_once 'footer.php';
?>