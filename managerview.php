<?php

include_once 'includes/header.php';

if (!isset($_SESSION["role"]) || ($_SESSION["role"] != "2" && $_SESSION["isAdmin"] != "1")) {
    header("location: index.php?error=invalidcall"); //If doesn't have the correct perms, kicks out
    exit();
}

require_once 'includes/functions.inc.php';
require_once 'includes/dbh.inc.php';

include_once "includes/editmode-controls.php"; //Gives the ability to change between edit mode and read-only mode
include_once "includes/tablekey.php";

$order = makeUserOrder($conn);
?>
<hr class="seperator2">
<h2 class="centre">Your Departments</h2>
<hr class="seperator2">
<?php
$usersgroups = UserGroupFromUserWhereManager($conn, $_SESSION["userid"]);
if(mysqli_num_rows($usersgroups) <= 0){//Checks if there is atleast 1 group managed by that user
    echo "<p class=\"centre\">You are not the manager of any departments!</p>";
}
else{
while ($group = mysqli_fetch_array($usersgroups)) {
?>
    <div class="centre" style="overflow-x:auto; max-width:90%; ">
    <table border="1" class="centre" >
        <tr>
            <th class="tabletitle" colspan="100%"><?php echo $group["GName"] ?></th>
        </tr>
        <tr>
            <th></th>
            <?php
            $Groupusers = UserGroupFromGroup($conn, $group["GroupID"]); //Gets all users in the current group
            while ($groupuser = mysqli_fetch_array($Groupusers)) { //Gives a heading to all users - the order given by the database is assumed to match in the following Value fetching functions
                echo "<th>" . namePrint($_SESSION, $groupuser, true) . (IsManagerOfGroup($conn, $groupuser["UserID"], $group["GroupID"]) ? "(M)" : "") . "</th>";//Prints name out with correct formatting, adds (M) if manager - Could have adapted the PrintIsManagerCheckbox for this.
            }
            
            ?>
        </tr>
        <?php
        $competencies = CompetencyGroupFromGroup($conn, $group["GroupID"]);
        while ($competency = mysqli_fetch_array($competencies)) {
            echo "<tr><td>" . displayCompetencyName($competency) . "</td>";

            mysqli_data_seek($Groupusers, 0); //resets the pointer to 0
            while ($groupuser = mysqli_fetch_array($Groupusers)) {
                echo "<td>".displayUserRatings($conn, $competency["CompetencyID"], $groupuser["UserID"])."</td>";
            }
            echo "</tr>";
        }

        mysqli_data_seek($Groupusers, 0); //Resets array pointer
        echo "<tr class=\"blank_row\"></tr><tr><td></td>"; //setup row, and offset cells by 1 to match layout
        while ($groupuser = mysqli_fetch_assoc($Groupusers)) {
            $summaryInfo = getUserSingleGroupSummary($conn, $groupuser["UserID"], $group["GroupID"]);
            echo "<th>" .
                formatPercent($summaryInfo) . "</th>"; //Gives the summary of each group
        }
        echo "</tr>";
        ?>
    </table>
    </div>

<?php
}
}

if ($_SESSION["isAdmin"] == 1) { //Only admins can see ALL groups

    //Order specific arrays - 1. Contains the index=>userid, 2. contains the userid=>index, 3. contains Userid=>names
    $order = makeUserOrder($conn);
    $assocOrder = makeUserOrderAssoc($conn);
    $idassoc = getUserInfo($conn);

//Mysqli_result values with values
$Allgroups = getGroups($conn);
$Allusers = getUsers($conn);

    //
    //-------------------------------------------------------------------------------------Ordered table starts here for Admin View in the ManagerView page-----------------------------------------------------------
    //

?>
<br>
<br>
<hr class="seperator2">
    <h2 class="centre">ALL groups & users</h2>

    <hr class="seperator2">
    <div class="centre" style="overflow-x:auto; max-width:90%; z-index: 5; ">
    <table border="1" class="centre" style="empty-cells: hide;  ">
        <!-- Table with border of 1, and any empty cells are hidden -->
        <?php
        
        //Overall User Summary
        echo "</tr><tr><td colspan=\"100%\" class=\"tabletitle\"><b>Overall Summary</b></td></tr>"; //Black line to seperate areas
        printUserNames($order, $idassoc, $_SESSION);
        summaryRowPrint($conn, $Allusers, $order, 'getCompleteUserSummary');
        
         echo "</tr><tr><td colspan=\"100%\" class=\"blank_td\"></td></tr>"; //Black line to seperate areas
        echo "</tr><tr><td colspan=\"100%\" class=\"blank_td\"></td></tr>"; //Black line to seperate areas
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

            while ($competency = mysqli_fetch_assoc($competencies)) { //Adds a row 
                //--Filling the value array--
                $rowPrint = makeRowValuesFromUsers($order); //Gets the array which contains the userid=> current row rating
                $memberUsers = mysqli_query($conn, "SELECT Users FROM individualusercompetencies WHERE Competencies = '" . $competency["CompetencyID"] . "';");
                while ($memberUser = mysqli_fetch_row($memberUsers)) { //Gives a heading to all users        
                    $rowPrint[$memberUser[0]] = displayUserRatings($conn, $competency["CompetencyID"], $memberUser[0]); //Adds to the user key the value they had 
                }
                //--Printing--
                echo "<tr><th>" . displayCompetencyName($competency) . "</th>"; //Displays the competency - with Description if present
                displayRowFromArray($order, $rowPrint); //Displays the values of the current row
                echo "</tr>";
            }

            //
            //---Prints individual competency User summaries
            //
            summaryRowPrint($conn, $Allusers, $order, 'getInvidiualUserSummary');

            echo "</tr><tr><td colspan=\"100%\" class=\"blank_td\"></td></tr>";
        }

        ?>
        <!-- Role User Values -->
        <tr>
            <th colspan="100%" class="tabletitle"><b>Roles</b></th>
        </tr>
        <?php
        //All the names in order
        printUserNames($order, $idassoc, $_SESSION);
        $roles = mysqli_query($conn, "SELECT * FROM roles");
        displayRoleValues($conn, $roles, $order);
        echo "</tr><tr><td colspan=\"100%\" class=\"blank_td\"></td></tr>"; //Black line to seperate areas
        ?>

        <!-- Group User Values-->
        <tr>
            <th colspan="100%" class="tabletitle"><b>Groups</b></th>
        </tr>
        <?php


        //All the names in order
        printUserNames($order, $idassoc, $_SESSION);
        displayGroupValues($conn, $Allgroups, $order);

        

        ?>

    </table>
    </div>


<?php
}




include_once 'includes/footer.php';