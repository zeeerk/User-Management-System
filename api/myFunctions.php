<?php
require_once("conn.php");
/*
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!? USER FUNCTIONS ? !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
*/
// ------------------------> Get User by Login and Password <---------------------

function getUserByLogin($uname,$password)
{ 
    global $conn;
    $sql = "SELECT * FROM users WHERE login = '$uname' AND password = '$password'";
	
	//Step-2: Query execution
	$result = mysqli_query($conn, $sql);
	
	//Step-3: Get count of records
	$recordsFound = 0;
    $recordsFound=mysqli_num_rows($result);			
	
	if ($recordsFound > 0)
	{
        $row = mysqli_fetch_assoc($result);
        $user['userid'] =       $row['userid'];
        $user['login'] =    $row['login'];
        $user['password'] =   $row['password'];
        $user['name'] =     $row['name'];
        $user['email'] =    $row['email'];
        $user['countryid']= $row['countryid'];
        $user['createdon']= $row['createdon'];
        $user['cityid'] =   $row['cityid'];
        $user['isadmin'] = $row['isadmin'];
        return $user;
    }
    else
        return null;
}

//---------------------> Get User by User ID <---------------------------
function getUserById($id)
{ 
    global $conn;
    $sql = "SELECT * FROM users WHERE userid = $id";
	//Step-2: Query execution
	$result = mysqli_query($conn, $sql);
	//Step-3: Get count of records
	$recordsFound = 0;
    $recordsFound=mysqli_num_rows($result);			
	if ($recordsFound > 0)
    {
        	$user = array();
         $row = mysqli_fetch_assoc($result);
        
            $user['id'] =       $row['userid'];
            $user['login'] =    $row['login'];
            $user['passwd'] =   $row['password'];
            $user['name'] =     $row['name'];
            $user['email'] =    $row['email'];
            $user['countryid']= $row['countryid'];
            $user['createdon']= $row['createdon'];
            $user['cityid'] =   $row['cityid'];
            $user['isadmin'] = $row['isadmin'];
        return $user;
    }
    else
        return null;

    
}

// ------------------------> Get User by Login and Password <---------------------
function getAllUsers()
{
    global $conn;
    $sql = "SELECT * FROM users";
	
	//Step-2: Query execution
	$result = mysqli_query($conn, $sql);
	
	//Step-3: Get count of records
	$recordsFound = 0;
    $recordsFound=mysqli_num_rows($result);			
	
	if ($recordsFound > 0)
    {
        $users =array();
        $user =array();
        while($row = mysqli_fetch_assoc($result))
        {
            $user['id'] = $row['userid'];
            $user['login'] = $row['login'];
            $user['passwd'] = $row['password'];
            $user['name'] = $row['name'];
            $user['email'] = $row['email'];
            $user['countryid'] = $row['countryid'];
            $user['cityid'] =$row['cityid'];
            $user['createdon'] =  $row['createdon'];
            $user['isadmin'] =  $row['isadmin'];
             
            array_push($users,$user);
            //  $users.push($user);
        }
        
        
        return $users;
    }
        
    else
        return null;
}

// ------------------------> Get All countries <---------------------
function getAllCountries()
{ 
    global $conn;
    $sql = "SELECT * FROM country";
	
	//Step-2: Query execution
	$result = mysqli_query($conn, $sql);
	
	//Step-3: Get count of records
	$recordsFound = 0;
    $recordsFound=mysqli_num_rows($result);			
	
	if ($recordsFound > 0)
    {
        $countries = array();
        $country = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $country['id']= $row['id'];
            $country['name']= $row['name'];
             
            array_push($countries,$country);
        }
       return $countries;
    }
    else
        return null;
}
 //------------------------> Save Users <------------------
function saveUser($user)
{
    global $conn;
    $login = $user['login'];
    $password = $user['password'];
    $name = $user['name'];
    $email = $user['email'];
    $country = $user['countryid'];
    $date=  date('Y-m-d H:i:s');
    $createdby = $_SESSION['user'];
   $isadmin = $user['isadmin'];
   $city = $user['cityid'];
        $sql = "INSERT INTO users VALUES(NULL,'$login','$password','$name','$email','$country','$city','$    date','$createdby','$isadmin')";
    
    if (mysqli_query($conn, $sql) === TRUE) {
				$userObj = getUserByLogin($login,$password);
				return $userObj;
			} else {
				
				echo"Some Problem has occurred";
			}	
 
       
}
 
//------------------------>Update User <--------------------
function updateUser($user)
{
    global $conn;
    $id = $user['userid'];
    $login = $user['login'];
    $password = $user['password'];
    $name = $user['name'];
    $email = $user['email'];
    $country = $user['countryid'];
    $isadmin = $user['isadmin'];
    
     $sql = "UPDATE users  SET login = '$login' ,password = '$password', name='$name', email='$email', countryid = $country , isadmin= $isadmin WHERE userid = $id";
    
 
    if (mysqli_query($conn, $sql) === TRUE) {
                        return getUserByLogin($login,$password);
			} else {
				return null;
			}	
    
}
//------------------------> Delete User <-----------------
function deleteUser($id)
{
    global $conn;
    $sql = "DELETE FROM users WHERE userid = $id";
    if (mysqli_query($conn, $sql) === TRUE) {
				$last_id = mysqli_insert_id($conn);
				
			} else {
				echo"Some Problem has occurred";
			}	
}

//------------------>Validate Email<-------------
function validateEmail($email,$isNew)
{
    global $conn;
    $alpha = 0;
    $dot = 0;
    $alphaPos = 0;
    $dotPos = 0;
    if($email == "")
        return false;
    if($isNew)
    {
        $users = getAllUsers($conn);
        while($user =  mysqli_fetch_assoc($users))
        {
        if($user['email'] == $email)
            return false;
        }
    }
     for($i=0; $i<strlen($email);$i++)
    {
        if($email[$i] == " ")
            return false;
         if($email[$i] == '@')
         {
             $alpha++;
             $alphaPos = $i;
             
         }
          if($email[$i] == '.')
          {
             $dot++;
              $dotPos = $i;
          }
    }
    
    if($dotPos > $alphaPos && $alpha ==1  && $dot == 1 )
        return true;
    else
        return false;
}

/*
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!? ROLE FUNCTIONS ? !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
*/
 //-------------------------------> Save Role <------------------------
function saveRole($role,$desc)
{
    global $conn;
     $date=  date('Y-m-d H:i:s');
    $createdby = $_SESSION['user'];
    $sql = "INSERT INTO roles VALUES(NULL,'$role','$desc','$date','$createdby')";
    
    if (mysqli_query($conn, $sql) === TRUE) {
				$retval = mysqli_insert_id($conn);
			} else {
				
				$retval = "Some Problem has occurred";
			}
			return $retval;
    
}

// ------------------------> Get All Roles <---------------------
function getAllRoles()
{
    global $conn;
    $sql = "SELECT * FROM roles";
	
	//Step-2: Query execution
	$result = mysqli_query($conn, $sql);
	
	//Step-3: Get count of records
	$recordsFound = 0;
    $recordsFound=mysqli_num_rows($result);			
	
	if ($recordsFound > 0)
    {
        $roles =array();
        $role =array();
        while($row = mysqli_fetch_assoc($result))
        {
            $role['roleid'] = $row['roleid'];
            $role['name'] = $row['name'];
            $role['description'] = $row['description'];
            array_push($roles,$role);
        }
        return $roles;
    }
    else
        return null;
}
//---------------------> Get Role by  ID <---------------------------
function getRoleById($id)
{ 
    global $conn;
    $sql = "SELECT * FROM roles WHERE roleid = $id";
	
	//Step-2: Query execution
	$result = mysqli_query($conn, $sql);
	//Step-3: Get count of records
	$recordsFound = 0;
    $recordsFound=mysqli_num_rows($result);			
	
	if ($recordsFound > 0)
    {
        $row = mysqli_fetch_assoc($result);

            $role['roleid'] = $row['roleid'];
            $role['name'] = $row['name'];
            $role['description'] = $row['description'];
            return $role;
    }
    else
        return null;

}

//------> Validate Role <-------------
function validateRole($role,$isNew)
{
    global $conn;
    if($role == "")
        return false;
    if($isNew)
    {
        $roles = getAllRoles($conn);
        while($role =  mysqli_fetch_assoc($roles))
        {
            if($role['name'] == $role)
                return false;
        }
    }
    return true;
}
//------------------------>Update Role <--------------------
function updateRole($role)
{
    global $conn;
    $id = $role['roleid'];
    $name = $role['name'];
    $desc = $role['description'];
     $sql = "UPDATE roles  SET name = '$name' ,description= '$desc' WHERE roleid = $id";
    if (mysqli_query($conn, $sql) === TRUE) {
				$retval = mysqli_insert_id($conn);
				
			} else {
				$retval = "Some Problem haas Occured";
			}	
    return $retval;
}
 //------------------------> Delete Role <--------------
function deleteRole($id)
{
    global $conn;
    $sql = "DELETE FROM roles WHERE roleid = $id";
    if (mysqli_query($conn, $sql) === TRUE) {
				$last_id = mysqli_insert_id($conn);
				
			} else {
				echo"Some Problem has occurred";
			}	
}
 //-----------------------------------------------------------------------------------------------------------------
/*
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!? Permission FUNCTIONS ? !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
*/
 //-------------------------------> Save Permission <------------------------
function savePermission($permission,$desc)
{
    global $conn;
     $date=  date("Y-m-d");
    $createdby = $_SESSION['user'];
    $sql = "INSERT INTO permissions (name, description,createdon,createdby)
				VALUES('$permission','$desc',$date,$createdby)";
    
    if (mysqli_query($conn, $sql) === TRUE) {
				$last_id = mysqli_insert_id($conn);
			} else {
				
				echo"Some Problem has occurred";
			}	
    
}

// ------------------------> Get All Permissions <---------------------
function getAllPermissions()
{ 
    global $conn;
    $sql = "SELECT * FROM Permissions";
	
	//Step-2: Query execution
	$result = mysqli_query($conn, $sql);
	
	//Step-3: Get count of records
	$recordsFound = 0;
    $recordsFound=mysqli_num_rows($result);			
	
	if ($recordsFound > 0)
    {
        $roles =array();
        $role =array();
        while($row = mysqli_fetch_assoc($result))
        {
            $role['permissionid'] = $row['permissionid'];
            $role['name'] = $row['name'];
            $role['description'] = $row['description'];
            array_push($roles,$role);
        }
        return $roles;
    }
    else
        return null;
}
//---------------------> Get Permission by  ID <---------------------------
function getPermissionById($id)
{ 
    global $conn;
    $sql = "SELECT * FROM Permissions WHERE permissionid = $id";
	
	//Step-2: Query execution
	$result = mysqli_query($conn, $sql);
	//Step-3: Get count of records
	$recordsFound = 0;
    $recordsFound=mysqli_num_rows($result);			
	
	if ($recordsFound > 0)
        return mysqli_fetch_assoc($result);
    else
        return null;

}

//------> Validate Permission <-------------
function validatePermission($permission,$isNew)
{
    global $conn;
    if($permission == "")
        return false;
    if($isNew)
    {
        $permissions = getAllPermissions($conn);
        while($perm =  mysqli_fetch_assoc($permissions))
        {
            if($perm['name'] == $permission)
                return false;
        }
    }
    return true;
}
//------------------------>Update Permission <--------------------
function updatePermission($permission)
{
    global $conn;
    $id = $permission['permissionid'];
    $name = $permission['name'];
    $desc = $permission['description'];
     $sql = "UPDATE Permissions  SET name = '$name' ,description= '$desc' WHERE permissionid = $id";
    if (mysqli_query($conn, $sql) === TRUE) {
				$last_id = mysqli_insert_id($conn);
				
			} else {
				echo"Some Problem has occurred";
			}	
    
}
 //------------------------> Delete Permission <--------------
function deletePermission($id)
{
    global $conn;
    $sql = "DELETE FROM permissions WHERE permissionid = $id";
    if (mysqli_query($conn, $sql) === TRUE) {
				$last_id = mysqli_insert_id($conn);
				
			} else {
				echo"Some Problem has occurred";
			}	
}

/*
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! SAVE LOGIN HISTORY !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
*/
function saveHistory()
{
    global $conn;
    $userId=$_SESSION["user"];
$sql = "SELECT * FROM users WHERE userid=$userId";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$login=$row["login"];
$ip = '';
if (isset($_SERVER['HTTP_CLIENT_IP']))
    $ip = $_SERVER['HTTP_CLIENT_IP'];
else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
else if(isset($_SERVER['HTTP_X_FORWARDED']))
$ip = $_SERVER['HTTP_X_FORWARDED'];
else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
$ip = $_SERVER['HTTP_FORWARDED_FOR'];
else if(isset($_SERVER['HTTP_FORWARDED']))
$ip = $_SERVER['HTTP_FORWARDED'];
else if(isset($_SERVER['REMOTE_ADDR']))
$ip = $_SERVER['REMOTE_ADDR'];
else
$ip = 'UNKNOWN';
echo $ip;
$date = date('Y-m-d H:i:s');
$sql="Insert into loginhistory VALUES (NULL,$userId,'".$login."'".
        ",'".$date."','".$ip."')";

if (mysqli_query($conn, $sql) === TRUE) {

} else {
    ?><script>alert("Some Problem has occurred while mainting history");</script><?php
}
}

// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! CITITES FUNCTIONS !!!!!!!!!!!!!!!!!!!!!!!!!!!
function getCities($countryId)
{
    global $conn;
    $countryId = intval($countryId);
    $sql = "SELECT * FROM city WHERE countryid = $countryId";

    //Step-2: Query execution
    $result = mysqli_query($conn, $sql);

    //Step-3: Get count of records
    $recordsFound = 0;
    $recordsFound=mysqli_num_rows($result);

    if ($recordsFound > 0)
    {
        $cities = array();
        $city = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $city['id']= $row['id'];
            $city['name']= $row['name'];

            array_push($cities,$city);
        }
        return $cities;
    }
    else
        return null;
}

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! ROLE-PERM FUNCTIONS !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
function saveRolePerm($role , $perm)
{
    global $conn;
    $sql = "INSERT INTO role_permission (roleid,permissionid)
				VALUES('$role','$perm')";

    if (mysqli_query($conn, $sql) === TRUE) {
        $last_id = mysqli_insert_id($conn);

    } else {

        $last_id = "Some Problem has occurred";
    }
    return $last_id;
}

// --------------------------> get All RolePerms
function getAllRolePerms()
{
    global $conn;
    $sql = "SELECT * FROM role_permission";

    //Step-2: Query execution
    $result = mysqli_query($conn, $sql);

    //Step-3: Get count of records
    $recordsFound = 0;
    $recordsFound=mysqli_num_rows($result);

    if ($recordsFound > 0)
    {
        $users =array();
        $user =array();
        while($row = mysqli_fetch_assoc($result))
        {
            $user['id'] = $row['id'];
            $user['roleid'] = $row['roleid'];
            $user['permissionid'] = $row['permissionid'];
             $role = getRoleById($user["roleid"]);
            $perm = getPermissionById($user['permissionid']);
            $user['rolename'] = $role['name'];
            $user['permissionname'] = $perm['name'];
            array_push($users,$user);
            //  $users.push($user);
        }
        return $users;
    }
    else
        return null;
}

// ---------> get Role Perm by Id
function getRolePermById($id)
{
    global $conn;
    $sql = "SELECT * FROM role_permission WHERE id = $id";
    //Step-2: Query execution
    $result = mysqli_query($conn, $sql);
    //Step-3: Get count of records
    $recordsFound = 0;
    $recordsFound=mysqli_num_rows($result);
    if ($recordsFound > 0)
    {
        $user = array();
        $row = mysqli_fetch_assoc($result);

        $user['id'] =       $row['id'];
        $user['roleid'] =    $row['roleid'];
        $user['permissionid'] =   $row['permissionid'];
           return $user;
    }
    else
        return null;

}

function deleteRolePerm($id)
{
    global $conn;
    $sql = "DELETE FROM role_permission WHERE id = $id";
    if (mysqli_query($conn, $sql) === TRUE) {
        $last_id = mysqli_insert_id($conn);

    } else {
        echo"Some Problem has occurred";
    }
}

function updateRolePerm($role_perm)
{
    global $conn;
    $role = $role_perm['roleid'];
    $perm = $role_perm['permissionid'];
    $id = $role_perm['id'];
    $sql = "UPDATE role_permission  SET roleid = '$role' ,permissionid= '$perm' WHERE id = $id";
    if (mysqli_query($conn, $sql) === TRUE) {
        $retval = mysqli_insert_id($conn);

    } else {
        $retval = "Some Problem haas Occured";
    }
    return $retval;
}

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! User - ROLE FUNCTIONS !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
function saveUserRole($role , $perm)
{
    global $conn;
    $sql = "INSERT INTO user_role (roleid,userid)
				VALUES('$role','$perm')";

    if (mysqli_query($conn, $sql) === TRUE) {
        $last_id = mysqli_insert_id($conn);

    } else {

        $last_id = "Some Problem has occurred";
    }
    return $last_id;
}

// --------------------------> get All RolePerms
function getAllUserRoles()
{
    global $conn;
    $sql = "SELECT * FROM user_role";

    //Step-2: Query execution
    $result = mysqli_query($conn, $sql);

    //Step-3: Get count of records
    $recordsFound = 0;
    $recordsFound=mysqli_num_rows($result);

    if ($recordsFound > 0)
    {
        $users =array();
        $user =array();
        while($row = mysqli_fetch_assoc($result))
        {
            $user['id'] = $row['id'];
            $user['roleid'] = $row['roleid'];
            $user['userid'] = $row['userid'];
            $role = getRoleById($user["roleid"]);
            $temp = getUserById($user['userid']);
            $user['rolename'] = $role['name'];
            $user['username'] = $temp['name'];
            array_push($users,$user);
            //  $users.push($user);
        }
        return $users;
    }
    else
        return null;
}

// ---------> get Role Perm by Id
function getUserRoleById($id)
{
    global $conn;
    $sql = "SELECT * FROM user_role WHERE id = $id";
    //Step-2: Query execution
    $result = mysqli_query($conn, $sql);
    //Step-3: Get count of records
    $recordsFound = 0;
    $recordsFound=mysqli_num_rows($result);
    if ($recordsFound > 0)
    {
        $user = array();
        $row = mysqli_fetch_assoc($result);

        $user['id'] =       $row['id'];
        $user['roleid'] =    $row['roleid'];
        $user['userid'] =   $row['userid'];
        return $user;
    }
    else
        return null;

}

function deleteUserRole($id)
{
    global $conn;
    $sql = "DELETE FROM user_role WHERE id = $id";
    if (mysqli_query($conn, $sql) === TRUE) {
        $last_id = mysqli_insert_id($conn);

    } else {
        echo"Some Problem has occurred";
    }
}

function updateUserRole($role_perm)
{
    global $conn;
    $role = $role_perm['roleid'];
    $user = $role_perm['userid'];
    $id = $role_perm['id'];
    $sql = "UPDATE user_role  SET roleid = '$role' ,userid= '$user' WHERE id = $id";
    if (mysqli_query($conn, $sql) === TRUE) {
        $retval = mysqli_insert_id($conn);

    } else {
        $retval = "Some Problem haas Occured";
    }
    return $retval;
}

?>