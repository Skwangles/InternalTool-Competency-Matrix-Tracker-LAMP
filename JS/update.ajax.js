function updateValue(userid, competencyid, value, callerid) { //Updates the user values when changing the textbox
    $.ajax({
        type: 'POST',
        url: "values.ajax.php",
        data: { UserID: userid, CompetencyID: competencyid, Value: value },
        dataType: 'JSON',
        success: function(response) {
            //console.log(response);
            var status = response.status;
            if (status == "ok") {
                var res = response.uv;
                document.getElementById(callerid).value = res;
                return; //updates the value to what the internal value is. 
            } else {
                if (document.getElementById(callerid).value != "") { //If not empty, the input must be false
                    document.getElementById(callerid).value = "";
                }
                console.log("AJAX Success, Processing failure");
            }
        },
        error: function(xhr, error) {
            //showError("Value update failed, please try again later...");
        }
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
        error: function(xhr, error) {
            //showError("Mode switch failed, please try again later...");
        }
    });

}

function updateManager(userid, groupid, value) {
    $.ajax({
        type: 'POST',
        url: "ajax/manager.ajax.php",
        data: { UserID: userid, GroupID: groupid, Value: value },
        dataType: 'JSON',
        success: function(response) {
            var status = response.status;
            if (status == "ok") {
                var id = userid + "-" + groupid + "-select";
                document.getElementById(id).value = response.Value; //updates the value to what the internal value is. 
                //showError("Update successful");
                return;
            }
        },
        error: function(xhr, error) {
            // showError("Manager update failed, please try again later...");
        }
    });
}