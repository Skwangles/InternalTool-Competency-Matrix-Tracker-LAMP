<?php
function emptyInputs($username, $password, $name) //Returns if a single value is empty
{
    if (empty($name) || empty($username) || empty($password)) { //Checks the values aren't empty
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function invalidUser($username) //Determines if alphanumeric
{
    return !preg_match("/^[a-zA-Z0-9]*$/", $username); //Determines if alphanumerics
}

//
//USER PROCESSING
//
function userExists($conn, $username) //Confirms if a matching username is found in the database - returns details if found
{
    $sql = "SELECT * FROM users WHERE uusername = ?;"; //gets users
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) { //if sql fails, rejects with error
        header("location: ../login.php?error=stmtfailure");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $username); //applies username to sql query, prevents injection
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row; //If not empty, instead returns the database entry associated with username
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}

function createUser($conn, $name, $username, $password, $role) //Adds new user to database
{
    $sql = "INSERT INTO users (uname, uusername, upassword, urole) VALUES (?, ?, ?, ?);"; //Sets up the add to database
    $stmt = mysqli_stmt_init($conn); //Initates database connection

    if (!mysqli_stmt_prepare($stmt, $sql)) { //if sql fails, rejects with an error
        header("location: ../staffedit.php?error=stmtfailure");
        exit();
    }

    $hashedPwd = password_hash($password, PASSWORD_DEFAULT); //Stores passwords securely
    mysqli_stmt_bind_param($stmt, "ssss", $name, $username, $hashedPwd, $role); //Binding prevents injection
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

}

function loginUser($conn, $username, $password) //Logs user in and sets session variables
{
    $uidExists = userExists($conn, $username); //checks if the username is in the database before processing
    if ($uidExists == false) { //sends back to login page
        header("location: ../login.php?error=incorrectlogin");
        exit();
    }

    $hashedPwd = $uidExists["UPassword"]; //uses returned database data to retrieve user data
    $checkPwd = password_verify($password, $hashedPwd); //checks two hashes

    if ($checkPwd == false) {
        header("location: ../login.php?error=incorrectlogin");
        exit();
    } elseif ($checkPwd == true) {
        session_start(); //allows saving session variables
        $_SESSION["userid"] = $uidExists["UserID"];
        $_SESSION["username"] = $uidExists["UUsername"];
        $_SESSION["name"] = $uidExists["UName"];
        $_SESSION["role"] = $uidExists["URole"];
    }
}

function getUsers($conn) //Returns the names of the different groups in the group table
{
    return mysqli_query($conn, "SELECT * FROM users;"); //gets users
}

function deleteUser($conn, $POST, $SESSION)
{
    $values = mysqli_query($conn, "SELECT * FROM users");
    foreach ($values as $value) {
        if (isset($POST[$value["UserID"]]) && $value["UserID"] != $SESSION["userid"]) { //Checks value exists, and is not the current user (Can't delete yourself)

            $sql = "DELETE FROM users WHERE UserID = ?"; //Sets up the add to database
            $stmt = mysqli_stmt_init($conn); //Initates database connection

            if (!mysqli_stmt_prepare($stmt, $sql)) { //if sql fails, rejects with an error
                header("location: ../staffedit.php?error=stmtfailure#StaffManage");
                exit();
            }

            mysqli_stmt_bind_param($stmt, "s", $value["UserID"]); //Binding prevents injection
            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);
        }
    }
}

//
//GROUP PROCESSING
//
function getGroups($conn) //Returns complete array of all departmanets
{
    return mysqli_query($conn, "SELECT * FROM groups;"); //gets users
}

function addGroup($conn, $name) //Adds Group to database
{
    $sql = "INSERT INTO groups (gname) VALUES (?);";
    $stmt = mysqli_stmt_init($conn); //Initates database connection

    if (!mysqli_stmt_prepare($stmt, $sql)) { //if sql fails, rejects with an error
        header("location: ../gcedit.php?error=stmtfailure#GroupManage");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $name); //Binding prevents injection
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

function getGroupsFromName($conn, $name) //Returns the groupIDs of the different groups in the group table
{
    $sql = "SELECT * FROM groups WHERE GName = ?;"; //gets users
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) { //if sql fails, rejects with error
        header("location: ../admin.php?error=stmtfailure");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    echo var_dump($resultData);

    if ($group = mysqli_fetch_assoc($resultData)) {
        return $group;
        //If not empty, instead returns the database entry associated with group name
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}

//
//COMPETENCY PROCESSING
//
function getCompetenciesFromName($conn, $name) //Allows retrieval of id, from name
{
    $sql = "SELECT * FROM Competencies WHERE CName = ?;"; //gets users
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) { //if sql fails, rejects with error
        header("location: ../gcedit.php?error=stmtfailure#AddRemoveCG");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($group = mysqli_fetch_assoc($resultData)) {
        return $group;
        //If not empty, instead returns the database entry associated with username
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}

function addCompetency($conn, $name) //Adds name to competency table - does not check for duplicates
{
    $sql = "INSERT IGNORE INTO competencies (cname) VALUES (?);"; //Sets up the add to database
    $stmt = mysqli_stmt_init($conn); //Initates database connection

    if (!mysqli_stmt_prepare($stmt, $sql)) { //if sql fails, rejects with an error
        header("location: ../gcedit.php?error=stmtfailure#CompetencyManage");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $name); //Binding prevents injection
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

function getCompetencies($conn) //Returns complete array of all departmanets
{
    $sql = "SELECT * FROM competencies;"; //gets users
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) { //if sql fails, rejects with error
        header("location: ../gcedit.php?error=stmtfailure#CompetencyManage");
        exit();
    }

    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if (mysqli_fetch_assoc($resultData)) { //Checks that there exists a single group, then returns the values
        return $resultData;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}

function addCompetenciesAssociated($conn, $userid, $competencies)
{
    while ($competency = mysqli_fetch_assoc($competencies)) { //goes through list of competencies, finds ones which do not exist - and adds
        if (mysqli_query($conn, "SELECT * FROM UserCompetencies WHERE users = " . $userid . " AND competencies = " . $competency["CompetencyID"]) == false) { //Checks if the item already exists in the table
            mysqli_query($conn, "INSERT INTO UserCompetencies (Users, Competencies) VALUES (" . $userid . ", " . $competency["CompetencyID"] . ")");
        }
    }
}

function removeCompetenciesAssociatedWithGroup($conn, $competenciesWithGroup, $userid, $groupid)
{
    $userRole = mysqli_fetch_row(mysqli_query($conn, "SELECT URole FROM Users WHERE UserID = " . $userid))[0];
    while ($competency = mysqli_fetch_array($competenciesWithGroup)) { //Deletes competencies associated with the group

        if (isCompInOtherGroup($conn, $competency["CompetencyID"], $groupid, $userid) == false && isCompInRole($conn, $competency["CompetencyID"], $userRole, $userid) == false && isCompInIndividualUser($conn, $competency["ComeptencyID"], $userid) == false) { //Checks if competency is associated with the user individually, or another group/role before removal
            mysqli_query($conn, "DELETE FROM UserCompetencies WHERE Competencies = " . $competency["CompetencyID"] . " AND Users = " . $userid);
        }
    }
}

function removeCompetenciesAssociatedWithRole($conn, $competenciesWithRole, $userid, $roleid)
{
    while ($competency = mysqli_fetch_array($competenciesWithRole)) { //Deletes competencies associated with the group
        if (isCompInOtherGroup($conn, $competency["CompetencyID"], 0, $userid) == false && isCompInRole($conn, $competency["CompetencyID"], $roleid, $userid) != false && isCompInIndividualUser($conn, $competency["ComeptencyID"], $userid) == false) { //Checks if competency is associated with the user individually, or another group/role before removal
            mysqli_query($conn, "DELETE FROM UserCompetencies WHERE Competencies = " . $competency["CompetencyID"] . " AND Users = " . $userid);
        }
    }
}

function removeCompetenciesAssociatedWithUser($conn, $competenciesWithUser, $userid)
{
    $userRole = mysqli_fetch_row(mysqli_query($conn, "SELECT URole FROM Users WHERE UserID = " . $userid))[0];
    while ($competency = mysqli_fetch_array($competenciesWithUser)) { //Deletes competencies associated with the group
        if (isCompInOtherGroup($conn, $competency["CompetencyID"], 0, $userid) == false && isCompInRole($conn, $competency["CompetencyID"], $userRole, $userid) == false) { //Checks if competency is associated with another group/role before removal
            mysqli_query($conn, "DELETE FROM UserCompetencies WHERE Competencies = " . $competency["CompetencyID"] . " AND Users = " . $userid);
        }
    }
}

//
//User Competency Checking
//

function isCompInOtherGroup($conn, $comp, $group, $user)
{ //Check if there exists any groups that the competency already exists in. 
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM CompetencyGroups WHERE NOT Groups = " . $group . " AND Competencies = " . $comp . " AND EXISTS (SELECT * FROM UserGroups WHERE `UserGroups`.Groups = `competencygroups`.`Groups` AND `usergroups`.`Users` = " . $user . ");")); //Check if the item exists in another competency group, where the group is different to the one being removed, and the user must exist in that group that may have that comeptency. 
}

function isCompInRole($conn, $comp, $role, $user)
{ //Check if there exists any roles that the competency already exists in. 
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM CompetencyRoles WHERE Roles = " . $role . " AND Competencies = " . $comp . " AND EXISTS (SELECT * FROM Users WHERE `UserID` = " . $user . " AND URole = " . $role . ");")); //There is a competency which is associated witht he role, and the user exists being part of that role
}

function isCompInIndividualUser($conn, $comp, $userid)
{ //Check if there exists any roles that the competency already exists in. 
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM IndividualUserCompetencies WHERE Users = " . $userid . " AND Competencies = " . $comp)); //Check if the competency exists in the individual users table
}

//
//JOINING TABLE PROCESSING
//

function UserGroupFromGroup($conn, $groupid)
{
    return mysqli_query($conn, "SELECT
    `Users`.*
FROM
    `Users`
    JOIN `Usergroups` ON `Users`.`UserID` = `UserGroups`.`Users`
WHERE
    `UserGroups`.`Groups` = " . $groupid);
}

function UserGroupFromUser($conn, $userid)
{
    return mysqli_query($conn, "SELECT
    `Groups`.*
FROM
    `Groups`
    JOIN `UserGroups` ON `Groups`.`GroupID` = `UserGroups`.`Groups`
WHERE
    `UserGroups`.`Users` = " . $userid); //Gets all groups the user is a part of
}

function IsManagerOfGroup($conn, $userid, $groupid)
{
    if (mysqli_fetch_row(mysqli_query($conn, "SELECT
    isManager FROM UserGroups
WHERE
    Users = " . $userid . " AND Groups = " . $groupid))[0] == 1) return true; //if is manager, true
    return false;
}

function UserGroupFromUserWhereManager($conn, $userid)
{
    return mysqli_query($conn, "SELECT
    `Groups`.*
FROM
    `Groups`
    JOIN `UserGroups` ON `Groups`.`GroupID` = `UserGroups`.`Groups`
WHERE
    `UserGroups`.`Users` = " . $userid . " AND `UserGroups`.`isManager` = 1"); //Gets all groups the user is a part of
}

function CompetencyGroupFromGroup($conn, $groupid)
{
    return mysqli_query($conn, "SELECT
`Competencies`.*
FROM
`Competencies`
JOIN `CompetencyGroups` ON `Competencies`.`CompetencyID` = `CompetencyGroups`.`Competencies`
WHERE
`CompetencyGroups`.`Groups` = " . $groupid);
}

function CompetencyGroupFromCompetency($conn, $competencyid)
{ //Recieves competency ID and returns groups
    return mysqli_query($conn, "SELECT
  `Groups`.*
  FROM
  `Groups`
  JOIN `CompetencyGroups` ON `Groups`.`GroupID` = `CompetencyGroups`.`Groups`
  WHERE
  `CompetencyGroups`.`Competencies` = " . $competencyid);
}

function CompetencyRolesFromCompetency($conn, $competencyid)
{ //Recieves competency ID and returns groups
    return mysqli_query($conn, "SELECT
  `Roles`.*
  FROM
  `Roles`
  JOIN `CompetencyRoles` ON `Roles`.`RoleID` = `CompetencyRoles`.`Roles`
  WHERE
  `CompetencyRoles`.`Competencies` = " . $competencyid);
}
function CompetencyRolesFromRoles($conn, $roleid)
{
    return mysqli_query($conn, "SELECT
  `Competencies`.*
  FROM
  `Competencies`
  JOIN `Competencyroles` ON `Competencies`.`CompetencyID` = `Competencyroles`.`Competencies`
  WHERE
  `Competencyroles`.`roles` = " . $roleid);
}

function UserCompetenciesFromCompetency($conn, $competencyid)
{ //Recieves competency ID and returns groups
    return mysqli_query($conn, "SELECT
  `users`.*
  FROM
  `users`
  JOIN `userCompetencies` ON `users`.`UserID` = `userCompetencies`.`users`
  WHERE
  `userCompetencies`.`Competencies` = " . $competencyid);
}

function UserCompetenciesFromUser($conn, $userid)
{ //Recieves competency ID and returns groups
    return mysqli_query($conn, "SELECT
  `Competencies`.*
  FROM
  `Competencies`
  JOIN `userCompetencies` ON `Competencies`.`CompetencyID` = `usercompetencies`.`Competencies`
  WHERE
  `usercompetencies`.`users` = " . $userid);
}

function IndUserCompetenciesFromCompetency($conn, $competencyid)
{ //Recieves competency ID and returns groups
    return mysqli_query($conn, "SELECT
  `users`.*
  FROM
  `users`
  JOIN `individualuserCompetencies` ON `users`.`UserID` = `individualuserCompetencies`.`users`
  WHERE
  `individualuserCompetencies`.`Competencies` = " . $competencyid);
}

function IndUserCompetenciesFromUser($conn, $userid)
{ //Recieves competency ID and returns groups
    return mysqli_query($conn, "SELECT
  `Competencies`.*
  FROM
  `Competencies`
  JOIN `individualuserCompetencies` ON `Competencies`.`CompetencyID` = `individualusercompetencies`.`Competencies`
  WHERE
  `individualusercompetencies`.`users` = " . $userid);
}

//
//Roles & Ratings
//

function UpdateRoleCompetencies($conn, $userid, $role, $oldRole)
{
    addCompetenciesAssociated($conn, $userid, CompetencyRolesFromRoles($conn, $role));
    removeCompetenciesAssociatedWithRole($conn, CompetencyRolesFromRoles($conn, $oldRole), $userid, $oldRole); //removed after the change to ensure that any values shared between roles are preserved
}

function RoleFromUser($conn, $userid)
{ //Recieves competency ID and returns groups
    return mysqli_query($conn, "SELECT
  `Roles`.*
  FROM
  `Roles`
  JOIN `users` ON `Roles`.`RoleID` = `users`.`URole`
  WHERE
  `Users`.`UserID` = " . $userid);
}

function getUserRatingsFromCompetency($conn, $userid, $competencyid)
{
    return mysqli_query($conn, "SELECT Rating
    From UserCompetencies
    WHERE Users = " . $userid . " AND Competencies = " . $competencyid);
}

function managerRoleSwitch($conn, $userid)
{ //For non-admin users, based on if they have any manager roles in any groups, role is alternated between 1 & 2
    if (mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM UserGroups WHERE Users = " . $userid . " AND isManager = 1"))) { //If users are a manager, give manager global role, otherwise strip role
        $oldRole = mysqli_query($conn, "SELECT * FROM Users WHERE UserID = " . $userid . " AND URole = 1");
        mysqli_query($conn, "UPDATE Users SET URole = 2 WHERE UserID = " . $userid . " AND URole = 1"); //If staff which has a mangerrole, give global manager role - ignore Admins
        if (mysqli_fetch_row($oldRole)["URole"] == 1) { //Doesn't update Admins
            UpdateRoleCompetencies($conn, $userid, 2, 1); //updates competencies associated with the user based on the role
        }
    } else {
        $oldRole = mysqli_query($conn, "SELECT * WHERE UserID = " . $userid . " AND URole = 2");
        mysqli_query($conn, "UPDATE Users SET URole = 1 WHERE UserID = " . $userid . " AND URole = 2"); //If manager without managing any managed groups, strip the manager role - ignore Admins
        if (mysqli_fetch_row($oldRole)["URole"] == 2) { //Doesn't update admins
            UpdateRoleCompetencies($conn, $userid, 1, 2); //updates competencies associated with the user based on the role
        }
    }
}

//
//Echo/Formatting Based Functions
//
function displayUserRatings($conn, $competency, $user)
{
    //
    //----If you want to get rid of the "Not trained" options, 1. Remove it from the select in the uservalues, 1. Change the default value of a competency to be 1/any
    //
    $ratingNames = array("Not Trained", "Trained", "Can demonstrate competency", "Can train others");
    $Ratings = getUserRatingsFromCompetency($conn, $user, $competency["CompetencyID"]);
    if ($Rating = mysqli_fetch_assoc($Ratings)) { //If there is a value in the array, get the first and only the first
        echo "<td>" . $ratingNames[$Rating["Rating"]] . "</td>"; //Gives the text versions of the names
    } else {
        echo "<td>N/A</td>"; //Gives empty
    }
    echo "</tr>";
}

function emptyArrayError($isNull)
{
    if ($isNull) {
        echo "<tr><td>None Found</td><td>-</td></tr>";
        return $isNull;
    } else {
        $isNull = true;
        return $isNull;
    }
}

function namePrint($sesh, $user)
{
    return $user["UName"] . ($user["UserID"] == $sesh["userid"] ? " (You)" : "");
}

function updateUserCompetencies($conn, $userid){
    $updates = mysqli_query($conn, "SELECT `competencies`.`competencyID` FROM `competencies` JOIN `usercompetencies` ON `usercompetencies`.`Competencies`= `competencies`.`CompetencyID` LEFT JOIN `competencygroups` ON `competencygroups`.`Competencies` = `competencies`.`CompetencyID` LEFT JOIN `competencyroles` ON `competencyroles`.`Competencies` = `competencies`.`CompetencyID` LEFT JOIN `individualusercompetencies` ON `individualusercompetencies`.`Competencies` = `competencies`.`CompetencyID` WHERE `usercompetencies`.`Users` = ".$userid."; ");
    $firstLoop = $updates;
    while($row = mysqli_fetch_row($firstLoop)){
            
    }
    
    
    }