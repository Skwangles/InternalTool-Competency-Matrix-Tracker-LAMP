function updateValue(userid, competencyid, value, callerid) {
    $.ajax({
        type: 'POST',
        url: "values.ajax.php",
        data: { UserID: userid, CompetencyID: competencyid, Value: value },
        dataType: 'JSON',
        success: function(response) {
            console.log(response);
            //var status = response.status;
            //var res = response.uv;
            if (response == "ok") {
                console.log("Change Succeeded");
                //document.getElementById(callerid).innerHTML = res;
                return; //updates the value to what the internal value is. 
            } else {
                console.log("Change Failed");
            }
        },
        error: function(xhr, error) {
            console.debug(xhr);
            console.debug(error);
        }
    });
}