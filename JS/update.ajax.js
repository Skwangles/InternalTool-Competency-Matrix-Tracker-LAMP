function updateValue(userid, competencyid, value) { //Updates the user values when changing the textbox
    $.ajax({
        type: 'POST',
        url: "ajax/values.ajax.php",
        data: { UserID: userid, CompetencyID: competencyid, Value: value },
        dataType: 'JSON',
        success: function(response) {
            //console.log(response);
            var status = response.status;
            if (status == "ok") {
                var res = response.uv;
                document.getElementById(userid + "-" + competencyid + "-tb").value = res;
                return; //updates the value to what the internal value is. 
            } else {
                if (document.getElementById(callerid).value != "") { //If not empty, the input must be false
                    document.getElementById(callerid).value = "";
                }
                console.log("AJAX Success, Processing failure");
            }
        },
        error: function(xhr, error) {}
    });
}

function switchEditMode() { //Updates the edit mode for Admins from edit everything, to just READ-ONLY
    $.ajax({
        type: 'POST',
        url: "ajax/editmode.ajax.php",
        dataType: 'JSON',
        success: function(response) {
            if (response.status == "ok") {
                window.location.reload(); //updates all values on the page
            }
        },
        error: function(xhr, error) {}
    });

}

function updateManager(userid, groupid, value) {

    $.ajax({
        type: 'POST',
        url: "ajax/manager.ajax.php",
        data: { UserID: userid, GroupID: groupid, Value: (value.checked ? 1 : 0) },
        dataType: 'JSON',
        success: function(response) {
            var status = response.status;
            var value = response.Value;
            if (status == "ok") {
                var id = userid + "-" + groupid + "-select";
                document.getElementById(id).checked = (value == "1" ? true : false); //updates the value to what the internal value is. 
            }
            return;
        },
        error: function(xhr, error) {}
    });
}


function UpdateUserValuesFromForm(formID) {

    var datastring = $("#" + formID).serialize();
    $.ajax({
        type: 'POST',
        url: 'ajax/updateuserinfo.ajax.php',
        data: datastring,
        dataType: 'JSON',
        success: function(response) {
            console.log(response);
            var status = response.status;
            if (status == "ok") {

            }
            return;
        },
        error: function(xhr, error) {
            // showError("Manager update failed, please try again later...");
            console.log(error);
            console.log(xhr);
        }
    });
    location.reload();
    window.location.href = location.protocol + '//' + location.host + location.pathname + "#individualusers"; //reloads the page, with the desired setting window still in focus
}

function deleteUser(userID) {
    $.ajax({
        type: 'POST',
        url: 'ajax/deleteuser.ajax.php',
        data: { UserID: userID },
        dataType: 'JSON',
        success: function(response) {
            console.log(response);
            var status = response.status;
            if (status == "ok") {
                console.log("User deleted");
            }
            return;
        },
        error: function(xhr, error) {}
    });
    location.reload();
    window.location.href = location.protocol + '//' + location.host + location.pathname + "#individualusers"; //reloads the page, with the desired setting window still in focus
}