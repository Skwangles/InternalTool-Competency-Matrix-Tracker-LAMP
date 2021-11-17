<?php
include_once 'header.php';
if (!isset($_SESSION["username"])) {
    header("location: login.php?error=notlogged"); //If not logged in, kicks out
    exit();
}

require_once "includes/dbh.inc.php";
require_once "includes/functions.inc.php";
?>
<table border="1">
    <tr>
        <th></th>
        <th><?php echo $_SESSION["name"]; ?></th>
    </tr>
    <?php
    $groups = UserGroupFromUser($conn, $_SESSION["userid"]);
    while ($group = mysqli_fetch_array($groups)) {
        echo "<tr><td><b>" . $group["GName"] . "</b></td></tr>";
        $competencies = CompetencyGroupFromGroup($conn, $group["GroupID"]);
        while ($competency = mysqli_fetch_array($competencies)) {
            echo "<tr><td>" . $competency["CName"] . "</td>";
            $Ratings = getUserRatings($conn, $_SESSION["userid"], $competency["CompetencyID"]);
            if ($Rating = mysqli_fetch_assoc($Ratings)) { //If there is a value in the array, get the first and only the first
                echo "<td>" . $Rating["Rating"] . "</td></tr>";
            }
        }
    }
    ?>
</table>
<?php
include_once 'footer.php';
?>