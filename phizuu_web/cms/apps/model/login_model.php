<?php
@session_start();
class LoginModel {

    function checkUser($user,$pwd) {
        $sql= "select * from  `user` where username='".addslashes($user)."' AND password = '$pwd' AND is_suspended = 0";
        $result= mysql_query($sql) or die(mysql_error());

        $count=0;
        $count = mysql_num_rows($result);
        if($count != 0) {
            $row=mysql_fetch_array($result);

            @session_destroy();
            @session_start();

            $_SESSION['user_id']=$row['id'];
            $_SESSION['app_id']=$row['app_id'];
            $_SESSION['user_name']=$row['username'];

            if($row['status']==0) {
                return 2; //Inactive user
            } else if($row['status']==3)  {
                return 3; //Freezed User
            } else {
                return 1; //Active user
            }
        }
        else {
            return 0; //Not a user
        }

    }

    function checkUser2($user) {
        $sql= "select * from  `user` where username='".addslashes($user)."'";
        $result= mysql_query($sql) or die(mysql_error());

        $count=0;
        $count = mysql_num_rows($result);
        if($count != 0) {
            $row=mysql_fetch_array($result);

            @session_destroy();
            @session_start();

            $_SESSION['user_id']=$row['id'];
            $_SESSION['app_id']=$row['app_id'];
            $_SESSION['user_name']=$row['username'];

            if($row['status']==0) {
                return 2; //Inactive user
            } else if($row['status']==3)  {
                return 3; //Freezed User
            } else {
                return 1; //Active user
            }
        }
        else {
            return 0; //Not a user
        }

    }

//get navigation modules

    function checkNavModules() {
        $sql= "select * from  `module` where app_id='".addslashes($_SESSION['app_id'])."'";
        $result= mysql_query($sql) or die(mysql_error());

        $count=0;
        $count = mysql_num_rows($result);
        if($count != 0) {
            $this->helper = new Helper();

            $this->item = $this->helper->_row($result);
            return $this->item;
        }

    }



    function checkUserName($user) {
        $sql= "select username,password from  `user` where username='".addslashes($user)."'";
        $result= mysql_query($sql) or die(mysql_error());

        $count = mysql_num_rows($result);
        if($count >0) {
            $this->helper = new Helper();
            return $this->helper->_result($result);

        }
        else {
            return false;
        }

    }

    function getUserSettings($id) {
        $sql= "select * from  `setting` where user_id='".addslashes($_SESSION['user_id'])."' AND  type=".addslashes($id)." ORDER BY user_id asc";
        $result= mysql_query($sql) or die(mysql_error());


        $this->helper = new Helper();
        return $this->helper->_result($result);

    }

//admin section
    function checkAdminUser($user,$pwd) {
        $sql= "select * from  `sf_guard_user` where username='".addslashes($user)."' AND password = '$pwd'";
        $result= mysql_query($sql) or die(mysql_error());

        $count=0;
        $count = mysql_num_rows($result);
        if($count != 0) {
            $row=mysql_fetch_array($result);

            @session_destroy();
            @session_start();

            $_SESSION['admin_user_id']=$row['id'];
            $_SESSION['is_super_admin']='yes';
            $_SESSION['is_admin']='yes';

            return true;
        }
        else {
            return false;
        }

    }


}

?>