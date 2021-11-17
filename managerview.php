<?php
include_once 'header.php';
require_once 'includes/functions.inc.php';
require_once 'includes/dbh.inc.php';

if(!isset($_SESSION["role"]) || $_SESSION["role"] == "1"){
    header("location: index.php?error=invalidcall");//If doesn't have the correct perms, kicks out
    exit();
}
$groups = UserGroupFromUser($conn, $_SESSION["userid"]);//Gets groups user is in - then gets each user in those groups and creates a table using each of those users's values
while ($group = mysqli_fetch_array($groups)){
?>

<table border="1">
    <tr>
        <th><?php echo $group["GName"]?></th>
    </tr>
    <tr>
        <th>-</th>
    <?php 
        $users = UserGroupFromGroup($conn, $group["GroupID"]);//Gets all users in the current group
        while ($user = mysqli_fetch_array($users)){//Gives a heading to all users
            echo "<th>".$user["UName"]."</th>";
        }
    ?>
</tr>
    <?php
        $competencies = CompetencyGroupFromGroup($conn, $group["GroupID"]);
        while ($competency = mysqli_fetch_array($competencies)) {
            echo "<tr><td>" . $competency["CName"] . "</td>";
            $users = UserGroupFromGroup($conn, $group["GroupID"]);//---------------------Can try save another call to users, by creating a variable upon first call and re-using it
            while ($user = mysqli_fetch_array($users)){
            $Ratings = getUserRatings($conn, $user["UserID"], $competency["CompetencyID"]);
            if ($Rating = mysqli_fetch_assoc($Ratings)) { //If there is a value in the array, get the first and only the first
                echo "<td>" . $Rating["Rating"] . "</td>";
            }
            else{
                echo "<td></td>";//Gives empty
            }
        }
            echo "</tr>";//Finishes the row after entering all User data
        }
    
    ?>
</table>

<?php
}//--End of group while loop
include_once 'footer.php';
?>