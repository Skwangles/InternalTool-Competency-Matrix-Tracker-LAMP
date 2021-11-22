<?php
include_once 'header.php';

//----------Not sure if this is secure, this is for the loading of the group list, have a look at alternative ways------
require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';

// if (!isset($_SESSION["role"]) || $_SESSION["role"] != "3") {
//     header("location: index.php?error=invalidcall");
//     exit();
// }
include_once 'error.php';

include_once 'admin.php';
?>

<!----
//
//COMPETENCY VALUES
//
//
-->
<br>
<h1 class="centre">Change User Competency Values</h1>


<?php
$allUsers = getUsers($conn);
while ($user = mysqli_fetch_array($allUsers)) {
    updateUserCompetencies($conn, $user["UserID"]);///-----------------likely an inefficent spot
?>
<section id="<?php echo $user["UserID"] ?>">
    <form class="centre" action="includes/actionuservalues.inc.php" method="post">
        <table border="1" class="centre">
            <tr>
                <th><?php echo namePrint($_SESSION, $user) ?></th>
            </tr>
            <tr>
                <th>Competency</th>
                <th>Groups Associated</th>
                <th>Other Associations</th>
                <th>Value</th>
            </tr>
            <?php
            //Will list competencies by group
            $userComptencies = UserCompetenciesFromUser($conn, $user["UserID"]);
            $isNull = true;//If the while fails to do a single loop - allows removal of button and showing of the "No Competencies" button
            while ($competencies = mysqli_fetch_assoc($userComptencies)) {
                $isNull = false;
                $groups = CompetencyGroupFromCompetency($conn, $competencies["CompetencyID"]); //Gets groups which contain the specific competency
                echo "<tr><td>" . $competencies["CName"] . "</td>
            <td><ul>";
                while ($group = mysqli_fetch_assoc($groups)) {
                    if (mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM UserGroups WHERE Users = " . $user["UserID"] . " AND Groups = " . $group["GroupID"]))) { //only displays if user is in that group
                        echo "<li style=\"text-align:left;\">" . $group["GName"] . "</li>";
                    }
                }
                echo "</ul></td><td><ul>";
                $roleCompetencies = CompetencyRolesFromCompetency($conn, $competencies["CompetencyID"]); //Get all roles attached to competency
                while ($role = mysqli_fetch_assoc($roleCompetencies)) { //Loops through roles associated with competency - checks if any roles are possessed by the current user. 
                    if ($role["RoleID"] == $user["URole"]) {
                        echo "<li style=\"text-align:left;\"> Role: " . $role["RName"] . "</li>";
                    }
                }
                if(mysqli_fetch_assoc(IndUserCompetenciesFromCompetency($conn, $competencies["CompetencyID"]))){
                    echo "<li style=\"text-align:left;\">Individually Assigned</li>";
                }
            ?>
                </ul>
                </td>
                <td>
                    <?php
                    $valueIndex = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM UserCompetencies WHERE Users = " . $user["UserID"] . " AND Competencies = " . $competencies["CompetencyID"]))["Rating"];
                    echo "<select name=\"compVal[]\">";
                    ?>
                    <option value="0" <?php echo $valueIndex == 0 ? "selected" : ""; //Sets the default selected option 
                                        ?>>Not Trained</option>
                    <option value="1" <?php echo $valueIndex == 1 ? "selected" : ""; ?>>Trained</option>
                    <option value="2" <?php echo $valueIndex == 2 ? "selected" : ""; ?>>Can demonstrate competency</option>
                    <option value="3" <?php echo $valueIndex == 3 ? "selected" : ""; ?>>Can train others</option>
                    </select>
                    <input type="hidden" name="id[]" value="<?php echo $competencies["CompetencyID"]; ?>">
                </td>
                </tr>
            <?php
            }
            if ($isNull) {
                echo "<tr><td>No Competencies Found</td><td>-</td><td>-</td><td>-</td></tr>";
            }
            ?>
        </table>
        <?php if (!$isNull) { ?>
            <button class="actionbuttons addbuttons" type="submit" name="updateC" value="<?php echo $user["UserID"] //Will give processing form the id to update 
                                                                                ?>">Update <?php echo $user["UName"] ?>'s Values</button>
        <?php } ?>
        <br>
        <br>
    </form>
    </section>
<?php
}

?>


<?php
include_once 'footer.php';
?>