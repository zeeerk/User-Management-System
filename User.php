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
        <title>User Management</title>
        <link href="css/bootstrap.min.css" rel="stylesheet"/>
         <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/userFunctions.js"></script>
    </head>
    <body>
       
        <div class="container">
        <div class="col-md-5">
        <h3>User's Data</h3>
        <hr>
            
                <label>Login:</label>
                <input type="text" class="form-group form-control" id="login" placeholder="Login">
                <label>Password:</label>
                <input type="password" class="form-group form-control" id="passwd" placeholder="Password">
                <label>Name:</label>
                <input type="text" class="form-group form-control" id="name" placeholder="Name">
                <label>Email:</label>
                <input type="text" class="form-group form-control" id="email" placeholder="Email">
                
                <label>Country:</label>
                <select id="country" class="form-group form-control">
                </select>
                <label>City:</label>
                <select id="city" class="form-group form-control">
                </select>
            <input type="checkbox" id="isadmin"><label>Is Admin</label>
            <br>
            <input type="button" class="col-md-6 btn btn-default btn-success" id="save" value="Save">
            <input type="button" class="col-md-6 btn btn-default btn-danger" id="clear" value="Clear">
            
            </div>

        

        <div class="col-md-offset-5">
         <h3>Users Table</h3>
         <hr>
            <table class="table table-bordered table-responsive" id="userTable">
                <tr class="table-active">
                    <th scope="col">ID</th>
                    <th>User Name</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </table>
            
        </div>
    </div>
    </body>
</html>