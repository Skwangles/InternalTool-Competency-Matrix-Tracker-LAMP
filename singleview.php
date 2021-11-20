<?php
include_once 'header.php';
if (!isset($_SESSION["username"])) {
    header("location: login.php?error=notlogged"); //If not logged in, kicks out
    exit();
}

require_once "includes/dbh.inc.php";
require_once "includes/functions.inc.php";
?>
<table border="1" class="centre">
    <tr>
        <th></th>
        <th><?php echo $_SESSION["name"]; ?></th>
    </tr>
    <?php
        $role = mysqli_fetch_assoc(RoleFromUser($conn, $_SESSION["userid"]));

        echo "<tr><td><b>" . $role["RName"] . "</b></td></tr>";
        $competencies = CompetencyRolesFromRoles($conn, $role["RoleID"]);
        while ($competency = mysqli_fetch_array($competencies)) {
            echo "<tr><td>" . $competency["CName"] . "</td>";
            displayUserRatings($conn, $competency, $_SESSION["userid"]);
    }

    ?>
    <?php
    $groups = UserGroupFromUser($conn, $_SESSION["userid"]);
    while ($group = mysqli_fetch_array($groups)) {
        echo "<tr><td><b>" . $group["GName"] . "</b></td></tr>";
        $competencies = CompetencyGroupFromGroup($conn, $group["GroupID"]);
        while ($competency = mysqli_fetch_array($competencies)) {
            echo "<tr><td>" . $competency["CName"] . "</td>";
            displayUserRatings($conn, $competency, $_SESSION["userid"]);
        }
    }
    ?>
</table>
<?php
include_once 'footer.php';
?>