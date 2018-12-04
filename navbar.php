<?php
if(isset($_SESSION["user"])) { ?>
<form action="" method="post">
    <nav class="navbar navbar-inverse">
        
        <ul class="nav navbar-nav">
        <div class="navbar-header">
                <a class="navbar-brand" href="home.php">Secuity Manager</a>
            </div>
            <li><a href="home.php">Home</a></li>
            <?php if(isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == 1) {?>
           
            <li><a href="User.php">User Management</a></li>
            <li><a href="Role.php">Role Management</a></li>
            <li><a href="Permission.php">Permission Management</a></li>
            <li><a href="RolePerm.php">Role-Permission Assignment</a></li>
            <li><a href="UserRole.php">User-Role Assignment</a></li>
            <li><a href="LoginHistory.php">Login History</a></li>
            <?php } ?>
            
        </ul>
        <ul class="nav navbar-nav pull-right">
             <li><input class="navbar-btn btn-link" style="text-decoration:none;" type="submit" value="Logout" name="btnLogout" id="btnLogout"></li>
        </ul>
    
    </nav>
</form>
    
<?php } 
if(isset($_POST["btnLogout"]))
{
     session_destroy();
    header('Location: login.php');
}
?>
