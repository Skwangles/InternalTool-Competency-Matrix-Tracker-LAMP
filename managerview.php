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
$usersgroups = UserGroupFromUserWhereManager($conn, $_SESSION["userid"]);
while ($group = mysqli_fetch_array($usersgroups)) {
?>

    <table border="1" class="centre" >
        <tr>
            <th class="tabletitle" colspan="100%"><?php echo $group["GName"] ?></th>
        </tr>
        <tr>
            <th></th>
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

<?php
} //--End of group while loop

//
//Table Key
//
displayNumberKey();


if ($_SESSION["role"] == 3) { //Admin can see all groups

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
        displayRoleAndGroupValues($conn, $roles, $order, "SELECT UserID FROM users WHERE URole = ", 'CompetencyRolesFromRoles', 'getUserRoleSummary');
        echo "</tr><tr><td colspan=\"100%\" class=\"blank_td\"></td></tr>"; //Black line to seperate areas
        ?>

        <!-- Group User Values-->
        <tr>
            <th colspan="100%" class="tabletitle"><b>Groups</b></th>
        </tr>
        <?php
        //---The following is Largely reused code from the Roles area, except for specific areas where GroupID, CompetencyGroup and GName are referenced---


        //All the names in order
        printUserNames($order, $idassoc, $_SESSION);
        displayRoleAndGroupValues($conn, $Allgroups, $order, "SELECT Users FROM usergroups WHERE Groups = ", 'CompetencyGroupFromGroup', 'getUserGroupSummary');
        

        //Overall User Summary
        echo "</tr><tr><td colspan=\"100%\" class=\"tabletitle\"><b>Overall Summary</b></td></tr>"; //Black line to seperate areas
        printUserNames($order, $idassoc, $_SESSION);
        summaryRowPrint($conn, $Allusers, $order, 'getCompleteUserSummary');

        echo "</tr><tr><td colspan=\"100%\" class=\"blank_td\"></td></tr>"; //Black line to seperate areas
        ?>

    </table>

<?php
}

//
//Code Reuse Functions
//

function printValuesFromCompetency($conn, $compArray, $memberUsers, $order){//Accpets array of competencies to loop, users to check for (Only users as a whole a part of the joining table) and the order of names as a whole
    while ($competency = mysqli_fetch_assoc($compArray)) { //Adds a row 
        //--Filling the value array--
        $rowPrint = makeRowValuesFromUsers($order); //Gets the array which contains the userid=> current row rating
        mysqli_data_seek($memberUsers, 0);//Resets pointer to 0
        while ($memberUser = mysqli_fetch_row($memberUsers)) { //Gives a heading to all users        
            $rowPrint[$memberUser[0]] = displayUserRatings($conn, $competency["CompetencyID"], $memberUser[0]); //Adds to the user key the value they had 
        }
        //--Printing--
        echo "<tr><th>" . displayCompetencyName($competency) . "</th>"; //Displays the competency - with Description if present
        displayRowFromArray($order, $rowPrint); //Displays the values of the current row
        echo "</tr>";
    }
}

function summaryRowPrint($conn, $Allusers, $order, $summaryGetFunction){
    //--Array setup--
    $rowPrint = makeRowValuesFromUsers($order); //Gets the array which contains the userid=> current row rating
    mysqli_data_seek($Allusers, 0); //Ensures the array pointer is 0
    //--Filling the value array--
    while ($Alluser = mysqli_fetch_assoc($Allusers)) { //Runs through all users
        $rowPrint[$Alluser["UserID"]] = formatPercent($summaryGetFunction($conn, $Alluser["UserID"])); //Adds to the user key the value they had 
    }
    //--Printing--
    echo "<tr><td=\"blank_td\"></td></tr>"; //Blank row to distinguish better
    echo "<tr><th>--</th>"; //Displays the competency - with Description if present
    displayRowFromArray($order, $rowPrint, true); //Displays the values of the current row
    echo "</tr>";
    }

function displayRoleAndGroupValues($conn, $setOfItems, $order, $sqlstring, $CompetencyFunction, $summaryGetFunction ){//1. connection to the database, 2. array of items to loop (roles, or groups), 3. Name order, 4. sql which finds the members, 5. function for the joining table, 6. function which gets the summary values
    if (mysqli_num_rows($setOfItems) <= 0) {
        emptyArrayError();
    } else {
        while ($items = mysqli_fetch_row($setOfItems)) {
            //--Value retrieval--
            echo "<tr><th colspan=\"100%\" class=\"tableentry\">" . $items[1] . "</th></tr>";
            $competencies = $CompetencyFunction($conn, $items[0]); //Gets all the users associated with this group/role
            if (mysqli_num_rows($competencies) <= 0) { //Checking for empty values
                emptyArrayError();
            } else {
                $memberUsers = mysqli_query($conn, $sqlstring."'" . $items[0] . "';");
                printValuesFromCompetency($conn, $competencies, $memberUsers, $order);
            }
        }
        //
        //---Prints group User summaries
        //
        summaryRowPrint($conn, getUsers($conn), $order, $summaryGetFunction);

        echo "</tr><tr><td colspan=\"100%\" class=\"blank_td\"></td></tr>"; //Black line to seperate areas
    }
}



include_once 'footer.php';
