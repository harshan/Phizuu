<?php

session_start();

require_once ('../../../config/config.php');
require_once ('../../../database/Dao.php');
require_once ("../../../controller/admin_session_controller.php");
require_once ("../../../view/admin/users/list_users.php");
require_once ("../../../model/UserInfo.php");
//set_error_handler("errorHandler", E_ALL);

$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

$dao = new Dao();
$sql = "SELECT * FROM package ORDER BY id";
$packages = $dao->toArray($sql);

switch ($action) {
    case 'find_users':
        $userinput = $_REQUEST['searchkeyword'];
        if (isset($_REQUEST['userids'])) {
            $userIds = $_REQUEST['userids'];
        }

        $sql = "SELECT username FROM user where username like '%$userinput%' and id not in ($userIds)";
        $arr = $dao->toArray($sql);
        $userArr = '';
        foreach ($arr as $val) {
            $userArr.=$val['username'] . ',';
        }
        echo $userArr;
        break;
    case 'find_user_account':
        $username = $_REQUEST['userAccount'];
        if (isset($_REQUEST['userids'])) {
            $userIds = $_REQUEST['userids'];
        }
        $sql = "SELECT id,username FROM user where username ='$username' and id not in ($userIds)";
        $arr = $dao->toArray($sql);
        if (isset($arr[0])) {

            echo $arr[0]["id"] . ',' . $arr[0]["username"];
            $id = $arr[0]["id"];
            $userName = $arr[0]["username"];
            echo $array = '<div style="clear: both" id="row_' . $id . '">
            <div style="float: left;width: 200px;height:20px"><input type="hidden" id="user_id" value="' . $id . '"/>' . $userName . '</div>
            <div style="float: left" id=delete_' . $id . '><img src="../../../images/delete_icon.png" style="cursor:pointer"/></div>
            </div>';
        } else {
            return false;
        }

        break;

    case 'add_account_manager':
        $error = array();
        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];
        $username = $_REQUEST['username'];
        $userType = $_REQUEST['userType'];
        $userList = $_REQUEST['userList'];
        //check user name and email address exit.
        $sql = "SELECT username from user where username ='$username'";
        $arr = $dao->toArray($sql);
        if (!isset($arr[0]['username'])) {
            $sql = "SELECT username from manager where username ='$username'";
            $arr = $dao->toArray($sql);
        }

        if (isset($arr[0]['username'])) {
            $error[] = 'User name already exist!';
        }
        $sql = "SELECT email from user where email ='$email'";
        $arr = $dao->toArray($sql);
        if (!isset($arr[0]['email'])) {
            $sql = "SELECT email from manager where email ='$email'";
            $arr = $dao->toArray($sql);
        }
        if (isset($arr[0]['email'])) {
            $error[] = 'Email address already exist!';
        }

        if (count($error) > 0) {
            $errorMsg = '';
            foreach ($error as $value) {
                $errorMsg.= "<div>" . $value . "</div>";
            }
            $json->error = TRUE;
            $json->msg = $errorMsg;
        } else {
            //insert new manager
            $sql = "insert into manager(email,username,password,user_type,status) values('$email','$username','".md5($password)."',$userType,1)";
            $dao->query($sql);
            $manager_id = mysql_insert_id();
            foreach ($userList as $val) {
                $sql = "insert into manager_accounts(manager_id,user_id) values($manager_id,$val)";
                $dao->query($sql);
            }
            if ($userType == 1) {
                $userTypeName = 'Single account';
            } elseif ($userType == 2) {
                $userTypeName = 'Multiple accounts';
            }
            $json->error = FALSE;
            $json->msg = "Record added successfuly";
            $json->html = '<tr id="tr_' . $manager_id . '"  style="border: 1px solid #201f1f">
                            <td>' . $username . '</td>
                            <td>' . $email . '</td>
                            <td>' . $userTypeName . '</td>
                           
                            <td style="width: 80px;text-align: center">Active</td>
                            <td style="width: 50px;text-align: center"><img src="../../../images/file.png" style="cursor: pointer"  onclick="editManager(' . $manager_id . ')"/></td>
                            <td style="width: 50px;text-align: center"><img src="../../../images/delete_icon.png" style="cursor: pointer" onclick="deleteManager(' . $manager_id . ')"/></td>
                        </tr>';
        }
        echo json_encode($json);
        break;
    case 'edit_save_account_manager':
        $manager_id = $_REQUEST['id'];
        $userType = $_REQUEST['userType'];
        $userStatus = $_REQUEST['userStatus'];
        $userList = $_REQUEST['userList'];
        //update manager table
        $sql = "update manager set user_type=$userType,status=$userStatus where id=$manager_id";
        $dao->query($sql);
        //delete all users from manager account table
        $sql="delete from manager_accounts where manager_id=$manager_id";
        $dao->query($sql);
        //inser all user in to manager account table
        foreach($userList as $val){
            $sql="insert into manager_accounts(manager_id,user_id) values($manager_id,$val) ";
            $dao->query($sql);
        }
        //get updated details
        $sql = "select * from manager where id=$manager_id";
        $arr = $dao->toArray($sql);
        if ($arr[0]['user_type'] == 1) {
                $userTypeName = 'Single account';
            } elseif ($arr[0]['user_type'] == 2) {
                $userTypeName = 'Multiple accounts';
            }
            if ($arr[0]['status'] == 0) {
                $userStatus = 'Inactive';
            } elseif ($arr[0]['status'] == 1) {
                $userStatus = 'Active';
            }
        $json->error = FALSE;
        $json->html = '<tr id="tr_' . $manager_id . '"  style="border: 1px solid #201f1f">
                            <td>' . $arr[0]['username'] . '</td>
                            <td>' . $arr[0]['email'] . '</td>
                            <td>' . $userTypeName . '</td>
                           
                            <td style="width: 80px;text-align: center">'.$userStatus.'</td>
                            <td style="width: 50px;text-align: center"><img src="../../../images/file.png" style="cursor: pointer"  onclick="editManager(' . $manager_id . ')"/></td>
                            <td style="width: 50px;text-align: center"><img src="../../../images/delete_icon.png" style="cursor: pointer" onclick="deleteManager(' . $manager_id . ')"/></td>
                        </tr>';
        echo json_encode($json);

        break;
    case 'delete_manager_account':
        $id = $_REQUEST['id'];
        $sql = "delete from manager where id=$id";
        $dao->query($sql);
        $sql = "delete from manager_accounts where manager_id=$id";
        $dao->query($sql);

        break;
    case 'changePssword':
        $id = $_REQUEST['id'];
        $password = $_REQUEST['password'];
        
        $sql = "update manager set password = '".md5($password)."' where id=$id";
        $dao->query($sql);
        echo 'ok';

        break;
    
    case 'edit_manager_account':
        //get manager details
        $id = $_REQUEST['id'];
        $sql = "SELECT * FROM manager where id=$id ";
        $result = $dao->query($sql);
        $row = mysql_fetch_array($result);
        //get user account related to manager
        $managerId= $row['id'];
        $sql1 = "SELECT * FROM manager_accounts inner join user on manager_accounts.user_id = user.id where manager_accounts.manager_id= $managerId";
        $users = $dao->toArray($sql1);
        $userArr = "";
        foreach($users as $val){ 
            $userArr.= $val['id'];
            $userArr.=",";
        }
        ob_start();
        include '../../../view/admin/users/edit_manager_form.php';
        $out1 = ob_get_contents();
        ob_end_clean();
        
        $json->stack = $userArr;
        $json->html = $out1;
        echo json_encode($json);
        break;
    case 'show_user_module':
        include '../../../view/admin/users/user_management.php';
        break;
    case 'show_account_managers_module':
        include '../../../view/admin/users/user_account_manager_management.php';
        break;
    case 'delete_user':
        $id = $_POST['id'];

        $sql = "DELETE FROM user WHERE id=$id";
        $dao->query($sql);
        break;
    case 'inline_edit_normal':
        $pair = explode('|', $_POST['id']);
        $field = $pair[0];
        $id = $pair[1];
        $value = $_POST['value'];

        $sql = "UPDATE user SET `$field`='$value' WHERE id = $id";
        $dao->query($sql);
        $retunValue = '';

        if ($field == 'package_id') {
            $retunValue = $packages[$value - 1]['name'];
        } elseif ($field == 'paid') {
            if ($value == 1) {
                $retunValue = 'Yes';
            } else {
                $retunValue = 'No';
            }
        } elseif ($field == 'is_suspended') {
            if ($value == 0) {
                $retunValue = 'Yes';
            } else {
                $retunValue = 'No';
            }
        } elseif ($field == 'status') {
            if ($value == 0) {
                $retunValue = 'App Wizard';
            } elseif ($value == 1) {
                $retunValue = 'CMS';
            } elseif ($value == 3) {
                $retunValue = 'Freezed';
            } else {
                $retunValue = 'Built';
            }
        } else {
            $retunValue = $value;
        }
        echo $retunValue;
        break;

    case 'query_data':
        $page = $_POST['page'];
        $rp = $_POST['rp'];
        /* $sortname = $_POST['sortname'];
          $sortorder = $_POST['sortorder'];

          if (!$sortname) $sortname = 'name';
          if (!$sortorder) $sortorder = 'desc';

          $sort = "ORDER BY $sortname $sortorder"; */

        if (!$page)
            $page = 1;
        if (!$rp)
            $rp = 10;

        $start = (($page - 1) * $rp);

        $limit = "LIMIT $start, $rp";

        $where = "WHERE username LIKE '%{$_POST['username']}%'";
        if ($_POST['app_id'] != '') {
            $where.= " AND app_id='{$_POST['app_id']}'";
        }
        if ($_POST['user_id'] != '') {
            $where.= " AND id='{$_POST['user_id']}'";
        }
        if ($_POST['app_name'] != '') {
            $where.= " AND app_name LIKE '%{$_POST['app_name']}%'";
        }
        if ($_POST['email'] != '') {
            $where.= " AND email LIKE '%{$_POST['email']}%'";
        }
        if ($_POST['status'] != '') {
            $where.= " AND status='{$_POST['status']}'";
        }

        $sql = "SELECT id,username,app_id,app_name, email,package_id,paid,status,is_suspended FROM user $where ORDER BY id $limit";
        $users = $dao->toArray($sql);

        $sql = "SELECT count(id) FROM user $where ";
        $result = $dao->query($sql);
        $row = mysql_fetch_array($result);
        $total = $row[0];

        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: text/x-json");


        /* $rows = array();
          foreach ($rowsRes as $row) {

          //Package
          $packageId = $row[5];
          $paid = $row[6];
          $suspended = $row[8];
          $appId = $row[2];

          if ($packageId == 1 || ($paid == 0 && $suspended == 1)) {
          $row[5] = $packages[0]['name'];
          } else {
          $row[5] = $packages[$packageId-1]['name'];
          }

          //Suspended
          if ($suspended == 0) {
          $row[8] = 'Yes';
          } else {
          $row[8] = 'No';
          }

          //Paid
          if ($paid == 1) {
          $row[6] = 'Yes';
          } else {
          $row[6] = 'No';
          }

          //Status
          $status = $row[7];
          if ($status == 0) {
          $row[7] = 'App Wizard';
          } elseif ($status == 1) {
          $row[7] = 'CMS';
          } elseif ($status == 3) {
          $row[7] = 'Freezed';
          } else {
          $row[7] = 'Invalid';
          }

          $sql = "SELECT * FROM module WHERE app_id = $appId";
          $permisions = $dao->toArray($sql);

          $modulesList = "";

          if($status!=0){
          foreach ($modules as $module) {
          $permision = isset($permisions[0])?$permisions[0][$module[1]]:0;
          if($permision==1)
          $checked = 'checked';
          else
          $checked = '';
          $modulesList .= "<input class='module_sel' type='checkbox' id='".$module[1]."|".$appId."' $checked/>$module[0] ";
          }
          }


          $row[9] = "<div class='cssstyle'>".$modulesList."</div>";

          //Active
          //$notActive =

          $rows[] = array('id'=>$row[0],'cell'=>$row);
          } */

        $jsonObj = array('html' => getUsersHTML($users, $packages), 'total' => $total);
        echo json_encode($jsonObj);
        break;
    case 'edit_permisions':
        $data = $_POST['data'];
        $value = $_POST['value'];
        $items = explode('|', $data);

        $sql = "SELECT id FROM module WHERE app_id={$items[1]}";
        if (mysql_num_rows($dao->query($sql)) == 0) {
            $sql = "INSERT INTO module (app_id) VALUES ({$items[1]})";
            $dao->query($sql);
        }

        $sql = "UPDATE module SET {$items[0]} = $value WHERE app_id={$items[1]}";
        $dao->query($sql);
        break;
    case 'show_package_module':
        include '../../../view/admin/package/package_management.php';
        break;
    case 'query_data_package':
        $sql = "SELECT `id`,`name`,`video_limit`,`music_limit`,`photo_limit`,`message_limit`,`home_screen_images`,`album_limit` FROM package";
        $packages = $dao->toArray($sql);

        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: text/x-json");



        /* $rows = array();
          foreach ($rowsRes as $row) {
          $id = $row[0];
          $row[1] = "<span class='cssstyle edit' id='name|$id'>".$row[1]."</span>";
          $row[2] = "<span class='cssstyle edit_right' id='video_limit|$id'>".$row[2]."</span>";
          $row[3] = "<span class='cssstyle edit_right' id='music_limit|$id'>".$row[3]."</span>";
          $row[4] = "<span class='cssstyle edit_right' id='photo_limit|$id'>".$row[4]."</span>";
          $row[5] = "<span class='cssstyle edit_right' id='message_limit|$id'>".$row[5]."</span>";
          $row[6] = "<div class='cssstyle edit_right' id='home_screen_images|$id'>".$row[6]."</div>";
          $row[7] = "<div class='cssstyle edit_right' id='album_limit|$id'>".$row[7]."</div>";
          //Active
          //$notActive =

          $rows[] = array('id'=>$row[0],'cell'=>$row);
          } */
        include '../../../view/admin/package/list_package.php';
        echo getUsersHTMLPackage($packages);
        break;
    case 'inline_edit_normal_package':
        $pair = explode('|', $_POST['id']);
        $field = $pair[0];
        $id = $pair[1];
        $value = $_POST['value'];

        $sql = "UPDATE package SET `$field`='$value' WHERE id = $id";
        $dao->query($sql);
        $retunValue = '';

        echo $value;
        break;
    case 'delete_package':
        $id = $_POST['id'];

        $sql = "DELETE FROM package WHERE id=$id";
        $dao->query($sql);

        break;
    case 'add_new_package':
        $sql = "SELECT MAX(id) as last_id FROM package";
        $arr = $dao->toArray($sql);
        $nextId = $arr[0]['last_id'] + 1;

        $sql = "INSERT INTO package(id,name) VALUES ($nextId,'New Package (Please Edit this)')";
        $dao->query($sql);
        break;
    case 'change_password':
        $pwd = $_POST['password'];
        $id = $_POST['id'];
        $sql = "UPDATE user SET password =MD5('$pwd') WHERE id = $id";
        $dao->query($sql);
        echo $pwd;
        break;
    case 'get_module_list_ajax':
        $sql = "SHOW COLUMNS FROM module";
        $columns = $rows = $dao->toArray($sql);

        $modules = array();
        foreach ($columns as $column) {
            $columnName = $column['Field'];

            if ($columnName != 'id' && $columnName != 'app_id') {
                $moduleName = ucwords(str_replace('_', ' ', $columnName));

                $modules[] = array($moduleName, $columnName);
            }
        }
        $userInfo = new UserInfo($_POST['id']);
        $userArr = $userInfo->getUserInfo();
        $appId = $userArr['app_id'];
        $dao = new Dao();
        $sql = "SELECT * FROM module WHERE app_id = $appId";
        $permisions = $dao->toArray($sql);

        $modulesList = "";

        foreach ($modules as $module) {
            $permision = isset($permisions[0]) ? $permisions[0][$module[1]] : 0;
            if ($permision == 1)
                $checked = 'checked';
            else
                $checked = '';
            $modulesList .= "<input class='module_sel' type='checkbox' id='" . $module[1] . "|" . $appId . "' $checked/>$module[0] ";
        }
        echo $modulesList;
        break;
    default:
        trigger_error("No valid action!");
}

function errorHandler($errno, $errstr, $errfile, $errline) {
    include '../../../view/common/error.php';
}

function browserError() {
    include '../../../view/common/browser_error.php';
    exit;
}

?>
