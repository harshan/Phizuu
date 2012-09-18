<?php

@session_start();
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Login
 *
 * @author Dhanushka
 */
class Login {

    function loginUser($username, $password, $admin = false) {

        if ($admin) {
            $sql = "SELECT * FROM  `user` WHERE username='$username'";
        } else {
            $sql = "SELECT * FROM  `user` WHERE username='$username' AND password = MD5('$password')";
        }

        $dao = new Dao();
        $userArr = $dao->toArray($sql);

        if (count($userArr) == 0) {
            return FALSE;
        } else {
            if ($userArr[0]['is_suspended'] != 0) {
                return 5;
            }

            $user = $userArr[0];

            $modules = NULL;
            if ($user['status'] == 1) {
                $modules = $this->_getModuleList($user['app_id']);
            }

            if ($user['status'] == 1 || $user['status'] == 0) {
                $this->_setSession($user, $modules);
            }

            return $user['status'];
        }
    }

    function loginManager($username, $password) {
        $sql = "SELECT * FROM  `manager` WHERE username='$username' AND password = MD5('$password')";
        $dao = new Dao();
        $managerArr = $dao->toArray($sql);
        if (count($managerArr) == 0) {
            return FALSE;
        } else {
            $manager = $managerArr[0];

            $modules = NULL;
            if ($manager['status'] == 1) {
                $_SESSION['manager_id'] = $manager['id'];
                $_SESSION['manager_name'] = $manager['username'];
                $_SESSION['user_type'] = $manager['user_type'];
                if ($manager['user_type'] == 1) {
                    $user_id = $manager['id'];
                    $sql = "SELECT * FROM  `manager_accounts` WHERE manager_id=$user_id";
                    $dao = new Dao();
                    $managerAccountArr = $dao->toArray($sql);

                    $userId = $managerAccountArr[0]['user_id'];
                    $sql = "SELECT * FROM  `user` WHERE id=$userId";
                    $dao = new Dao();
                    $userArr = $dao->toArray($sql);
                    $user = $userArr[0];
                    $modules = $this->_getModuleList($user['app_id']);
                    $this->_setSession($user, $modules);
                } else if ($manager['user_type'] == 2) {
                    $user_id = $manager['id'];
                    //get manage account details
                    $sql = "SELECT * FROM  `manager_accounts` WHERE manager_id=$user_id";
                    $dao = new Dao();
                    $managerAccountArr = $dao->toArray($sql);

                    return 'manager';
                }
            } else {
                return FALSE;
            }



            return $user['status'];
        }
    }

    function getUserAccountList() {
        //GEt all user accounts
        if (!isset($_SESSION['manager_id'])) {
            header("location:../../../controller/modules/login/?action=logout");
            break;
        }
        $manager_id = $_SESSION['manager_id'];
        $sql = "SELECT * FROM  `manager_accounts` WHERE manager_id=$manager_id";
        $dao = new Dao();
        $userAccountArr = $dao->toArray($sql);
        return $userAccountArr;
    }

    function getUserDetails($appId) {
        $sql = "SELECT * FROM  `user` WHERE app_id=$appId";
        $dao = new Dao();
        $userdetailArr = $dao->toArray($sql);
        return $userdetailArr;
    }

//    function loginToClientAccount(){
//        $sql = "SELECT * FROM  `user` WHERE username='$username'";
//        $dao = new Dao();
//        $userArr = $dao->toArray($sql);
//    }
    function adminFakeLogin($username) {
        
    }

    function _setSession($user, $modules = NULL) {
//        @session_destroy();
//        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['app_id'] = $user['app_id'];
        $_SESSION['user_name'] = $user['username'];

        if ($modules) {
            $_SESSION['modules'][0] = $modules;
        }

        //print_r($_SESSION);
    }

    function _getModuleList($appId) {
        if ($appId == 0)
            return NULL;

        $sql = "SELECT * FROM module WHERE app_id=$appId";

        $dao = new Dao();
        $array = $dao->toArray($sql, MYSQL_ASSOC);

        if (count($array) != 0) {
            $moduleArr = array();
            foreach ($array[0] as $moduleName => $value) {
                if ($moduleName != 'id' && $moduleName != 'app_id') {
                    if ($array[0]['recurrent_payments'] == '1') { // No permissions if no payments have been made
                        $moduleArr[$moduleName] = '0';
                        if ($moduleName == 'recurrent_payments') {
                            $moduleArr['payments'] = '1';
                        }
                    } else {
                        $moduleArr[$moduleName] = $value;
                        if ($moduleName == 'recurrent_payments') {
                            $moduleArr['payments'] = $value;
                        }
                    }
                }
            }
            return $moduleArr;
        } else {
            return NULL;
        }
    }

    function logout() {
        @session_destroy();
    }

    function sendPasswordForgetMail($email, $password, $username, $appName) {
        require_once "../../../config/app_key_values.php";
        $domain = $_SERVER["SERVER_NAME"];
        if ($_SERVER["SERVER_NAME"] == app_key_values::$LIVE_SERVER_DOMAIN) {
            $callbackURL = "http://$domain/" . app_key_values::$LIVE_SERVER_URL;
        } elseif ($_SERVER["SERVER_NAME"] == app_key_values::$TEST_SERVER_DOMAIN) {
            $callbackURL = "http://$domain/" . app_key_values::$TEST_SERVER_URL;
        } else {
            $callbackURL = "http://$domain/" . app_key_values::$LOCALHOST_SERVER_URL;
        }
        $m = new MAIL;
        // set from address
        $m->From('info@phizuu.com');
        // add to address
        $m->AddTo($email);
        // set subject
        $m->Subject('Password Reset Request - phizuu CMS');
        // set text message
        $m->Text('Please use html browser to view this email');

        $password = urlencode($password);

        $html = '<html>';
        $html .= '<body>';
        $html .= "Hey $username, <br/><br/>";
        $html .= "You have requested to reset password of the phizuu CMS for the application '$appName'. ";
        $html .= "Please click <a href='$callbackURL/cms/apps/controller/modules/login/?action=reset_password&id=$password&username=" . urlencode($username) . "'>here</a> to reset your password.<br/><br/>";
        $html .= "-Team phizuu";
        $html .= '</body>';
        $html .= '</html>';
        $html .= '</html>';

        $m->Html($html);

        return $m->Send();
    }

    function checkResetPasswordURL($username, $password) {
        $sql = "SELECT * FROM user WHERE username='$username' AND password='$password'";

        $dao = new Dao();
        $res = $dao->query($sql);
        if (mysql_numrows($res) == 0) {
            $sql = "SELECT * FROM manager WHERE username='$username' AND password='$password'";
            $dao = new Dao();
            $res = $dao->query($sql);
        }
        if (mysql_numrows($res) == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function checkManagerResetPasswordURL($username, $password) {
        $sql = "SELECT * FROM manager WHERE username='$username' AND password='$password'";

        $dao = new Dao();
        $res = $dao->query($sql);

        if (mysql_numrows($res) == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function resetPassword($username, $password) {
        $sql = "UPDATE user SET password=MD5('$password') WHERE username='$username'";

        $dao = new Dao();
        $res = $dao->query($sql);
        
        if (mysql_affected_rows() == 0) {
            $sql = "UPDATE manager SET password=MD5('$password') WHERE username='$username'";

            $dao = new Dao();
            $res = $dao->query($sql);
            echo mysql_affected_rows();
        }
        if (mysql_affected_rows() == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function resetManagerPassword($username, $password) {
        $sql = "UPDATE manager SET password=MD5('$password') WHERE username='$username'";

        $dao = new Dao();
        $res = $dao->query($sql);

        if (mysql_affected_rows() == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

}

?>