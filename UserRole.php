<?php
session_start();
if(!(isset($_SESSION["user"]) && $_SESSION["user"] != null) || $_SESSION["isAdmin"] != 1)
{
    header('Location: Login.php');
}
require_once("navbar.php");
?>
<html>
<head>
    <title>Role Management</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"/>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/userRoleFunctions.js"></script>
</head>
<body >
<div class="container" >
    <div class="col-md-5">
        <h3>User - Role Assigment</h3>
        <hr>
            <label>User:</label>
            <select id="cmbUser" class="form-control form-group">

            </select>
            <label>Role:</label>
            <select id="cmbRole" class="form-control form-group">

            </select>


            <input type="button" class="col-md-6 btn btn-default btn-success" id="save" value="Save">
            <input type="button" class="col-md-6 btn btn-default btn-danger" id="clear" value="Clear">
    </div>
    <div class=" col -md-7 col-lg-offset-5">
        <h3>User-Role Table</h3>
        <hr>
        <div >
            <table class="table table-bordered table-responsive" id="userRoleTable">

                <tr>
                    <th scope="col">ID</th>
                    <th>User</th>
                    <th scope="col">Role</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>

            </table>
        </div>

    </div>
</div>
</body>
</html>
