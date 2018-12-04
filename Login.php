<?php
$error="";
$uname="";
$password="";
?>
<?php
require("api/conn.php");
require("api/myFunctions.php");
if(isset($_SESSION["user"]) && $_SESSION["user"] != null)
{
    header('Location: Home.php');
}
?>
<?php
if(isset($_POST["btnLogin"]))
{
    $uname = $_POST["uname"];
    $password = $_POST["passwd"];
    $user = getUserByLogin($uname,$password);
    echo $user;
    if($uname == $user["login"] && $password == $user["password"])
    {
        
        $_SESSION["user"] = $user["userid"];
        $_SESSION["isAdmin"] = $user["isadmin"];
        saveHistory();
        header('Location: Home.php');
    }
    else
        $error="Invalid Credentials.";
}
?>
 <!DOCTYPE html>
    <html>
    <head>
        <title>Login</title>
        <link href="css/bootstrap.min.css" rel="stylesheet"/>
    </head>
    <body>
        <h1>Security Manager</h1>
        <hr>
        <div class="container">
                
            <div class="col-md-12" style="border:4px solid black;">
            <h1 class="text-center">Login</h1>    
                <hr>
            <form method="post"  action="">
                <label>Username:</label>
                <input type="text"  id="uname" name="uname" class="form-control form-group" placeholder="Username" required>
                <label>Password:</label>
                <input type="password" id="passwd" name="passwd" class="form-control form-group" placeholder="Password" required>
                <br><br>
                <input type="submit"   id="btnLogin" name="btnLogin" value ="Login" class="col-md-6 btn  btn-success">
                <input type="reset" class="col-md-6 pull-right btn btn-danger" value="Clear">
                <br><br><br>
            </form>
            <h2 style="color:red;"><?php echo($error) ?></h2>
            </div>
        </div>
        
    </body>
    </html>