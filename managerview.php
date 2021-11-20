<?php
include_once 'header.php';
require_once 'includes/functions.inc.php';
require_once 'includes/dbh.inc.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] == "1") {
    header("location: index.php?error=invalidcall"); //If doesn't have the correct perms, kicks out
    exit();
}

?>
<h2>--Your Departments--</h2>
<?php

$groups = UserGroupFromUser($conn, $_SESSION["userid"]);
while ($group = mysqli_fetch_array($groups)) {
?>

    <table border="1">
        <tr>
            <th><?php echo $group["GName"] ?></th>
        </tr>
        <tr>
            <th>-</th>
            <?php
            $users = UserGroupFromGroup($conn, $group["GroupID"]); //Gets all users in the current group
            while ($user = mysqli_fetch_array($users)) { //Gives a heading to all users
                echo "<th>" . $user["UName"] . "</th>";
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
            echo "</tr>"; //Finishes the row after entering all User data
        }

        ?>
    </table>

<?php
} //--End of group while loop
if ($_SESSION["role"] == 3) { //Admin can see all groups
    $groups = getGroups($conn);
?>

    <h2>--ALL groups--</h2>
    <?php
    while ($group = mysqli_fetch_array($groups)) {
    ?>

        <table border="1">
            <tr>
                <th><?php echo $group["GName"] ?></th>
            </tr>
            <tr>
                <th>-</th>
                <?php
                $users = UserGroupFromGroup($conn, $group["GroupID"]); //Gets all users in the current group
                while ($user = mysqli_fetch_array($users)) { //Gives a heading to all users
                    echo "<th>" . $user["UName"] . "</th>";
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
                echo "</tr>"; //Finishes the row after entering all User data
            }

            ?>
        </table>

<?php
    }
} //--End of group while loop
include_once 'footer.php';
?>