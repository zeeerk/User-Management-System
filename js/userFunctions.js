            $(document).ready(main);
function main()
{
    if($("#userTable").length != 0)
        loadUserTable();
        loadCountries();
        $("#country").change(loadCities);
        $("#save").click(saveUser);
        $("#clear").click(clearFields);
        $("#login").change(validateLogin);
        $("#email").change(validateEmail);
        $("#passwd").change(validatePassword);
}

// Load Users Table
function loadUserTable()
{
    var setting = {};
    setting.type = "POST";          // request method 
    setting.dataType = "json";      // specify the data format
    setting.url = "api/api.php";        // request target.....
    // data in JSON format
    setting.data = {
        action:"getAllUsers"
    };

    // Loading Data 
    setting.success = function(response)
    {
        var table = $("#userTable");
        $(response).each(function()
        {
            var userid = parseInt($(this).attr("id"));
           var tr = $("<tr>");
            tr.append("<td>"+$(this).attr("id")+"</td>");
            tr.append("<td>"+$(this).attr("login")+"</td>");
            tr.append("<td>"+$(this).attr("name")+"</td>");
            tr.append("<td>"+$(this).attr("email")+"</td>");
            link = $("<td><a  style = 'cursor:pointer;'>Edit</a></td>");
            
            link.click(function()
            {
               
                var editRequest = {};
                editRequest.type = "POST";
                editRequest.dataType = "JSON";
                editRequest.url = "api/api.php";
                editRequest.data = {
                        action: "editUser",
                        id: userid
                    };
                    editRequest.success = function (response)
                    {
                        var hiddenElement = $("<p class='uidtoedit' id='"+$(response).attr("id")+"'></p>");
                        $("body").append(hiddenElement);
                        $("#login").val($(response).attr("login"));
                        $("#passwd").val($(response).attr("passwd"));
                        $("#name").val($(response).attr("name"));
                        $("#email").val($(response).attr("email"));
                        $("#country").val($(response).attr("countryid"));
                        loadCities($(response).attr("cityid"));
                        if($(response).attr("isadmin") == 1)
                            $("#isadmin").attr("checked",true);
                        else
                            $("#isadmin").attr("checked",false);
                        
                    };
                    editRequest.error = function()
                    {
                        alert("Error in Editing User...");
                    };
                    $.ajax(editRequest); 
            });
            tr.append(link);
            var link = $("<td><a  style = 'cursor:pointer;'>Delete</a></td>");
            link.click(function()
            {
                if(confirm("Are You Sure you want Delete User"+ userid))
                {
                    var deleteRequest = {};
                    deleteRequest.type = "POST";
                    deleteRequest.dataType = "JSON";
                    deleteRequest.url = "api/api.php"
                    deleteRequest.data = {
                        action: "deleteUser",
                        id: userid
                    };
                    deleteRequest.success = function (response)
                    {
                       tr.remove();
                    }
                    deleteRequest.error = function()
                    {
                        alert("Error in Deleting User...");
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
        alert("Some Error occured while loading Users....");
    };
                    
    $.ajax(setting); // sending request
}

function checkifUserExists()
{
    var flag= -1;
    var storedId = 0;
    if($("body").has($(".uidtoedit")))
        storedId = $(".uidtoedit").attr("id");
    var ids = $('#userTable').find("tr").find("td:first");
    ids.each(function () {
        if($(this).text() == storedId)
            flag = storedId
    });
    return flag;
}

// function Save User

function saveUser()
{
    debugger;
    if(validateCredentials()) {

        var isNew = true;
        var uid = checkifUserExists();
        if (uid != -1)
            isNew = false;
        var login = $("#login").val();
        var passwd = $("#passwd").val();
        var name = $("#name").val();
        var email = $("#email").val();
        var country = $("#country").val();
        var city = $("#city").val();
        var isadmin = 0;
        if ($("#isadmin").attr("checked"))
            isadmin = 1;
        var user = {
            "login": login,
            "password": passwd,
            "name": name,
            "email": email,
            "countryid": country,
            "cityid": city,
            "isadmin": isadmin
        };
        var saveRequest = {};
        saveRequest.type = "POST";
        saveRequest.dataType = "JSON";
        saveRequest.url = "api/api.php";
        saveRequest.data =
            {
                "action": "saveUser",
                "user": user,
                "isNew": isNew
            };
        saveRequest.success = function (response) {
            console.log(response);
            var table = $("#userTable");
            var userid = parseInt($(response).attr("userid"));
            var isNew = $(response).attr("isNew");
            if (isNew == false) {
                $("#" + userid).remove();
                var rows = $('#userTable').find("tr");
                rows.find("td:first").each(function () {
                    if ($(this).text() == userid)
                        $(this).closest("tr").remove();
                });
            }
            var tr = $("<tr>");
            tr.append("<td>" + $(response).attr("userid") + "</td>");
            tr.append("<td>" + $(response).attr("login") + "</td>");
            tr.append("<td>" + $(response).attr("name") + "</td>");
            tr.append("<td>" + $(response).attr("email") + "</td>");
            link = $("<td><a  style = 'cursor:pointer;'>Edit</a></td>");

            link.click(function () {

                var editRequest = {};
                editRequest.type = "POST";
                editRequest.dataType = "JSON";
                editRequest.url = "api/api.php"
                editRequest.data = {
                    action: "editUser",
                    id: userid
                };
                editRequest.success = function (response) {
                    debugger;
                    console.log(response);
                    var hiddenElement = $("<p class='uidtoedit' id='" + $(response).attr("id") + "'></p>");
                    $("body").append(hiddenElement);
                    $("#login").val($(response).attr("login"));
                    $("#passwd").val($(response).attr("passwd"));
                    $("#name").val($(response).attr("name"));
                    $("#email").val($(response).attr("email"));
                    $("#country").val($(response).attr("countryid"));
                    loadCities($(response).attr("cityid"));
                    if($(response).attr("cityid") == "1")
                        $("#isadmin").attr("checked",true);
                    else
                        $("#isadmin").attr("checked",false);

                };
                editRequest.error = function () {
                    alert("Error in Editing User...");
                };
                $.ajax(editRequest);
            });
            tr.append(link);
            var link = $("<td><a  style = 'cursor:pointer;'>Delete</a></td>");
            link.click(function () {
                if (confirm("Are You Sure you want Delete User" + userid)) {
                    var deleteRequest = {};
                    deleteRequest.type = "POST";
                    deleteRequest.dataType = "JSON";
                    deleteRequest.url = "api/api.php"
                    deleteRequest.data = {
                        action: "deleteUser",
                        id: userid
                    };
                    deleteRequest.success = function (response) {
                        tr.remove();
                    }
                    deleteRequest.error = function () {
                        alert("Error in Deleting User...");
                    }
                    $.ajax(deleteRequest);
                }

            });
            tr.append(link);
            table.append(tr);
            clearFields();
        };

        saveRequest.error = function (err) {
            console.log(err);
            alert("Hello");
        };

        $.ajax(saveRequest);
    }
}

//Load Countries
function loadCountries()
{
    var setting  ={};
    setting.type = "POST";
    setting.dataType ="JSON";
    setting.url = "api/api.php";
    setting.data = 
    {
     action: "getCountries"    
    };
    setting.success = function(response)
    {
        var cmbCountry = $("#country");
        cmbCountry.html("<option value='0'>-- Select Country --</option>");
         $(response).each(function() 
         {
                cmbCountry.append("<option value='"+$(this).attr("id")+"'>"+$(this).attr("name")+"</option>");
         });
        
    };
    setting.error = function()
    {
        alert("somme Error Occured while Loading Countries.");
    };
    $.ajax(setting);
}

//load Countries
function loadCities(cid)
{
    debugger;
    var country =$("#country").val();
    if(country != null)
    {
        if (country != '0')
        {
            var setting = {};
            setting.type = "POST";
            setting.dataType = "JSON";
            setting.url = "api/api.php";
            setting.data = {"action": "getCities", "cid": parseInt(country)};

            setting.success = function (response)
            {
                console.log(response);
                var cmbCity = $("#city");
                cmbCity.html("<option value='0'>--Select City--</option>");
                $(response).each(function()
                {
                    if($(this).attr("id") == cid)
                        cmbCity.append("<option selected='selected' value='" + $(this).attr("id") + "'>" + $(this).attr("name") + "</option>");

                    else
                        cmbCity.append("<option value='"+$(this).attr("id")+"'>" + $(this).attr("name") + "</option>");
                });
            };
            setting.error = function (err)
            {
                alert("Some Error Occured While Loading Cities.");
            };
            $.ajax(setting);
        }
        else
        {
            alert("Select a country First");
        }
    }
}

//Clear Fields
function  clearFields()
{
    if($("body").has($(".uidtoedit")))
        $(".uidtoedit").remove();
    $("#login").val("");
    $("#passwd").val("");
    $("#name").val("");
    $("#email").val("");
    $("#country").val("0");
    $("#city").val("0");
    $("#isadmin").attr("checked",false);
}

// validate Login
function validateLogin()
{
    var login = $("#login");
    var retval = true;
    if(login.val().trim(" ").length == 0)
    {
        alert("Login cannot be empty");
        return false;
    }
    $("#userTable").find("tr").find("td:nth-child(2)").each(function()
    {
        var uid = checkifUserExists();
        if( uid == -1) {
            if (login.val() == $(this).text()) {
                alert("Login Already Exists");
                retval = false;
            }
        }

    });
    return retval;
}

//validate Email
function validateEmail()
{
    var email = $("#email");
    var retval = true;
    if(email.val().trim(" ").length == 0)
    {
        alert("Email cannot be empty");
        return false;
    }
    var uid = checkifUserExists();
    $("#userTable").find("tr").find("td:nth-child(4)").each(function()
    {
        if(uid == -1) {
            if (email.val() == $(this).text()) {
                alert("Email Already Exists");
                retval =  false;
            }
        }

    });
    return retval;
}

// validate Name
function validateName()
{
    if($("#name").val().trim(" ").length == 0) {
        alert("Please Enter Name");
        return false;
    }
    return true;

}

            // validate Name
function validatePassword() {
    if ($("#passwd").val().trim(" ").length == 0) {
        alert("Password cannot be empty");
        return false;
    }
    return true;
}

            // validate Name
function validateCountry() {
    if ($("#country").val() == "0") {
        alert("Please Select A country");
        return false;
    }
    return true;
}

function validateCity() {
    if ($("#city").val() == "0") {
        alert("Please Select A City");
        return false;
    }
    return true;
}


function validateCredentials()
{
    debugger;
    if(validateLogin() && validatePassword() && validateName() && validateEmail() && validateCountry() && validateCity())
    return true;
    return false;
}
