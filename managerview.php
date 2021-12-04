<?php
include_once 'header.php';
require_once 'includes/functions.inc.php';
require_once 'includes/dbh.inc.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] == "1") {
    header("location: index.php?error=invalidcall"); //If doesn't have the correct perms, kicks out
    exit();
}

?>
<h2 class="centre">Your Departments</h2>
<?php
$groups = UserGroupFromUserWhereManager($conn, $_SESSION["userid"]);
while ($group = mysqli_fetch_array($groups)) {
?>

    <table border="1" class="centre">
        <tr>
            <th class="tabletitle"><?php echo $group["GName"] ?></th>
        </tr>
        <tr>
            <th>-</th>
            <?php
            $users = UserGroupFromGroup($conn, $group["GroupID"]); //Gets all users in the current group
            while ($user = mysqli_fetch_array($users)) { //Gives a heading to all users
                echo "<th>" . namePrint($_SESSION, $user) . "</th>";
            }
            ?>
        </tr>
        <?php
        $competencies = CompetencyGroupFromGroup($conn, $group["GroupID"]);
        while ($competency = mysqli_fetch_array($competencies)) {
            echo "<tr><td>" . $competency["CName"] . "</td>";
            $users = UserGroupFromGroup($conn, $group["GroupID"]); //---------------------Can try save another call to users, by creating a variable upon first call and re-using it
            while ($user = mysqli_fetch_array($users)) {
                displayUserRatings($conn, $competency["CompetencyID"], $user["UserID"]);
            }
            echo "</tr>";
        }

        ?>
    </table>

<?php
} //--End of group while loop
if ($_SESSION["role"] == 3) { //Admin can see all groups
    $groups = getGroups($conn);
?>

    <h2 class="centre">ALL groups & users</h2>
    <hr class="seperator">
    <table border="1" class="centre">
        <?php
        echo "<tr><th class=\"tabletitle\"><b>Individual User Competencies</b></th></tr>";
        //
        //Individual User values
        //
        ?>

        
        <?php
        $competencies = mysqli_query($conn, "SELECT DISTINCT CompetencyID, CName FROM individualusercompetencies JOIN competencies ON individualusercompetencies.Competencies = competencies.CompetencyID;");//It is possible that DISTINCT means that two competencies with the same name are rejected
        $isNull = true;
        while ($competency = mysqli_fetch_assoc($competencies)) {
            $isNull = false;
            echo "<tr><th>-</th>";
            $users = IndUserCompetenciesFromCompetency($conn, $competency["CompetencyID"]);
            while ($user = mysqli_fetch_array($users)) { //Gives a heading to all users
                echo "<th>" . namePrint($_SESSION, $user) . "</th>"; //If empty, will give "none found" otherwise will print out
            }
            echo "</tr><tr><td>" . $competency["CName"] . "</td>";
            mysqli_data_seek($users, 0);
            while ($user = mysqli_fetch_array($users)) { //Gives a heading to all users
                displayUserRatings($conn, $competency["CompetencyID"], $user["UserID"]);
            }
            echo "</tr>";
        }
        if ($isNull) {
            $isNull = emptyArrayError($isNull);
        }

        ?>

        <br>
        <?php
        //
        //All role summaries
        //
        $roles = mysqli_query($conn, "SELECT * FROM roles");
        while ($role = mysqli_fetch_array($roles)) {
        ?>

            <tr>
                <th class="tabletitle"><?php echo $role["RName"] ?></th>
            </tr>
            <tr>
                <th>-</th>
                <?php
                $users = mysqli_query($conn, "SELECT * FROM users WHERE URole = '" . $role["RoleID"] . "';"); //Gets all users in the current role
                while ($user = mysqli_fetch_array($users)) { //Gives a heading to all users
                    echo "<th>" . namePrint($_SESSION, $user) . "</th>"; //If empty, will give "none found" otherwise will print out
                }
                ?>
            </tr>
            <?php
            $competencies = CompetencyRolesFromRoles($conn, $role["RoleID"]);
            $isNull = true;
            while ($competency = mysqli_fetch_array($competencies)) {
                $isNull = false;
                echo "<tr><td>" . $competency["CName"] . "</td>";
                mysqli_data_seek($users, 0); //---------------------Can try save another call to users, by creating a variable upon first call and re-using it
                while ($user = mysqli_fetch_array($users)) {
                    displayUserRatings($conn, $competency["CompetencyID"], $user["UserID"]);
                }
                echo "</tr>"; //Finishes the row after entering all User data
            }
            if ($isNull) {
                $isNull = emptyArrayError($isNull);
            }

            ?>
            <br>

        <?php
        } //--End of role while loop


        //
        //All groups
        //
        while ($group = mysqli_fetch_array($groups)) {
        ?>

            <tr>
                <th class="tabletitle"><?php echo $group["GName"] ?></th>
            </tr>
            <tr>
                <th>-</th>
                <?php
                $users = UserGroupFromGroup($conn, $group["GroupID"]); //Gets all users in the current group
                while ($user = mysqli_fetch_array($users)) { //Gives a heading to all users
                    echo "<th>" . namePrint($_SESSION, $user) . "</th>";
                }
                ?>
            </tr>
            <?php
            $competencies = CompetencyGroupFromGroup($conn, $group["GroupID"]);
            $isNull = true;
            while ($competency = mysqli_fetch_array($competencies)) {
                $isNull = false;
                echo "<tr><td>" . $competency["CName"] . "</td>";
                mysqli_data_seek($users, 0);//---------------------Can try save another call to users, by creating a variable upon first call and re-using it
                while ($user = mysqli_fetch_array($users)) {
                    displayUserRatings($conn, $competency["CompetencyID"], $user["UserID"]);
                }
                echo "</tr>"; //Finishes the row after entering all User data
            }
            if ($isNull) {
                $isNull = emptyArrayError($isNull);
            }

            ?>
            <br>

    <?php
        } //--End of group while loop
    }
    ?>

    </table>
    <?php
    include_once 'footer.php';
    ?>