<?php
include_once 'header.php';

//----------Not sure if this is secure, this is for the loading of the group list, have a look at alternative ways------
require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "3") {
    header("location: index.php?error=invalidcall");
    exit();
}
?>

<form action="admin.php" method="post"><button type="submit" name="submit">Go back</button></form>
<?php
$allUsers = getUsers($conn);
while ($user = mysqli_fetch_array($allUsers)) {
?>
    <table border="1">
        <tr>
            <th><?php echo $user["UName"] ?></th>
        </tr>
        <tr>
            <th>Competency</th>
            <th>Groups Found</th>
            <th>Roles Found</th>
            <th>Value</th>
        </tr>
        <?php
        //Will list competencies by group
        $userComptencies = UserCompetenciesFromUser($conn, $user["UserID"]);
        while ($competencies = mysqli_fetch_array($userComptencies)) {
            echo $competencies;
            $groups = CompetencyGroupFromCompetency($conn, $competencies["CompetencyID"]); //Gets groups which contain the specific competency
            echo "<tr><td>" . $competencies["CName"] . "</td><td><ul>";
            while ($group = mysqli_fetch_array($userComptencies)) {
                if (mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM UserGroups WHERE Users = " . $user["UserID"] . " AND Groups = " . $competencies["GroupID"]))) { //only displays if user is in that group
                    echo "<li>" . $group["GName"] . "</li>";
                }
            }
            echo "</ul>";
        }
        ?>
    </table>
    <br>
<?php
}

?>
<?php
//Handles error tags
if (isset($_GET["error"])) {
    if ($_GET["error"] == "emptyinput") {
        echo "<p>Some fields are empty!</p>";
    } else if ($_GET["error"] == "stmtfailure") {
        echo "<p>Processing failure occurred!</p>";
    } else if ($_GET["error"] == "invaliduser") {
        echo "<p>Value must be alphanumeric!</p>";
    } else if ($_GET["error"] == "invalidcall") {
        echo "<p>Page was accessed incorrectly!</p>";
    } else if ($_GET["error"] == "none") {
        echo "<p>Successfully Added!</p>";
    } else if ($_GET["error"] == "usernametaken") {
        echo "<p>Username is already taken!</p>";
    } else {
        echo "<p>Something went wrong!</p>";
    }
}

include_once 'footer.php';
?>