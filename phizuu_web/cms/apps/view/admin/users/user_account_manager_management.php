<?php
require_once "../../../controller/admin_users_controller.php";

$module = 'account_manager';
$str = "{"; //"{'1':'Garage Band','2':'Idol','3':'Rock Star'}"
$first = true;
foreach ($packages as $package) {
    if ($first) {
        $comma = '';
        $first = false;
    } else {
        $comma = ',';
    }
    $str .= "$comma'{$package['id']}':'{$package['name']}'";
}
$str .= "}";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>phizuu CMS - Admin</title>
        <link href="../../../css/styles.css" rel="stylesheet" type="text/css" />

        <style type="text/css">
            body{
                font-family: Tahoma;
                font-size: 12px;
                color: #616262;
            }

            #row_box input{
                padding: 0;
            }

            #row_box select{
                width: 95%;
                font-size: 10px;
            }

            .flexigrid div.bDiv td div .edit_right input{
                text-align: right;
            }

            .row_box {
                width: 958px;
                float: left;
                padding-top: 10px;
                overflow: hidden;
            }

            .row_box_data {
                width: 958px;
                float: left;
                padding-top: 1px;
                overflow: hidden;
            }

            .searchbox input, .searchbox select{
                width: 64px;
                height: 15px;
                border: 1px solid gray;
            }

            .title {
                height: 16px;
                padding: 4px;
                margin-right: 1px;
                color: #FFFFFF;
                font-size: 12px;
                background-color: #747c7e;
                float: left;
            }

            .data {
                height: 20px;
                padding: 4px;
                margin-right: 1px;
                font-size: 12px;
                background-color: #F3F3F3;
                float: left;
            }

            .button {
                cursor: pointer;
            }

            .moduleFloating {
                background-color:#043F53;
                color:#FFFFFF;
                display:none;
                position:absolute;
                width:830px;
                padding: 5px;
                height: 40px;
                z-index: 10;
            }
        </style>

    </head>


    <body>
        <img id="pointerArrow" src="../../../images/admin_top_module.png" style="display:none; position: absolute; z-index: 5"/>
        <div class="moduleFloating" id="module_list"></div>
        <div id="header">
            <div id="headerContent">
                <?php include("../../../view/admin/common/header.php"); ?>
            </div>
            <div style="position: absolute; background-image: url(../../../images/menu_bg.png);height: 80px;width: 100%"></div>
        </div>
        <div id="mainWideDiv">
            <div id="middleDiv2">
                
                    <?php // include("../../../view/admin/common/header.php");?>
                <?php include("../../../view/admin/common/navigator.php"); ?>
                
                <div style="height: 19px;clear: both"></div>
                <div id="successMsg">Record saved successfully  </div>
                <div class="row_box" style="padding-top: 20px">
                    <div id="lightBlueHeader2">

                        <div class="tahoma_14_white" id="lightBlueHeaderMiddle2" style="width: 940px">Manage User Account Managers</div>

                    </div>
                </div>
                <div style="float: right;cursor: pointer;margin-right: -8px" id="add_new"><img src="../../../images/add_new.png"/></div>
                <div id="add_new_form" style="display: none;clear: both">
                    <div class="row_box" style="padding-top: 20px">
                        <div id="lightBlueHeader2">

                            <div class="tahoma_14_white" id="lightBlueHeaderMiddle2" style="width: 940px">Add New Account Manager</div>

                        </div>
                        <div style="width: 400px;float: left">
                            <div><div style="">Email  </div><div><input type="text" size="30" maxlength="255" name="email" id="email"/></div></div><div id="error_email" class="accountManagerErrorMsg"></div>
                            <div><div style="">Password  </div><div><input type="password" size="30" maxlength="50" name="password" id="password"/></div></div><div id="error_password" class="accountManagerErrorMsg"></div>
                            <div><div style="">Confirm Password  </div><div><input type="password" size="30" maxlength="50" name="confirmPassword" id="confirmPassword"/></div></div><div id="error_confirmPassword" class="accountManagerErrorMsg"></div>
                            <div><div style="padding-top: 5px;" ><img src="../../../images/save.png" style="cursor: pointer" id="save"/><img src="../../../images/cancel.png" id="cancel" style="cursor: pointer"/></div></div>
                        </div>
                        <div style="width: 400px;float: left">
                            <div><div>User Name  </div><div><input type="text" size="30" maxlength="50" name="username" id="username"/></div></div><div id="error_username" class="accountManagerErrorMsg"></div>
                            <div><div>User Type  </div><div>
                                    <select id="userType">
                                        <option value="0">---Select account type---</option>
                                        <option value="1">Single account</option>
                                        <option value="2">Multiple accounts</option>

                                    </select>

                                </div></div>
                            <div id="error_userType" class="accountManagerErrorMsg"></div>
                            <div id="single_user_account" style="display: none">
                                <div>Please select user account </div>
                                <div>
                                    <input type="text" id="userAccount" onkeyup="autocompleteUser(this.value); return false;"/><input type="button" value="Add User" id="addNewUser"/>
                                </div>
                                <div id="userList" style="padding: 10px 0 0 5px">

                                </div>
                                <div id="error_user_accounts" class="accountManagerErrorMsg"></div>
                            </div>
                        </div>
                        <div id="error_msg" style="display: none;clear: both;color: red;padding: 10px">
                           
                        </div>
                    </div>
                </div>
                
                <div id="edit_form" style="clear: both">
                    
                </div>
                
                <div id="manager_list" style="clear: both" >
                    <table id="manager_list_table" >
                        <tr>
                            <th style="width: 200px">Name</th>
                            <th style="width: 300px">Email</th>
                            <th style="width: 200px">Account Type</th>
                            
                            <th style="width: 80px;text-align: center">Status</th>
                            <th style="width: 50px;text-align: center">Edit</th>
                            <th style="width: 50px;text-align: center">delete</th>
                        </tr>
                        <?php 
                            $userController = new User();
                            $managetList = $userController->GetAllManagers();

                            
                            if(isset($managetList)){
                            foreach($managetList as $val){
                            
                            
                        ?>
                        <tr id="tr_<?php echo $val->{'id'}; ?>">
                            <td><?php echo $val->{'username'}; ?></td>
                            <td><?php echo $val->{'email'}; ?></td>
                            <td><?php if($val->{'user_type'}==1){
                                echo 'Single account'; }elseif($val->{'user_type'}==2){ echo 'Multiple accounts'; }?></td>
                            
                            
                           
                            <td style="width: 80px;text-align: center"><?php if($val->{'status'}=='0'){ echo "Inactive";}else{ echo "Active"; } ?></td>
                            <td style="width: 50px;text-align: center"><img src="../../../images/file.png" style="cursor: pointer" onclick="editManager(<?php echo $val->{'id'}; ?>)"/></td>
                            <td style="width: 50px;text-align: center"><img src="../../../images/delete_icon.png" style="cursor: pointer" onclick="deleteManager(<?php echo $val->{'id'}; ?>)"/></td>
                        </tr>
                        <?php } } ?>
                    </table>
                </div>
            </div>
            </div>
            <br class="clear"/> <br class="clear"/> 
            <div id="footerInner" >
                <div class="lineBottomInner"></div>
                <div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
            </div>
    </body>
    
    <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="../../../js/jquery-ui-1.7.2.custom.min.js"></script>
    <script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>
    <script type="text/javascript" src="../../../js/module/admin_module.js"></script>

    <script type="text/javascript" src="../../../js/autoComplete/jquery.min.js"></script>
    <script type="text/javascript" src="../../../js/autoComplete/jquery-ui.min.js"></script>
    <link media="all" type="text/css" href="../../../js/autoComplete/jquery-ui.css" rel="stylesheet"/>
</html>
<script type="text/javascript">
        var packageArr = <?php echo $str; ?>;
        
        $(document).ready(function() {
            
        });
    </script>

<div id="dialog-confirm" title="Delete confirmation?" style="display: none">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span> Are you sure, you want to delete this item?</p>
</div>
<div id="dialog-message" title="Information" style="display: none">
	<p>You can add only one user account, if you want to add multiple user accounts change user type to 'Multiple Accounts'.</p>
</div>
<div id="dialog-confirm-change-user-type" title="Confirmation?" style="display: none">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span> If you change user type, all user account's will removed from this manager account you need to assign them again ?</p>
</div>
<div id="dialog-changePassword" title="Change Password" style="display: none">
    <div><div style="width: 120px;float: left">New password</div><div style="width: 100px;float: left"><input type="password" id="newPswword" size="15"/></div></div>
    <div><div style="width: 120px;float: left">Confirm password</div><div style="width: 100px;float: left"><input type="password" id="confirmPswword" size="15"/></div></div>
    <div id="password_error_list" class="accountManagerErrorMsg" style="clear: both;width: 250px;font-size: "></div>
</div>
