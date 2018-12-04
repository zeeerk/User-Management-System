<?php  require_once("myFunctions.php")?>
 <?php
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! USER FUNCTIONS !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
if(isset($_POST["action"]))
{
    if($_POST["action"] == "getAllUsers")
    {
        $users = getAllUsers();
        echo json_encode($users);
    }
    
    else if($_POST["action"] == "deleteUser")
    {
        $id = $_POST['id'];
        deleteUser($id);
        $message = "Deleted Sucessfully...";
        echo json_encode($message);
    }
    else if($_POST["action"] == "editUser")
    {
         $id = $_POST['id'];
        $user = getUserById($id);
        echo json_encode($user);
        
    }
    else if($_POST["action"] == "getCountries")
    {
        $countries = getAllCountries();
        echo  json_encode($countries);
    }
    else if($_POST["action"] == "getCities")
    {
        $id = $_POST["cid"];
        $cities = getCities($id);
        echo json_encode($cities);
    }
    else if($_POST["action"] == "saveUser")
    {
        $isNew;
        $user = array();
        $userObj = $_POST['user'];
        $user['login'] = $userObj['login'];
        $user['password'] = $userObj['password'];
        $user['name'] = $userObj['name'];
        $user['email'] = $userObj['email'];
        $user['countryid'] = $userObj['countryid'];
        $user['cityid'] = $userObj['cityid'];
        $user['isadmin'] = $userObj['isadmin'];
        $isNew = $_POST['isNew'];
        if($isNew == "true")
        {
            $userObj = saveUser($user);
            $userObj['isNew'] = true;
            echo json_encode($userObj);
        }
        else
         {
                $temp = getUserByLogin($user['login'],$user['password']);
                $user['userid'] = $temp['userid'];
                $userObj = updateUser($user);
                 $userObj['isNew'] = false;
                 echo json_encode($userObj);
            }

    }
    else if($_POST['action'] == "getAllRoles")
    {
        $roles = getAllRoles();
        echo json_encode($roles);
    }
    else if($_POST['action'] == "deleteRole")
    {
        $id = $_POST['id'];
        deleteRole($id);
        $msg = "Deleted Sucessfully";
        echo json_encode($msg);
    }
    else if($_POST['action'] == "editRole")
    {
        $id = $_POST['id'];
        $role = getRoleById($id);
        echo json_encode($role);
    }
    else if($_POST['action'] == "saveRole")
    {
       $isNew = $_POST['isNew'];
       $name = $_POST['role'];
       $desc = $_POST['desc'];
       if($isNew == "true")
       {
            $retval = saveRole($name,$desc);
            echo json_encode($retval);
       }
       else
        {
            $role = array();
            $id = $_POST['id'];
            $role['roleid'] =$id;
            $role['name'] = $name;
            $role['description'] =$desc;
            $retval = updateRole($role);
            echo json_encode($retval);
        }

    }
    else if($_POST['action'] == "getAllPerms")
    {
        $roles = getAllPermissions();
        echo json_encode($roles);
    }
    else if($_POST['action'] == "deletePerm")
    {
        $id = $_POST['id'];
        deletePermission($id);
        $msg = "Deleted Sucessfully";
        echo json_encode($msg);
    }
    else if($_POST['action'] == "editPerm")
    {
        $id = $_POST['id'];
        $role = getPermissionById($id);
        echo json_encode($role);
    }
    else if($_POST['action'] == "savePerm")
    {
        $isNew = $_POST['isNew'];
        $name = $_POST['perm'];
        $desc = $_POST['desc'];
        if($isNew == "true")
        {
            $retval = savePermission($name,$desc);
            echo json_encode($retval);
        }
        else
        {
            $role = array();
            $id = $_POST['id'];
            $role['permissionid'] =$id;
            $role['name'] = $name;
            $role['description'] =$desc;
            $retval = updatePermission($role);
            echo json_encode($retval);
        }

    }
    else if($_POST['action'] == "saveRolePerm")
    {
        $role = $_POST['role'];
        $perm = $_POST['perm'];
        $isNew = $_POST['isNew'];
        if($isNew == "true") {
            $retval = saveRolePerm($role, $perm);
            echo json_encode($retval);
        }
        else
        {
            $role_perm['id'] = $_POST['id'];
            $role_perm['roleid'] = $role;
            $role_perm['permissionid'] = $perm;
            $retval = updateRolePerm($role_perm);
            echo json_encode($retval);
        }
    }
    else if($_POST['action'] == "getRolePerms")
    {
      $role_perms = getAllRolePerms();
        echo json_encode($role_perms);
    }
    else if($_POST['action'] == "editRolePerm")
    {
        $id = $_POST['id'];
        $role_perm = getRolePermById($id);
        echo json_encode($role_perm);
    }
    else if($_POST['action'] == "deleteRolePerm")
    {
        $id = $_POST['id'];
        deleteRolePerm($id);
        echo json_encode("User Deleted Sucessfully");

    }
    else if($_POST['action'] == "saveUserRole")
    {
        $role = $_POST['role'];
        $perm = $_POST['user'];
        $isNew = $_POST['isNew'];
        if($isNew == "true") {
            $retval = saveUserRole($role, $perm);
            echo json_encode($retval);
        }
        else
        {
            $role_perm['id'] = $_POST['id'];
            $role_perm['roleid'] = $role;
            $role_perm['userid'] = $perm;
            $retval = updateUserRole($role_perm);
            echo json_encode($retval);
        }
    }
    else if($_POST['action'] == "getUserRoles")
    {
        $role_perms = getAllUserRoles();
        echo json_encode($role_perms);
    }
    else if($_POST['action'] == "editUserRole")
    {
        $id = $_POST['id'];
        $role_perm = getUserRoleById($id);
        echo json_encode($role_perm);
    }
    else if($_POST['action'] == "deleteUserRole")
    {
        $id = $_POST['id'];
        deleteUserRole($id);
        echo json_encode("User Role Deleted Sucessfully");

    }
}
?>