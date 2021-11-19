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

    header("location: ../staffedit.php?error=none"); //Returns back to page
    exit();
}

function loginUser($conn, $username, $password) //Logs user in and sets session variables
{
    $uidExists = userExists($conn, $username); //checks if the username is in the database before processing
    if ($uidExists === false) { //sends back to login page
        header("location: ../login.php?error=incorrectlogin");
        exit();
    }

    $hashedPwd = $uidExists["UPassword"]; //uses returned database data to retrieve user data
    $checkPwd = password_verify($password, $hashedPwd); //checks two hashes

    if ($checkPwd == false) {
        header("location: ../login.php?error=incorrectlogin");
        exit();
    } elseif ($checkPwd === true) {
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
        header("location: ../groupedit.php?error=stmtfailure");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $name); //Binding prevents injection
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    header("location: ../groupedit.php?error=none"); //Returns back to page
    exit();
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
        header("location: ../competencyedit.php?error=stmtfailure");
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
        header("location: ../competencyedit.php?error=stmtfailure");
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
        header("location: ../competencyedit.php?error=stmtfailure");
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

function deleteUser($conn, $POST, $SESSION){
    $values = mysqli_query($conn, "SELECT * FROM users");
    foreach ($values as $value) {
        if (isset($POST[$value["UserID"]]) && $value["UserID"] != $SESSION["userid"]) { //Checks value exists, and is not the current user (Can't delete yourself)

            $sql = "DELETE FROM users WHERE UserID = ?"; //Sets up the add to database
            $stmt = mysqli_stmt_init($conn); //Initates database connection

            if (!mysqli_stmt_prepare($stmt, $sql)) { //if sql fails, rejects with an error
                header("location: ../staffedit.php?error=stmtfailure");
                exit();
            }

            mysqli_stmt_bind_param($stmt, "s", $value["UserID"]); //Binding prevents injection
            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);
        }
    }
}

function UserGroupFromGroup($conn, $group){
    return mysqli_query($conn, "SELECT
    `Users`.*
FROM
    `Users`
    JOIN `Usergroups` ON `Users`.`UserID` = `UserGroups`.`Users`
WHERE
    `UserGroups`.`Groups` = " . $group);
}

function UserGroupFromUser($conn,$user){
    return mysqli_query($conn, "SELECT
    `Groups`.*
FROM
    `Groups`
    JOIN `UserGroups` ON `Groups`.`GroupID` = `UserGroups`.`Groups`
WHERE
    `UserGroups`.`Users` = ".$user);//Gets all groups the user is a part of
}

function CompetencyGroupFromGroup($conn, $group){
  return mysqli_query($conn,"SELECT
`Competencies`.*
FROM
`Competencies`
JOIN `CompetencyGroups` ON `Competencies`.`CompetencyID` = `CompetencyGroups`.`Competencies`
WHERE
`CompetencyGroups`.`Groups` = " . $group);
}

function CompetencyGroupFromCompetency($conn, $competency){//Recieves competency ID and returns groups
    return mysqli_query($conn,"SELECT
  `Groups`.*
  FROM
  `Groups`
  JOIN `CompetencyGroups` ON `Groups`.`GroupID` = `CompetencyGroups`.`Groups`
  WHERE
  `CompetencyGroups`.`Competencies` = " . $competency);
  }

  function CompetencyRolesFromCompetency($conn, $competency){//Recieves competency ID and returns groups
    return mysqli_query($conn,"SELECT
  `Roles`.*
  FROM
  `Roles`
  JOIN `CompetencyRoles` ON `Roles`.`RoleID` = `CompetencyRoles`.`Roles`
  WHERE
  `CompetencyRoles`.`Competencies` = " . $competency);
  }

  function UserCompetenciesFromCompetency($conn, $competency){//Recieves competency ID and returns groups
    return mysqli_query($conn,"SELECT
  `users`.*
  FROM
  `users`
  JOIN `userCompetencies` ON `users`.`UserID` = `userCompetencies`.`users`
  WHERE
  `userCompetencies`.`Competencies` = " . $competency);
  }

  function UserCompetenciesFromUser($conn, $user){//Recieves competency ID and returns groups
    return mysqli_query($conn,"SELECT
  `Competencies`.*
  FROM
  `Competencies`
  JOIN `userCompetencies` ON `Competencies`.`CompetencyID` = `usercompetencies`.`Competencies`
  WHERE
  `usercompetencies`.`users` = " . $user);
  }

  function CompetencyRolesFromRoles($conn, $role){
    return mysqli_query($conn,"SELECT
  `Competencies`.*
  FROM
  `Competencies`
  JOIN `Competencyroles` ON `Competencies`.`CompetencyID` = `Competencyroles`.`Competencies`
  WHERE
  `Competencyroles`.`roles` = " . $role);
  }

function getUserRatings($conn, $user, $competency){
    return mysqli_query($conn, "SELECT Rating
    From UserCompetencies
    WHERE Users = " . $user . " AND Competencies = " . $competency);
}


function isCompInOtherGroup($conn, $comp, $group, $user){//Check if there exists any groups that the competency already exists in. 
    return mysqli_query($conn, "SELECT * FROM CompetencyGroups WHERE NOT Groups = ".$group." AND Competencies = ".$comp." AND EXISTS (SELECT * FROM UserGroups WHERE `UserGroups`.Groups = `competencygroups`.`Groups` AND `usergroups`.`Users` = ".$user.");");
}

function isCompInOtherRole($conn, $comp, $role, $user){//Check if there exists any roles that the competency already exists in. 
    return mysqli_query($conn, "SELECT * FROM CompetencyRoles WHERE NOT Roles = ".$role." AND Competencies = ".$comp." AND EXISTS (SELECT * FROM UserRoles WHERE `UserRoles`.Roles = `competencyroles`.`Roles` AND `userroles`.`Users` = ".$user.");");
}