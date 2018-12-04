$(document).ready(main);
function  main()
{
    loadRolePermTable();
    loadAllRoles();
    loadAllPermissions();
    $("#save").click(saveRolePerm);
    $("#clear").click(clearFields)
    $("#cmbPerm").change(validatePermission);
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

function loadAllPermissions()
{
    var getRequest = {};
    getRequest.type= "POST";
    getRequest.dataType = "JSON";
    getRequest.url = "api/api.php";
    getRequest.data =
        {
            "action":"getAllPerms"
        };
    getRequest.success = function(response)
    {
        var role = $("#cmbPerm");
        role.html("<option value='0'>-- Select Permission --</option>");
        $(response).each(function()
        {
            role.append("<option value='"+$(this).attr("permissionid")+"'>"+$(this).attr("name")+"</option>")
        });
    }
    getRequest.error =function ()
    {
        alert("Some Error Has Occured");
    }
    $.ajax(getRequest);
}

function saveRolePerm()
{
   if(validateRole() && validatePermission()) {
       if(validateRolePerm()) {
           var isNew = true;
           var rpid = checkifRolePermexist();
           console.log(rpid);
           if (rpid != -1)
               isNew = false;
           var saveRequest = {};
           saveRequest.type = "POST";
           saveRequest.dataType = "JSON";
           saveRequest.url = "api/api.php";
           saveRequest.data =
               {
                   "action": "saveRolePerm",
                   "role": $("#cmbRole").val(),
                   "perm": $("#cmbPerm").val(),
                   "isNew": isNew,
                   "id": rpid
               };
           saveRequest.success = function (reponse) {
               $("#rolePermTable").find("tr.dynamicRow").remove();
               clearFields();
               loadRolePermTable();

           };
           saveRequest.error = function (error) {
               console.log(error);
           };
           $.ajax(saveRequest);
       }
       else
           alert("Role - Permission Set Already Exists");
       }
}

function loadRolePermTable()
{
    var getRequest = {};
    getRequest.type= "POST";
    getRequest.dataType = "JSON";
    getRequest.url = "api/api.php";
    getRequest.data =
        {
            "action":"getRolePerms"
        };
    getRequest.success = function (response)
    {
        var table = $("#rolePermTable");
        $(response).each(function()
        {
            var permid = parseInt($(this).attr("id"));
            var tr = $("<tr>");
            tr.attr("class","dynamicRow");
            tr.append("<td>"+$(this).attr("id")+"</td>");
            tr.append("<td>"+$(this).attr("rolename")+"</td>");
            tr.append("<td>"+$(this).attr("permissionname")+"</td>");
            link = $("<td><a  style = 'cursor:pointer;'>Edit</a></td>");
            link.click(function()
            {
                var editRequest = {};
                editRequest.type = "POST";
                editRequest.dataType = "JSON";
                editRequest.url = "api/api.php";
                editRequest.data =
                {
                    action: "editRolePerm",
                    id: permid
                };
                editRequest.success = function (response)
                {
                    var hiddenElement = $("<p class='rpidtoedit' id='"+$(response).attr("id")+"'></p>");
                    $("body").append(hiddenElement);
                    $("#cmbRole").val(response['roleid']);
                    $("#cmbPerm").val(response['permissionid']);
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
                        action: "deleteRolePerm",
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

function clearFields()
{
    if($("body").has($(".rpidtoedit")))
        $(".rpidtoedit").remove();
    $("#cmbRole").val("0");
    $("#cmbPerm").val("0");
}

function validatePermission()
{
    if($("#cmbPerm").val() == '0')
    {
        alert("Select A Permission");
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

function  checkifRolePermexist()
{
    var flag= -1;
    var storedId = 0;
    if($("body").has($(".rpidtoedit")))
        storedId = $(".rpidtoedit").attr("id");
    var ids = $('#rolePermTable').find("tr").find("td:first");
    ids.each(function () {
        if($(this).text() == storedId)
            flag = storedId
    });
    return flag;
}

function validateRolePerm() {
    if(checkifRolePermexist() == -1) {
        var isRoleSame = false;
        var isPermSame = false;
        var roles = $('#rolePermTable').find("tr").find("td:nth-child(2)");
        var perm = $('#rolePermTable').find("tr").find("td:nth-child(3)");
        roles.each(function () {

            if ($(this).text() == $("#cmbRole option:selected").text()) {

                isRoleSame = true;
            }
        });
        perm.each(function () {
            if ($(this).text() == $("#cmbPerm option:selected").text()) {
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