<?php 
if($_SESSION["role"] == "3"){ //only shows button if the user is an admin ?>
    <button class="centre actionbuttons" style="background-color:darkcyan; width:max-content; border: 2%; border-colour:red;" action="managerview.php" onclick="switchEditMode()" id="switchButton" name="editMode"><?php echo $_SESSION["editMode"] == "1"?"Disable Edit Mode":"Enable Edit Mode"?></button>
    <?php
    }
