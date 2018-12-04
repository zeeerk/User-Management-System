<?php
require("api/conn.php");
require("api/myFunctions.php");
$name ="";
?>
<?php
if($_SESSION["user"] != null)
{
    $user = getUserById($_SESSION["user"]);
   $name = $user["name"];
}
else
   header('Location: Login.php');


?>
<html>
    <head>
        <title>Admin Home</title>
        <link href="css/bootstrap.min.css" rel="stylesheet"/>
    </head>
    <body>
        <?php require("navbar.php"); ?>
            <h1 class="text-center">Welcome <?php echo($name); ?></h1>
    </body>
</html>

<?php
if(isset( $_SESSION["isAdmin"] )  && $_SESSION["isAdmin"] == '0')
{
    $user_roles= getAllUserRoles();
    $role_perms = getAllRolePerms();
    echo "<ol>";
    for ($i =0; $i < count($user_roles);$i++)
    {
        if($user_roles[$i]['userid'] == $_SESSION['user']) {

            echo "<li><b>Role: </b>" . $user_roles[$i]['rolename']."<br><b>Permissions:</b></li>";
            echo"<ul>";
            for ($j = 0; $j < count($role_perms) ; $j++) {
                if($user_roles[$i]['roleid'] == $role_perms[$j]['roleid'])
                {
                    echo"<li>".$role_perms[$j]['permissionname']. " </li>";
                }
            }
            echo "</ul>";
        }
    }
    echo"</ol>";
}
?>