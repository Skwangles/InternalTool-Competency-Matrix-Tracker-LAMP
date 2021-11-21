<?php
include_once 'header.php';

require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';

include_once 'error.php';

include_once 'admin.php'; //Adds the navigation, and checks the user has the right permissions.
?>
<br>
<h3 class="centre">Change User Competency Values</h3>


<?php
$allUsers = getUsers($conn);
while ($user = mysqli_fetch_array($allUsers)) {
?>
<section>
    <form class="centre" action="includes/actionmanagers.inc.php" method="post">
        <table border="1" class="centre">
            <tr>
                <th><?php echo $user["UName"] ?></th>
            </tr>
            <tr>
                <th>Group Name</th>
                <th>Group Role</th>
            </tr>
            <?php
            //Will list competencies by group
            $userGroups = UserGroupFromUser($conn, $user["UserID"]);
            $isNull = true;
            while ($group = mysqli_fetch_assoc($userGroups)) {
                $isNull = false;
            ?>
                <td>
                    <?php
                    echo $group["GName"];
                    ?>
                </td>
                <td>
                    <?php
                    $valueIndex = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM UserGroups WHERE Users = " . $user["UserID"] . " AND Groups = " . $group["GroupID"]))["isManager"];
                    echo "<select name=\"managerValue[]\">";
                    ?>
                    <option value="0" <?php echo $valueIndex == 0 ? "selected" : ""; //Sets the default selected option 
                                        ?>>Staff</option>
                    <option value="1" <?php echo $valueIndex == 1 ? "selected" : ""; ?>>Manager</option>

                    </select>
                    <input type="hidden" name="gid[]" value="<?php echo $group["GroupID"]; ?>">
                </td>
                </tr>
            <?php
            }
            if($isNull){
            $isNull = emptyArrayError($isNull);
            }
            ?>
        </table>
        <button class="actionbuttons addbuttons" type="submit" name="update" value="<?php echo $user["UserID"] //Will give processing form the id to update 
                                                                            ?>">Update <?php echo $user["UName"] ?>'s Values</button>
        <br>
        <br>
    </form>
    </section>
<?php
}
include_once 'footer.php';
