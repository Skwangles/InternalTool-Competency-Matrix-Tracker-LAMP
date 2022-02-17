<br>
<div class="centre inwindow-buttons">
<?php //shows all the admin controls which are to be available in the windows where info is being viewed
if($_SESSION["isAdmin"] == 1){ //only shows button if the user is an admin ?>
    <button class="actionbuttons inwindow adminbuttons" action="managerview.php" onclick="switchEditMode()" id="switchButton" name="editMode"><?php echo $_SESSION["editMode"] == "1"?"Disable Edit Mode":"Enable Edit Mode"?></button>
    <?php if($_SESSION["editMode"] == '1'){
    echo "<button class=\"actionbuttons inwindow adminbuttons\" onclick=\"location.reload()\">Reload Entries</button>";
    }
}
?>
</div>