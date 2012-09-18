<?php
@session_start();
include('../../../config/error_config.php');
require_once "../../../model/login/Login.php";
require_once '../../../database/Dao.php';
require_once ('../../../config/config.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php
$login = new Login();
$users = $login->getUserAccountList();
$width = count($users)*115;
if (isset($_REQUEST['userId'])) {
   
    $admin = TRUE;
    $status = $login->loginUser($_REQUEST['userName'],'',$admin);
    
    if ($status === FALSE) {
        $error = "Invalid login";
    } elseif ($status == 1) { // CMS User
        if ($_SESSION['modules'][0]['payments'] == '1') { //No payments - redirect to payments
            header("Location: ../../../controller/modules/payments/PaymentController.php?action=view");
        } else {
            header("Location: ../../../view/user/music/music.php");
        }
        exit;
    } elseif ($status == 0) { // App Wizard User
        header("Location: ../../../controller/modules/app_wizard/AppWizardControllerNew.php");
        exit;
    } elseif ($status == 3 || $status == 4) { // Freezed user
        $error = "CMS is freezed until your application is reviewed by Apple";
    } elseif ($status == 5) { // Not confirmed user
        $error = "Before login, please confirm your email address by clicking the link in the email alredy sent to you subjected 'Welcome to phizuu'.";
    } else {
        $error = "Invalid user status";
    }
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Phizuu Application</title>
        <link href="../../../css/styles.css" rel="stylesheet" type="text/css" />

    </head>


    <body>
        <div id="header">
            <div id="headerContent">
                <?php include("../../../view/user/common/header_manager.php"); ?>
            </div>
            <div style="position: absolute; background-image: url(../../../images/menu_bg.png);height: 80px;width: 100%"></div>
        </div>
        <div id="mainWideDiv" style="background: url('../../../images/menu_bg.png')">
            <div id="middleDiv2">

                <div id="body" style="background: url('../../../images/menu_bg.png'); border: 0;">
                    <div style="text-align: center;width: 900px;height: 500px;margin: auto">
                        <div style="text-align: center;margin: auto;width: <?php echo $width;?>px;padding-top: 100px">
                            <?php
                            foreach ($users as $val) {
                                $userDeteails = $login->getUserDetails($val['user_id']);
                               
                                ?>
                            
                            <div style="width: 100px;float: left;text-align: center;margin: auto;height: 100px;color:#fff;border-radius: 10px;border: solid 0px #fff;margin: 5px">
                                <a href="?userId=<?php echo $val['user_id']; ?>&userName=<?php echo $userDeteails[0]['username']; ?>">
                                    <div style="border-radius: 10px;border: solid 0px #fff;width: 70px;overflow: hidden;margin: auto;text-align: center;margin-top: 10px"><img src="../../../application_dirs/<?php echo $val['user_id']?>/Icon.png" width="70" height="70"/></div>
                                    <div style="float: left;width: 100px;text-align: center;margin: auto;text-decoration: none;color: #A5A1A0;font-size: 12px;white-space:pre-wrap;padding-top: 10px;font-weight: bold;font-family: arial"><?php echo $userDeteails[0]['username'];?></div>
                                </a>
                            </div>
                                <?php } ?>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <br class="clear"/> 
        <div id="footerInner" style="background: url('../../../images/menu_bg.png')">
            <div class="lineBottomInner" style="background: url('../../../images/menu_bg.png')"></div>
            <div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
        </div>
    </body>
</html>
