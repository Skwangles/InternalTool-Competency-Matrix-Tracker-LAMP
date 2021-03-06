<?php

require_once 'dbh.inc.php';
require_once 'functions.inc.php';

require_once 'actiongroups.inc.php'; //Adds actions for check upon reload
require_once 'actioncompetencies.inc.php';
?>
<!--
//
// COMPETENCY/GROUP ASSIGN
//
-->
<!--Defines all groups, and what their assigned competencies are in a table-->
<section id="AddRemoveCG">
    <h3 class="centre"> Add Competencies To Groups, Roles and Users </h3>
    <form class="centre" action="admin.php" method="post">
        <table border="1" class="centre">
            <tr>
                <th>Select</th>
                <th>Name</th>
                <th>Competencies</th>
            </tr>
            <tr>
                <th class="tabletitle">Groups</th>
            </tr>
            <?php
            $groups = mysqli_query($conn, "SELECT * FROM groups");//prints each group and their associated competencies
            while ($row = mysqli_fetch_array($groups)) {
                echo "<tr>";
                echo "<td><label>" . "<input type=\"checkbox\" id=\"" . $row["GroupID"] . "-checkbox-groups-gcedit\" name=\"groups[]\" value=\"" . $row["GroupID"] . "\">" . "</label></td>"; //Creates Checkbox with group ID
                echo "<td><label for=\"" . $row["GroupID"] . "-checkbox-groups-gcedit\">" . $row["GName"] . "</label></td>";   //Gives name of the group
                

                $competencygroups = CompetencyGroupFromGroup($conn, $row["GroupID"]);
                echo "<td><ul>"; //Lists the competencies in a cell
                while ($competency = mysqli_fetch_array($competencygroups)) {
                    echo "<li style=\"text-align:left;\"><p>" . $competency["CName"] . "</p></li>"; //lists every associated competency
                }
                echo "</ul></td>";
                echo "</tr>";
            }
            ?>
            <tr>
                <th class="tabletitle">Roles</th>
            </tr>
            <?php

            //
            //Roles
            //
            $roles = mysqli_query($conn, "SELECT * FROM roles");//prints each role and their associated competencies
            while ($row = mysqli_fetch_array($roles)) {
                echo "<tr>";
                echo "<td><label>" . "<input type=\"checkbox\" name=\"roles[]\" id=\"" . $row["RoleID"] . "-checkbox-roles-gcedit\" value=\"" . $row["RoleID"] . "\">" . "</label></td>"; //Creates Checkbox with group ID
                echo "<td><label for=\"" . $row["RoleID"] . "-checkbox-roles-gcedit\">" . $row["RName"] . "</label></td>";   //Gives name of the group
                

                $competencyroles = CompetencyRolesFromRoles($conn, $row["RoleID"]);
                echo "<td><ul>"; //Lists the competencies in a cell
                while ($competency = mysqli_fetch_array($competencyroles)) {
                    echo "<li style=\"text-align:left;\"><p>" . $competency["CName"] . "</p></li>";
                }
                echo "</ul></td>";
                echo "</tr>";
            }
            ?>
            <tr>
                <th class="tabletitle">Individuals</th>
            </tr>
            <?php
            $users = mysqli_query($conn, "SELECT * FROM users");//prints each user and their associated competencies
            while ($row = mysqli_fetch_array($users)) {
                echo "<tr>";
                echo "<td><label>" . "<input type=\"checkbox\" name=\"users[]\" id=\"" . $row["UserID"] . "-checkbox-users-gcedit\" value=\"" . $row["UserID"] . "\">" . "</label></td>"; //Creates Checkbox with group ID
                echo "<td><label for=\"" . $row["UserID"] . "-checkbox-users-gcedit\">" . namePrint($_SESSION, $row) . "</label></td>";   //Gives name of the group - allows user to click on name to select checkbox
                

                $indUserCompetencies = IndUserCompetenciesFromUser($conn, $row["UserID"]);
                echo "<td><ul>"; //Lists the competencies in a cell
                while ($competency = mysqli_fetch_array($indUserCompetencies)) {
                    echo "<li style=\"text-align:left;\">" . $competency["CName"] . "</li>";
                }
                echo "</ul></td>";
                echo "</tr>";
            }
            ?>
        </table>


        <?php //list of all Competencies in a table
        $result = mysqli_query($conn, "SELECT * FROM competencies");

        echo "<table border='1' class=\"centre\">
            <tr>
            <th>Select</th>
            <th>Name</th>
            </tr>";

        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td><label>" . "<input type=\"checkbox\" id=\"" . $row["CompetencyID"] . "-checkbox-competency-gcedit\" name=\"competencies[]\" value=\"" . $row["CompetencyID"] . "\">" . "</label></td>"; //Creates Checkbox with competency name
            echo "<td><label for=\"" . $row["CompetencyID"] . "-checkbox-competency-gcedit\">" . $row["CName"] . "</label></td>"; //Gives name
            echo "</tr>";
        }
        echo "</table>";
        ?>
        <br>
        <button class="actionbuttons addbuttons" type="submit" name="addC">Add Selected Competencies To Groups</button>
        <button class="actionbuttons rembuttons" type="submit" name="removeC">Remove Selected Competencies From Groups</button>


    </form>
</section>
<hr class="seperator2">

<!--
//
// GROUP CREATION AND DELETION
//
-->
<section id="GroupManage">
    <h1 class="centre">Department/Group Editing</h1>
    <hr class="seperator2">
    <br>
    <h3 class="centre"> Create a group</h3>
    <form class="centre" action="" method="post">
        <input type="text" name="groupname" placeholder="Group Name" maxlength="30">
        <button class="actionbuttons addbuttons" type="submit" name="createG">Create Group</button>
    </form>
    <br>
    <hr class="seperator2">
    <br>
    <h3 class="centre">Current Departments/Groups</h3>
    <form class="centre" action="admin.php" method="post">
        <?php //list of Groups in a table
        $result = mysqli_query($conn, "SELECT * FROM groups");

        echo "<table border='1' class=\"centre\">
        
<tr>
<th>Select</th>
<th>Name</th>
</tr>";

        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td><label>" . "<input type=\"radio\" id=\"" . $row["GroupID"] . "-checkbox-individual-groups-gcedit\" name=\"groupradio\" value=\"" . $row["GroupID"] . "\">" . "</label></td>"; //Creates Checkbox
            echo "<td><label for=\"" . $row["GroupID"] . "-checkbox-individual-groups-gcedit\">" . $row["GName"] . "</label></td>"; //Gives name
            echo "</tr>";
        }
        echo "</table>";
        ?>
        <br>

        <h4 class="centre">Change Selected Group's Name</h4>
        <input type="text" name="gnameNewValue" placeholder="Group name" maxlength="30">
        <button class="centre actionbuttons addbuttons" type="submit" name="changeGNameG">Update Selected Group's Name</button>
        <br>
        <button class="dangerous centre" type="submit" name="permdeleteG">Delete Selected PERMANENTLY</button>
    </form>
</section>
<hr class="seperator2">
<!--
//
// COMPETENCY CREATION
//
-->
<section id="CompetencyManage">
    <h1 class="centre"><b>Competency Editing</b></h1>
    <hr class="seperator2">
    <br>
    <h3 class="centre"><b>Create a competency</b></h3>
    <!--Gets name of a competency and allows the addition of it to the database-->
    <form class="centre" action="" method="post">
        <input type="text" name="competency" placeholder="Competency Name" maxlength="50">
        <br>
        <textarea name="description" cols="40" rows="5" placeholder="Competency Description"></textarea>
        <button class="actionbuttons addbuttons" type="submit" name="createC">Create Competency</button>
    </form>

    <!--
//
// COMPETENCY Management
//
-->

    <br>
    <hr class="seperator2">
    <h3 class="centre"><b>Current Competencies</b></h3>
    <form class="centre" action="" method="post">
        <?php //list of Groups in a table
        $result = mysqli_query($conn, "SELECT * FROM competencies");

        echo "<table border='1' class=\"centre\">
                <tr>
                <th>Select</th>
                <th>Name</th>
                <th>Description</th>
                </tr>";

        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td><label>" . "<input type=\"radio\" id=\"" . $row["CompetencyID"] . "-checkbox-individual-competency-gcedit\" name=\"competencyradio\" value=\"" . $row["CompetencyID"] . "\">" . "</label></td>"; //Creates Checkbox
            echo "<td><label for=\"" . $row["CompetencyID"] . "-checkbox-individual-competency-gcedit\">" . $row['CName'] . "</td>"; //Gives name
            echo "<td style=\"word-wrap: break-word;
            max-width: 300px;\">" . $row["CDescription"] . "</td>"; //Limited width of the colum to avoid becoming unnessecarily wide
            echo "</tr>";
        }
        echo "</table>";
        ?>

        <h4 class="centre">Change Selected Competency's Name</h4>
        <input type="text" name="cnameNewValue" placeholder="Group name" maxlength="50">
        <button class="centre actionbuttons addbuttons" type="submit" name="changeCNameC">Update Competency Name</button>
        <h4 class="centre">Change Selected Competency's Description</h4>
        <textarea name="descriptionNewValue" cols="40" rows="5" placeholder="Competency Description"></textarea>
        <button class="centre actionbuttons addbuttons" type="submit" name="changeCDescriptionC">Update Competency Description</button>

        <br>
        <button class="dangerous centre" type="submit" name="permdeleteC">Delete Selected PERMANENTLY</button>
    </form>
</section>