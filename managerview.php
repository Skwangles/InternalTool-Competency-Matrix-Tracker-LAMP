<?php
include_once 'header.php';
require_once 'includes/functions.inc.php';
require_once 'includes/dbh.inc.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] == "1") {
    header("location: index.php?error=invalidcall"); //If doesn't have the correct perms, kicks out
    exit();
}
include_once "admin-inwindow-controls.php"; //Gives the ability to change between edit mode and read-only mode
?>
<h2 class="centre">Your Departments</h2>
<?php
$groups = UserGroupFromUserWhereManager($conn, $_SESSION["userid"]);
while ($group = mysqli_fetch_array($groups)) {
?>

    <table border="1" class="centre">
        <tr>
            <th class="tabletitle" colspan="100%"><?php echo $group["GName"] ?></th>
        </tr>
        <tr>
            <th>-</th>
            <?php
            $Groupusers = UserGroupFromGroup($conn, $group["GroupID"]); //Gets all users in the current group
            while ($groupuser = mysqli_fetch_array($Groupusers)) { //Gives a heading to all users
                echo "<th>" . namePrint($_SESSION, $groupuser) . "</th>";
            }
            ?>
        </tr>
        <?php
        $competencies = CompetencyGroupFromGroup($conn, $group["GroupID"]);
        while ($competency = mysqli_fetch_array($competencies)) {
            echo "<tr><td>" . displayCompetencyName($competency) . "</td>";

            mysqli_data_seek($Groupusers, 0); //resets the pointer to 0
            while ($groupuser = mysqli_fetch_array($Groupusers)) {
                displayUserRatings($conn, $competency["CompetencyID"], $groupuser["UserID"]);
            }
            echo "</tr>";
        }

        mysqli_data_seek($Groupusers, 0); //Resets array pointer
        echo "<tr class=\"blank_row\"></tr><tr><td>--</td>"; //setup row, and offset cells by 1 to match layout
        while ($groupuser = mysqli_fetch_assoc($Groupusers)) {
            $summaryInfo = getUserSingleGroupSummary($conn, $groupuser["UserID"], $group["GroupID"]);
            echo "<th>" .
                formatPercent($summaryInfo) . "</th>"; //Gives the summary of each group
        }
        echo "</tr>";
        ?>
    </table>

<?php
} //--End of group while loop

//
//Table Key
//
displayNumberKey();


if ($_SESSION["role"] == 3) { //Admin can see all groups

    $order = makeUserOrder($conn);
    $assocOrder = makeUserOrderAssoc($conn);
    $idassoc = getUserInfo($conn);
    $groups = getGroups($conn);
    $Allusers = getUsers($conn);
    //
    //-------------------------------------------------------------------------------------Ordered table starts here for Admin View in the ManagerView page-----------------------------------------------------------
    //

?>


    <h2 class="centre">ALL groups & users</h2>

    <hr class="seperator">
    <table border="1" class="centre" style="empty-cells: hide;">
        <!-- Table with border of 1, and any empty cells are hidden -->
        <?php

        //
        //Individual User values
        //
        echo "<tr><th colspan=\"100%\" class=\"tabletitle\"><b>Individual User Competencies</b></th></tr>";

        //All the names in order
        printUserNames($order, $idassoc, $_SESSION);

        $competencies = mysqli_query($conn, "SELECT DISTINCT CompetencyID, CName, CDescription FROM individualusercompetencies JOIN competencies ON individualusercompetencies.Competencies = competencies.CompetencyID;"); //It is possible that DISTINCT means that two competencies with the same name are rejected, or same description (i.e. Blank)
        //The above query, gets all competencies which appear in the 
        if (mysqli_num_rows($competencies) <= 0) {
            emptyArrayError();
        } else {
            //
            //Prints all values - in order, with their values filled in. 
            //
            $memberUsers = mysqli_query($conn, "SELECT DISTINCT Users FROM individualusercompetencies");
            printValuesFromCompetency($conn, $competencies, $memberUsers, $order);
            //
            //---Prints individual competency User summaries
            //
            //--Array setup--
            $rowPrint = makeRowValuesFromUsers($order); //Gets the array which contains the userid=> current row rating
            mysqli_data_seek($Allusers, 0); //Ensures the array pointer is 0
            //--Filling the value array--
            while ($Alluser = mysqli_fetch_assoc($Allusers)) { //Runs through all users
                $rowPrint[$Alluser["UserID"]] = formatPercent(getInvidiualUserSummary($conn, $Alluser["UserID"])); //Adds to the user key the value they had 
            }
            //--Printing--
            echo "<tr><td=\"blank_td\"></td></tr>"; //Blank row to distinguish better
            echo "<tr><th>--</th>"; //Displays the competency - with Description if present
            displayRowFromArray($order, $rowPrint, true); //Displays the values of the current row
            echo "</tr>";

            echo "</tr><tr><td colspan=\"100%\" class=\"blank_td\"></td></tr>";
        }

        ?>
<!-- 
        <!-- Role User Values -->
        <tr>
            <th colspan="100%" class="tabletitle"><b>Roles</b></th>
        </tr>
        <?php
        //All the names in order
        printUserNames($order, $idassoc, $_SESSION);
        $roles = mysqli_query($conn, "SELECT * FROM roles");
        if (mysqli_num_rows($roles) <= 0) {
            emptyArrayError();
        } else {
            while ($role = mysqli_fetch_array($roles)) {
                //--Value retrieval--
                echo "<tr><th colspan=\"100%\" class=\"tableentry\">" . $role["RName"] . "</th></tr>";
                $roleComp = CompetencyRolesFromRoles($conn, $role["RoleID"]); //Gets all the users associated with this competency
                if (mysqli_num_rows($roleComp) <= 0) { //Checking for empty values - should NEVER be empty, as there is ALWAYS 3 roles - as of coding this.
                    emptyArrayError();
                } else {
                    $memberUsers = mysqli_query($conn, "SELECT UserID FROM users WHERE URole = '" . $role["RoleID"] . "';"); //Gets all users in that role to check
                    printValuesFromCompetency($conn, $roleComp, $memberUsers, $order);
                }
            }
            //
            //---Prints individual competency User summaries
            //
            //--Array setup--
            $rowPrint = makeRowValuesFromUsers($order); //Gets the array which contains the userid=> current row rating
            mysqli_data_seek($Allusers, 0); //Ensures the array pointer is 0
            //--Filling the value array--
            while ($Alluser = mysqli_fetch_assoc($Allusers)) { //Runs through all users
                $rowPrint[$Alluser["UserID"]] = formatPercent(getUserRoleSummary($conn, $Alluser["UserID"])); //Adds to the user key the value they had 
            }
            //--Printing--
            echo "<tr><td=\"blank_td\"></td></tr>"; //Blank row to distinguish better
            echo "<tr><th>--</th>"; //Displays the competency - with Description if present
            displayRowFromArray($order, $rowPrint, true); //Displays the values of the current row
            echo "</tr>";

            echo "</tr><tr><td colspan=\"100%\" class=\"blank_td\"></td></tr>"; //Black line to seperate areas
        }
        ?>

        <!-- Group User Values-->
        <tr>
            <th colspan="100%" class="tabletitle"><b>Groups</b></th>
        </tr>
        <?php
        //---The following is Largely reused code from the Roles area, except for specific areas where GroupID, CompetencyGroup and GName are referenced---


        //All the names in order
        printUserNames($order, $idassoc, $_SESSION);
        $groups = mysqli_query($conn, "SELECT * FROM groups");
        if (mysqli_num_rows($groups) <= 0) {
            emptyArrayError();
        } else {
            while ($group = mysqli_fetch_array($groups)) {
                //--Value retrieval--
                echo "<tr><th colspan=\"100%\" class=\"tableentry\">" . $group["GName"] . "</th></tr>";
                $groupCompetencies = CompetencyGroupFromGroup($conn, $group["GroupID"]); //Gets all the users associated with this competency
                if (mysqli_num_rows($groupCompetencies) <= 0) { //Checking for empty values - should NEVER be empty, as there is ALWAYS 3 roles - as of coding this.
                    emptyArrayError();
                } else {
                    $memberUsers = mysqli_query($conn, "SELECT Users FROM usergroups WHERE Groups = '" . $group["GroupID"] . "';");
                    printValuesFromCompetency($conn, $groupCompetencies, $memberUsers, $order);
                }
            }
            //
            //---Prints individual competency User summaries
            //
            //--Array setup--
            $rowPrint = makeRowValuesFromUsers($order); //Gets the array which contains the userid=> current row rating
            mysqli_data_seek($Allusers, 0); //Ensures the array pointer is 0
            //--Filling the value array--
            while ($Alluser = mysqli_fetch_assoc($Allusers)) { //Runs through all users
                $rowPrint[$Alluser["UserID"]] = formatPercent(getUserGroupSummary($conn, $Alluser["UserID"])); //Adds to the user key the value they had 
            }
            //--Printing--
            echo "<tr><td=\"blank_td\"></td></tr>"; //Blank row to distinguish better
            echo "<tr><th>--</th>"; //Displays the competency - with Description if present
            displayRowFromArray($order, $rowPrint, true); //Displays the values of the current row
            echo "</tr>";

            echo "</tr><tr><td colspan=\"100%\" class=\"blank_td\"></td></tr>"; //Black line to seperate areas
        }
        ?> -->

    </table>

<?php
}
include_once 'footer.php';
