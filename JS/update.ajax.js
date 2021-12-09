function updateValue(userid, competencyid, value, callerid) {
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
                console.log("AJAX Success, Processing failure");
            }
        },
        error: function(xhr, error) {
            console.log("AJAX Failure")
        }
    });
}