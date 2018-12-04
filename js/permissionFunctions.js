$(document).ready(main);
function main()
{
    loadPermsTable();
    $("#save").click(savePerm);
    $("#clear").click(clearTextFields);
    $("#perm").change(validatePermission);
    $("#desc").change(validateDescription);
}
function loadPermsTable()
{
    if($("body").has($(".pidtoedit")))
        $(".pidtoedit").remove();
    var setting = {};
    setting.type = "POST";          // request method
    setting.dataType = "json";      // specify the data format
    setting.url = "api/api.php";        // request target.....
    // data in JSON format
    setting.data = {
        action:"getAllPerms"
    };

    // Loading Data
    setting.success = function(response)
    {
        var table = $("#permTable");
        $(response).each(function()
        {
            var permid = parseInt($(this).attr("permissionid"));
            var tr = $("<tr>");
            tr.attr("class","dynamicRow");
            tr.append("<td>"+$(this).attr("permissionid")+"</td>");
            tr.append("<td>"+$(this).attr("name")+"</td>");
            tr.append("<td>"+$(this).attr("description")+"</td>");
            link = $("<td><a  style = 'cursor:pointer;'>Edit</a></td>");
            link.click(function()
            {

                var editRequest = {};
                editRequest.type = "POST";
                editRequest.dataType = "JSON";
                editRequest.url = "api/api.php";
                editRequest.data = {
                    action: "editPerm",
                    id: permid
                };
                editRequest.success = function (response)
                {
                    var hiddenElement = $("<p class='pidtoedit' id='"+$(response).attr("permissionid")+"'></p>");
                    $("body").append(hiddenElement);
                    $("#perm").val($(response).attr("name"));
                    $("#desc").val($(response).attr("description"));
                };
                editRequest.error = function()
                {
                    alert("Error in Editing Permission...");
                };
                $.ajax(editRequest);
            });
            tr.append(link);
            var link = $("<td><a  style = 'cursor:pointer;'>Delete</a></td>");
            link.click(function()
            {
                if(confirm("Are You Sure you want Delete Permission "+ permid))
                {
                    var deleteRequest = {};
                    deleteRequest.type = "POST";
                    deleteRequest.dataType = "JSON";
                    deleteRequest.url = "api/api.php"
                    deleteRequest.data = {
                        action: "deletePermission",
                        id: permid
                    };
                    deleteRequest.success = function (response)
                    {
                        tr.remove();
                    }
                    deleteRequest.error = function()
                    {
                        alert("Error in Deleting Permission...");
                    }
                    $.ajax(deleteRequest);
                }

            });
            tr.append(link);
            table.append(tr);

        });

    };
    // Error Function
    setting.error = function(res)
    {
        console.log(res);
        alert("Some Error occured while loading Permission....");
    };

    $.ajax(setting); // sending request
}

// Save Role
function savePerm()
{
    if(validatePermission() && validateDescription()) {
        var isNew = true;
        var rid = checkifPermExists();
        if (rid != -1)
            isNew = false;
        var saveRequest = {};
        saveRequest.type = "POST";
        saveRequest.dataType = "JSON";
        saveRequest.url = "api/api.php";
        saveRequest.data =
            {
                "action": "savePerm",
                "perm": $("#perm").val(),
                "desc": $("#desc").val(),
                "isNew": isNew,
                "id": rid
            };
        saveRequest.success = function (response) {
            $("#permTable").find("tr.dynamicRow").remove();
            clearTextFields();
            loadPermsTable();
        };
        saveRequest.error = function (error) {
            console.log(error);
            alert("Some Problem Has Occurred");
        };
        $.ajax(saveRequest);
    }
}
// Check if Perm
function checkifPermExists()
{
    var flag= -1;
    var storedId = 0;
    if($("body").has($(".pidtoedit")))
        storedId = $(".pidtoedit").attr("id");
    var ids = $('#permTable').find("tr").find("td:first");
    ids.each(function () {
        if($(this).text() == storedId)
            flag = storedId
    });
    return flag;
}

// Clear Text Fields
function  clearTextFields() {
    if ($("body").has($(".pidtoedit")))
        $(".pidtoedit").remove();
    $("#perm").val("");
    $("#desc").val("");


}


function validatePermission()
{
    var login = $("#perm");
    var retval = true;
    if(login.val().trim(" ").length == 0) {
        alert("Permission Cannot be empty");
        return false;
    }
    $("#permTable").find("tr").find("td:nth-child(2)").each(function()
    {
        var uid = checkifPermExists();
        if( uid == -1) {
            if (login.val() == $(this).text()) {
                alert("Permission Already Exists");
                retval = false;
            }
        }

    });
    return retval;
}

function validateDescription()
{
    if($("#desc").val().trim(" ").length == 0) {
        alert("Please Add Some description");
        return false;
    }
    return true;
}