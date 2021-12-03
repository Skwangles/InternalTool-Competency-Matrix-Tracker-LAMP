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
    echo mysqli_real_escape_string($conn, $username);
    $sql = "SELECT * FROM users WHERE uusername = '" . mysqli_real_escape_string($conn, $username) . "';";
    $resultData = mysqli_query($conn, $sql); //gets users

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return false;
    }
}

function createUser($conn, $name, $username, $password, $role) //Adds new user to database
{
    $hashedPwd = password_hash(mysqli_real_escape_string($conn,$password), PASSWORD_DEFAULT); //Stores passwords securely
    mysqli_query($conn, "INSERT INTO users (uname, uusername, upassword, urole) VALUES ('" . mysqli_real_escape_string($conn, $name) . "', '" . mysqli_real_escape_string($conn, $username) . "', '" . $hashedPwd . "', '" . mysqli_real_escape_string($conn, $role) . "');"); //Sets up the add to database
}

function updateSession($conn, $userid){
    session_start();
    $user = mysqli_query($conn, "SELECT * FROM users WHERE UserID = '".$userid."';");
    $uservariables = mysqli_fetch_assoc($user);
    $_SESSION["username"] = $uservariables["UUsername"];
    $_SESSION["name"] = $uservariables["UName"];
}

function changePassword($conn, $userid, $password) //Adds new user to database
{
    $hashedPwd = password_hash(mysqli_real_escape_string($conn,$password), PASSWORD_DEFAULT); //Stores passwords securely
   return mysqli_query($conn, "UPDATE users SET UPassword = '" . $hashedPwd . "' WHERE UserID = '" . $userid . "';"); //Sets up the add to database - returns false if failed
    
}

function changeUsername($conn, $userid, $username) //Adds new user to database
{
    if (userExists($conn, $username) == false && !invalidUser($username)) { //make sure that the username given is not already taken or is not applicable
        mysqli_query($conn, "UPDATE users SET UUsername = '" . mysqli_real_escape_string($conn, $username) . "' WHERE UserID = '" . $userid . "';"); //Sets up the add to database
        return true;
    }
    return false; //returns false if it failed
}

function changeName($conn, $userid, $name) //Adds new user to database
{
    mysqli_query($conn, "UPDATE users SET UName = '" . mysqli_real_escape_string($conn, $name) . "' WHERE UserID = '" . $userid . "';"); //Sets up the add to database
}

function changeGName($conn, $groupid, $name) //Adds new user to database
{
    mysqli_query($conn, "UPDATE groups SET GName = '" . mysqli_real_escape_string($conn, $name) . "' WHERE GroupID = '" . $groupid . "';"); //Sets up the add to database
}

function changeCName($conn, $competencyid, $name) //Adds new user to database
{
    mysqli_query($conn, "UPDATE competencies SET CName = '" . mysqli_real_escape_string($conn, $name) . "' WHERE CompetencyID = '" . $competencyid . "';"); //Sets up the add to database
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

            mysqli_query($conn, "DELETE FROM users WHERE UserID = " . $value["UserID"]); //Sets up the add to database

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
   return mysqli_query($conn, "INSERT INTO groups (gname) VALUES ('" . mysqli_real_escape_string($conn, $name) . "');");
}

function getGroupsFromName($conn, $name) //Returns the groupIDs of the different groups in the group table
{
    $resultData = mysqli_query($conn, "SELECT * FROM groups WHERE GName = '" . mysqli_real_escape_string($conn, $name)."';"); //gets users


    if ($group = mysqli_fetch_assoc($resultData)) {
        return $group;
        //If not empty, instead returns the database entry associated with group name
    } else {
        return false;
    }
}

//
//COMPETENCY PROCESSING
//
function getCompetenciesFromName($conn, $name) //Allows retrieval of id, from name
{
    $resultData = mysqli_query($conn, "SELECT * FROM competencies WHERE CName = '" . mysqli_real_escape_string($conn, $name)."';"); //gets users

    if ($group = mysqli_fetch_assoc($resultData)) {
        return $group;
        //If not empty, instead returns the database entry associated with username
    } else {
        return false;
    }
}

function addCompetency($conn, $name) //Adds name to competency table - does not check for duplicates
{
    $resultData = mysqli_query($conn, "INSERT IGNORE INTO competencies (cname) VALUES ('" . mysqli_real_escape_string($conn, $name) . "');"); //Sets up the add to database
}

function getCompetencies($conn) //Returns complete array of all departmanets
{
    $resultData = mysqli_query($conn, "SELECT * FROM competencies;"); //gets users

    if (mysqli_fetch_assoc($resultData)) { //Checks that there exists a single group, then returns the values
        return $resultData;
    } else {
        return false;
    }
}


//
//JOINING TABLE PROCESSING
//

function UserGroupFromGroup($conn, $groupid)
{
    return mysqli_query($conn, "SELECT
    `users`.*
FROM
    `users`
    JOIN `usergroups` ON `users`.`UserID` = `usergroups`.`Users`
WHERE
    `usergroups`.`Groups` = '" . $groupid."';");
}

function UserGroupFromUser($conn, $userid)
{
    return mysqli_query($conn, "SELECT
    `groups`.*
FROM
    `groups`
    JOIN `usergroups` ON `groups`.`GroupID` = `usergroups`.`Groups`
WHERE
    `usergroups`.`Users` = '" . $userid."';"); //Gets all groups the user is a part of
}

function IsManagerOfGroup($conn, $userid, $groupid)
{
    if (mysqli_fetch_row(mysqli_query($conn, "SELECT
    isManager FROM usergroups
WHERE
    Users = " . $userid . " AND Groups = " . $groupid))[0] == 1) return true; //if is manager, true
    return false;
}

function UserGroupFromUserWhereManager($conn, $userid)
{
    return mysqli_query($conn, "SELECT
    `groups`.*
FROM
    `groups`
    JOIN `usergroups` ON `groups`.`GroupID` = usergroups.Groups
WHERE
    usergroups.Users = " . $userid . " AND usergroups.isManager = '1'"); //Gets all groups the user is a part of
}

function CompetencyGroupFromGroup($conn, $groupid)
{
    return mysqli_query($conn, "SELECT
competencies.*
FROM
competencies
JOIN competencygroups ON competencies.CompetencyID = competencygroups.Competencies
WHERE
competencygroups.Groups = '" . $groupid."';");
}

function CompetencyGroupFromCompetency($conn, $competencyid)
{ //Recieves competency ID and returns groups
    return mysqli_query($conn, "SELECT
  groups.*
  FROM
  groups
  JOIN competencygroups ON groups.`GroupID` = competencygroups.`Groups`
  WHERE
  competencygroups.Competencies = '" . $competencyid. "';");
}

function CompetencyRolesFromCompetency($conn, $competencyid)
{ //Recieves competency ID and returns groups
    return mysqli_query($conn, "SELECT
  roles.*
  FROM
  roles
  JOIN competencyroles ON roles.RoleID = competencyroles.Roles
  WHERE
  competencyroles.Competencies = '" . $competencyid."';");
}
function CompetencyRolesFromRoles($conn, $roleid)
{
    return mysqli_query($conn, "SELECT
  competencies.*
  FROM
  competencies
  JOIN competencyroles ON competencies.CompetencyID = competencyroles.Competencies
  WHERE
  competencyroles.roles = '" . $roleid."';");
}

function UserCompetenciesFromCompetency($conn, $competencyid)
{ //Recieves competency ID and returns groups
    return mysqli_query($conn, "SELECT
  users.*
  FROM
  users
  JOIN usercompetencies ON users.UserID = usercompetencies.Users
  WHERE
  usercompetencies.Competencies = '" . $competencyid."';");
}

function UserCompetenciesFromUser($conn, $userid)
{ //Recieves competency ID and returns groups
    return mysqli_query($conn, "SELECT
  competencies.*
  FROM
  competencies
  JOIN usercompetencies ON competencies.CompetencyID = usercompetencies.Competencies
  WHERE
  usercompetencies.Users = '" . $userid."';");
}

function IndUserCompetenciesFromCompetency($conn, $competencyid)
{ //Recieves competency ID and returns groups
    return mysqli_query($conn, "SELECT
  users.*
  FROM
  users
  JOIN individualusercompetencies ON users.UserID = individualusercompetencies.users
  WHERE
  individualusercompetencies.Competencies = '" . $competencyid."';");
}

function IndUserCompetenciesFromUser($conn, $userid)
{ //Recieves competency ID and returns groups
    return mysqli_query($conn, "SELECT
  competencies.*
  FROM
  competencies
  JOIN `individualusercompetencies` ON competencies.CompetencyID = individualusercompetencies.Competencies
  WHERE
  individualusercompetencies.Users = '" . $userid."';");
}

//
//Roles & Ratings
//


function RoleFromUser($conn, $userid)
{ //Recieves competency ID and returns groups
    return mysqli_query($conn, "SELECT
  `roles`.*
  FROM
  `roles`
  JOIN `users` ON `roles`.`RoleID` = `users`.`URole`
  WHERE
  `users`.`UserID` = '" . $userid."';");
}

function getUserRatingsFromCompetency($conn, $userid, $competencyid)
{
    return mysqli_query($conn, "SELECT Rating
    From usercompetencies
    WHERE Users = '" . $userid . "' AND Competencies = '" . $competencyid."';");
}

function managerRoleSwitch($conn, $userid)
{ //For non-admin users, based on if they have any manager roles in any groups, role is alternated between 1 & 2
    if (mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM usergroups WHERE Users = " . $userid . " AND isManager = 1"))) { //If users are a manager, give manager global role, otherwise strip role
        mysqli_query($conn, "UPDATE users SET URole = 2 WHERE UserID = '" . $userid . "' AND URole = 1"); //If staff which has a mangerrole, give global manager role - ignore Admins
    } else {
        mysqli_query($conn, "UPDATE users SET URole = 1 WHERE UserID = '" . $userid . "' AND URole = 2"); //If manager without managing any managed groups, strip the manager role - ignore Admins
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

//
// Updating User Competency list
//

function updateUserCompetencies($conn, $userid) //Inefficent update process - but it works - can probably improve efficency of this
{
    $sqlString = "SELECT competencies.CompetencyID FROM `competencies` LEFT JOIN `competencygroups` ON `competencygroups`.`Competencies` = competencies.CompetencyID LEFT JOIN `competencyroles` ON `competencyroles`.`Competencies` = competencies.CompetencyID LEFT JOIN `individualusercompetencies` ON `individualusercompetencies`.`Competencies` = competencies.CompetencyID LEFT JOIN `usergroups` ON `usergroups`.`groups` = `competencygroups`.`Groups` LEFT JOIN `users` ON `users`.`UserID` = `usergroups`.`Users` WHERE (`users`.`UserID` = '".$userid."' OR `individualusercompetencies`.`Users` = '".$userid."' OR competencyroles.Roles = (SELECT URole FROM `users` WHERE `users`.`UserID` = '".$userid."'))";
    $updates = mysqli_query($conn, $sqlString);
    while ($competency = mysqli_fetch_row($updates)) {
        if (mysqli_fetch_row(mysqli_query($conn, "SELECT * FROM usercompetencies WHERE Users = '" . $userid . "' AND Competencies = '" . $competency[0]."';")) == false) { //Checks if the item already exists in the table
            mysqli_query($conn, "INSERT INTO usercompetencies (Users, Competencies) VALUES ('" . $userid . "', '" . $competency[0] . "')");
        }
    }
    $userCompetencies = UserCompetenciesFromUser($conn, $userid);
    while ($competency = mysqli_fetch_assoc($userCompetencies)) {
        mysqli_query($conn, "DELETE FROM `usercompetencies` WHERE `usercompetencies`.`Users` = '" . $userid . "' AND `usercompetencies`.`Competencies` = '" . $competency["CompetencyID"] . "' AND NOT EXISTS (" . $sqlString . " AND `competencies`.`CompetencyID` = '" . $competency["CompetencyID"] . "')"); //checks if it exists in one of the joining tables, otherwise deletes
    }
}


//
//User Competency Checking
//

function isCompInOtherGroup($conn, $comp, $group, $user)
{ //Check if there exists any groups that the competency already exists in. 
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM competencygroups WHERE NOT Groups = '".$group."' AND Competencies = '".$comp."' AND EXISTS (SELECT * FROM usergroups WHERE usergroups.Groups = `competencygroups`.`Groups` AND `usergroups`.`Users` = '".$user."')")); //Check if the item exists in another competency group, where the group is different to the one being removed, and the user must exist in that group that may have that comeptency. 
}

function isCompInRole($conn, $comp, $role, $user)
{ //Check if there exists any roles that the competency already exists in. 
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM competencyroles WHERE roles = '" . $role . "' AND competencies = '" . $comp . "' AND EXISTS (SELECT * FROM users WHERE `UserID` = '" . $user . "' AND URole = '" . $role . "');")); //There is a competency which is associated witht he role, and the user exists being part of that role
}

function isCompInIndividualUser($conn, $comp, $userid)
{ //Check if there exists any roles that the competency already exists in. 
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM individualusercompetencies WHERE Users = '" . $userid . "' AND Competencies = '" . $comp."';")); //Check if the competency exists in the individual users table
}