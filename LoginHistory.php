<?php
require("api/conn.php");
require("api/myFunctions.php");
require('navbar.php');
if(!(isset($_SESSION["user"]) && $_SESSION["user"] != null) || $_SESSION["isAdmin"] != 1)
{
    header('Location: Login.php');
}
?>
<html>
<head>
    <title> Login History </title>
    <link href="css/bootstrap.min.css" rel="stylesheet"/>
</head>
</html>

<?php
$sql = "SELECT * FROM loginhistory";
$result = mysqli_query($conn, $sql);
$recordsFound = mysqli_num_rows($result);
if ($recordsFound > 0) {
    ?>
    <div class="container">
        <h1>Login History</h1>
        <hr>
   <table class=" table table-bordered table-responsive" id="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>login</th>
            <th>Login Time</th>
            <th>Machine IP</th>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['userid']; ?></td>
                <td><?php echo $row['login']; ?></td>
                <td><?php echo $row["logintime"]; ?></td>
                <td><?php echo $row["machineip"]; ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <?php
} else {
    ?>
    <script>alert("No Records Found")</script><?php
    //header("location: userManagement.php");
}
?>
        </div>

