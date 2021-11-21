<?php
include_once 'header.php';
require_once 'includes/functions.inc.php';
require_once 'includes/dbh.inc.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] == "1") {
    header("location: index.php?error=invalidcall"); //If doesn't have the correct perms, kicks out
    exit();
}

?>
<h2 class="centre">--Your Departments--</h2>
<?php
$groups = UserGroupFromUserWhereManager($conn, $_SESSION["userid"]);
while ($group = mysqli_fetch_array($groups)) {
?>

    <table border="1" class="centre">
        <tr>
            <th><?php echo $group["GName"] ?></th>
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
                displayUserRatings($conn, $competency, $user["UserID"]);
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

    <h2 class="centre">--ALL groups--</h2>
    <table border="1" class="centre">
        <?php
        echo "<tr><th><b>Individual User Competencies</b></th></tr>";
        //Individual User values
        $users = getUsers($conn);
        while ($user = mysqli_fetch_assoc($users)) {
            echo "<tr><th>" . $user["UName"] . "</th></tr>";
            $competencies = IndUserCompetenciesFromUser($conn, $_SESSION["userid"]);
            $isNull = true; //Determines if the while ended before the first loop
            while ($competency = mysqli_fetch_array($competencies)) {
                $isNull = false;
                echo "<tr><td>" . $competency["CName"] . "</td>";
                displayUserRatings($conn, $competency, $_SESSION["userid"]);
                echo "</tr>";
            }
            if ($isNull) {
                $isNull = emptyArrayError($isNull); //Prints out the "No Competency Found" tile, done like this incase wanting to change the format
            } 
        }
        ?>
    </table>
    <?php
    while ($group = mysqli_fetch_array($groups)) {
    ?>
        <table border="1" class="centre">
            <tr>
                <th><?php echo $group["GName"] ?></th>
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
                $isNull = false;
                echo "<tr><td>" . $competency["CName"] . "</td>";
                $users = UserGroupFromGroup($conn, $group["GroupID"]); //---------------------Can try save another call to users, by creating a variable upon first call and re-using it
                while ($user = mysqli_fetch_array($users)) {
                    displayUserRatings($conn, $competency, $user["UserID"]);
                }
                 echo "</tr>"; //Finishes the row after entering all User data
            }
            if ($isNull) {
                $isNull = emptyArrayError($isNull);
            } 

            ?>
        </table>

<?php
    }
} //--End of group while loop
include_once 'footer.php';
?>