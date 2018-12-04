$(document).ready(main);
function main()
{
    loadRolesTable();
    $("#save").click(saveRole);
    $("#clear").click(clearTextFields);
    $("#role").change(validateRole);
    $("#desc").change(validateDescription);

}
function loadRolesTable()
{
    if($("body").has($(".ridtoedit")))
        $(".ridtoedit").remove();
    var setting = {};
    setting.type = "POST";          // request method
    setting.dataType = "json";      // specify the data format
    setting.url = "api/api.php";        // request target.....
    // data in JSON format
    setting.data = {
        action:"getAllRoles"
    };

    // Loading Data
    setting.success = function(response)
    {
        var table = $("#rolesTable");
        $(response).each(function()
        {
            var roleid = parseInt($(this).attr("roleid"));
            var tr = $("<tr>");
            tr.attr("class","dynamicRow");
            tr.append("<td>"+$(this).attr("roleid")+"</td>");
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
                    action: "editRole",
                    id: roleid
                };
                editRequest.success = function (response)
                {
                    var hiddenElement = $("<p class='ridtoedit' id='"+$(response).attr("roleid")+"'></p>");
                    $("body").append(hiddenElement);
                    $("#role").val($(response).attr("name"));
                    $("#desc").val($(response).attr("description"));
                };
                editRequest.error = function()
                {
                    alert("Error in Editing Role...");
                };
                $.ajax(editRequest);
            });
            tr.append(link);
            var link = $("<td><a  style = 'cursor:pointer;'>Delete</a></td>");
            link.click(function()
            {
                if(confirm("Are You Sure you want Delete Role"+ roleid))
                {
                    var deleteRequest = {};
                    deleteRequest.type = "POST";
                    deleteRequest.dataType = "JSON";
                    deleteRequest.url = "api/api.php"
                    deleteRequest.data = {
                        action: "deleteRole",
                        id: roleid
                    };
                    deleteRequest.success = function (response)
                    {
                        tr.remove();
                    }
                    deleteRequest.error = function()
                    {
                        alert("Error in Deleting Role...");
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
        alert("Some Error occured while loading Roles....");
    };

    $.ajax(setting); // sending request
}

// Save Role
function saveRole()
{
    if(validateRole() && validateDescription()) {
        var isNew = true;
        var rid = checkifRoleExists();
        if (rid != -1)
            isNew = false;
        var saveRequest = {};
        saveRequest.type = "POST";
        saveRequest.dataType = "JSON";
        saveRequest.url = "api/api.php";
        saveRequest.data =
            {
                "action": "saveRole",
                "role": $("#role").val(),
                "desc": $("#desc").val(),
                "isNew": isNew,
                "id": rid
            };
        saveRequest.success = function (response) {
            $("#rolesTable").find("tr.dynamicRow").remove();
            clearTextFields();
            loadRolesTable();
        };
        saveRequest.error = function (error) {
            console.log(error);
            alert("Some Problem Has Occurred");
        };
        $.ajax(saveRequest);
    }
}
// Check if Role
function checkifRoleExists()
{
    var flag= -1;
    var storedId = 0;
    if($("body").has($(".ridtoedit")))
        storedId = $(".ridtoedit").attr("id");
    var ids = $('#rolesTable').find("tr").find("td:first");
    ids.each(function () {
        if($(this).text() == storedId)
            flag = storedId
    });
    return flag;
}

function  clearTextFields()
{

    if($("body").has($(".ridtoedit")))
        $(".ridtoedit").remove();
    $("#role").val("");
    $("#desc").val("");
}

function validateRole()
{
    var login = $("#role");
    var retval = true;
    if($("#role").val().trim(" ").length == 0) {
        alert("Role Cannot be empty");
        return false;
    }
    $("#rolesTable").find("tr").find("td:nth-child(2)").each(function()
    {

        var uid = checkifRoleExists();
        if( uid == -1) {
            if (login.val() == $(this).text()) {
                alert("Role Already Exists");
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