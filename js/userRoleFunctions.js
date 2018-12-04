$(document).ready(main);
function  main()
{
    loadUserRoleTable();
    loadAllRoles();
    loadAllUsers();
    $("#save").click(saveUserRole);
    $("#clear").click(clearFields);
    $("#cmbUser").change(validateUser);
    $("#cmbRole").change(validateRole);
}
function loadAllRoles()
{
    var getRequest = {};
    getRequest.type= "POST";
    getRequest.dataType = "JSON";
    getRequest.url = "api/api.php";
    getRequest.data =
        {
            "action":"getAllRoles"
        };
    getRequest.success = function(response)
    {
        var role = $("#cmbRole");
        role.html("<option value='0'>-- Select Role --</option>");
        $(response).each(function()
        {
            role.append("<option value='"+$(this).attr("roleid")+"'>"+$(this).attr("name")+"</option>")
        });
    }
    getRequest.error =function ()
    {
        alert("Some Error Has Occured");
    }
    $.ajax(getRequest);
}

function loadAllUsers()
{
    var getRequest = {};
    getRequest.type= "POST";
    getRequest.dataType = "JSON";
    getRequest.url = "api/api.php";
    getRequest.data =
        {
            "action":"getAllUsers"
        };
    getRequest.success = function(response)
    {
        var role = $("#cmbUser");
        role.html("<option value='0'>-- Select User --</option>");
        $(response).each(function()
        {
            role.append("<option value='"+$(this).attr("id")+"'>"+$(this).attr("name")+"</option>")
        });
    }
    getRequest.error =function (error)
    {
        console.log(error);
        alert("Some Error Has Occured");
    }
    $.ajax(getRequest);
}

function saveUserRole()
{
 if(validateRole() && validateUser()) {
     if(validateUserRole()) {
         var isNew = true;
         var rpid = checkifUserRoleExist();
         console.log(rpid);
         if (rpid != -1)
             isNew = false;
         var saveRequest = {};
         saveRequest.type = "POST";
         saveRequest.dataType = "JSON";
         saveRequest.url = "api/api.php";
         saveRequest.data =
             {
                 "action": "saveUserRole",
                 "role": $("#cmbRole").val(),
                 "user": $("#cmbUser").val(),
                 "isNew": isNew,
                 "id": rpid
             };
         saveRequest.success = function (reponse) {
             $("#userRoleTable").find("tr.dynamicRow").remove();
             clearFields();
             loadUserRoleTable();
         };
         saveRequest.error = function (error) {
             console.log(error);
         };
         $.ajax(saveRequest);
     }
     else
         alert("User - Role Already Exists...");
 }
}

function loadUserRoleTable()
{
    debugger;
    var getRequest = {};
    getRequest.type= "POST";
    getRequest.dataType = "JSON";
    getRequest.url = "api/api.php";
    getRequest.data =
        {
            "action":"getUserRoles"
        };
    getRequest.success = function (response)
    {
        var table = $("#userRoleTable");
        $(response).each(function()
        {
            console.log(response);
            var permid = parseInt($(this).attr("id"));
            var tr = $("<tr>");
            tr.attr("class","dynamicRow");
            tr.append("<td>"+$(this).attr("id")+"</td>");
            tr.append("<td>"+$(this).attr("username")+"</td>");
            tr.append("<td>"+$(this).attr("rolename")+"</td>");
            link = $("<td><a  style = 'cursor:pointer;'>Edit</a></td>");
            link.click(function()
            {
                var editRequest = {};
                editRequest.type = "POST";
                editRequest.dataType = "JSON";
                editRequest.url = "api/api.php";
                editRequest.data =
                    {
                        action: "editUserRole",
                        id: permid
                    };
                editRequest.success = function (response)
                {
                    var hiddenElement = $("<p class='uridtoedit' id='"+$(response).attr("id")+"'></p>");
                    $("body").append(hiddenElement);
                    $("#cmbRole").val(response['roleid']);
                    $("#cmbUser").val(response['userid']);
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
                if(confirm("Are You Sure you want Delete Role Permission "+ permid))
                {
                    var deleteRequest = {};
                    deleteRequest.type = "POST";
                    deleteRequest.dataType = "JSON";
                    deleteRequest.url = "api/api.php";
                    deleteRequest.data = {
                        action: "deleteUserRole",
                        id: permid
                    };
                    deleteRequest.success = function (response)
                    {
                        tr.remove();
                    }
                    deleteRequest.error = function(err)
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

    getRequest.error = function (err) {
        console.log(err);
    };

    $.ajax(getRequest);
}

function validateUser()
{
    if($("#cmbUser").val() == '0')
    {
        alert("Select A User");
        return false;
    }
        return true;
}

function validateRole()
{
    if($("#cmbRole").val() == '0')
    {
        alert("Select A Role");
        return false;
    }
    return true;
}


function clearFields()
{
    if($("body").has($(".uridtoedit")))
        $(".uridtoedit").remove();
    $("#cmbRole").val("0");
    $("#cmbUser").val("0");
}

function  checkifUserRoleExist()
{
    var flag= -1;
    var storedId = 0;
    if($("body").has($(".uridtoedit")))
        storedId = $(".uridtoedit").attr("id");
    var ids = $('#userRoleTable').find("tr").find("td:first");
    ids.each(function () {
        if($(this).text() == storedId)
            flag = storedId
    });
    return flag;
}

function validateUserRole() {
    if(checkifUserRoleExist() == -1) {
        var isRoleSame = false;
        var isPermSame = false;
        var roles = $('#userRoleTable').find("tr").find("td:nth-child(3)");
        var perm = $('#userRoleTable').find("tr").find("td:nth-child(2)");
        roles.each(function () {

            if ($(this).text() == $("#cmbRole option:selected").text()) {

                isRoleSame = true;
            }
        });
        perm.each(function () {
            if ($(this).text() == $("#cmbUser option:selected").text()) {
                isPermSame = true;
            }
        });

        if (isRoleSame && isPermSame)
            return false;
        else
            return true;
    }
    return true;
}