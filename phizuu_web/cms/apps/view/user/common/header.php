<?php  @session_start();
require_once "../../../config/app_key_values.php";

        if (!isset($imagePath)) {
            $imagePath = '../../../';
        }

        $makeChangesLiveRedirection = urlencode(curPageURL());


function curPageURL() {
    $requestURI = $_SERVER["REQUEST_URI"];
    
    
//    if($_SERVER["SERVER_NAME"]=='phizuu.com'){
//        $uri = str_replace('cms/apps/','',$_SERVER["REQUEST_URI"]);
//    }else{
//        $uri = str_replace('~phizuu/phizuu_web/cms/apps/','',$_SERVER["REQUEST_URI"]);
//        }
    if($_SERVER["SERVER_NAME"]==app_key_values::$LIVE_SERVER_DOMAIN){
        $uri = str_replace(app_key_values::$LIVE_SERVER_URL.'cms/apps/','',$_SERVER["REQUEST_URI"]);
    }elseif($_SERVER["SERVER_NAME"]==app_key_values::$TEST_SERVER_DOMAIN){
        $uri = str_replace(app_key_values::$TEST_SERVER_URL.'cms/apps/','',$_SERVER["REQUEST_URI"]);
    }else{
        $uri = str_replace(app_key_values::$LOCALHOST_SERVER_URL.'cms/apps/','',$_SERVER["REQUEST_URI"]);}


    return $uri;
}

?>
  	<div id="header">
	  <div id="logoContainer"><img src="<?php echo $imagePath; ?>images/logoInner.png" width="350" height="35" /></div>
          <?php if(isset($_SESSION['manager_id'])){ 
              if($_SESSION['user_type']==1){?>
          <div class="tahoma_12_white2" id="loginBox">Welcome <span style="font-weight:bold; "><?php if(!empty($_SESSION['manager_name'])){echo $_SESSION['manager_name'];}else{echo "Visitor";}?></span> you have login to <span style="font-weight:bold; "><?php echo $_SESSION['user_name']; ?></span> account | <?php if(!empty($_SESSION['manager_name'])){echo '<a href="../../../controller/logout_controller.php" class="tahoma_12_white2" onclick="return publishChangesOnLogout(this);">Logout</a>';}else{echo '<a href="../usr_login.php" class="tahoma_12_white2">Login</a>';}?><br />
              
 <?php if (isset($_SESSION['modules'])) {   ?>          
              <strong><a href="../../../controller/push_notifications_controller.php?action=make_changes_live&redirect=<?php echo $makeChangesLiveRedirection ?>"> <div  style="padding-top: 10px;"><img src="../../../images/makeChanges.png" style="text-decoration: none;border: 0px"/></div></a></strong>
 <?php } else { ?>
             <strong><a href="../../../controller/modules/app_wizard/AppWizardController.php?action=check_module_filled&module=<?php echo $menu_item; ?>">Application Wizard</a></strong>

 <?php }?>
          </div>
          <?php }else{ ?>
               <div class="tahoma_12_white2" id="loginBox">Welcome <span style="font-weight:bold; "><?php if(!empty($_SESSION['manager_name'])){echo $_SESSION['manager_name'];}else{echo "Visitor";}?></span> you have login to <span style="font-weight:bold; "><?php echo $_SESSION['user_name']; ?></span> account | <?php if(!empty($_SESSION['manager_name'])){echo '<a href="../../../controller/logout_controller.php" class="tahoma_12_white2" onclick="return publishChangesOnLogout(this);">Logout</a>';}else{echo '<a href="../usr_login.php" class="tahoma_12_white2">Login</a>';}?><br />
 <?php if (isset($_SESSION['modules'])) {   ?>
                   <div style="float: right">
                       <div style="float: left;padding-top: 15px;padding-right: 10px">Please click <strong style="text-decoration: underline"><a href="../../../controller/logout_controller.php?action=main">here</a></strong> to logout from <strong><?php echo $_SESSION['user_name'] ?></strong> account</div>
                       <strong><a href="../../../controller/push_notifications_controller.php?action=make_changes_live&redirect=<?php echo $makeChangesLiveRedirection ?>"> <div  style="padding-top: 10px;float: left"><img src="../../../images/makeChanges.png" style="text-decoration: none;border: 0px"/></div></a></strong>
                   </div>
                    <?php } else { ?>
             <strong><a href="../../../controller/modules/app_wizard/AppWizardController.php?action=check_module_filled&module=<?php echo $menu_item; ?>">Application Wizard</a></strong>

 <?php }?>
          </div>
          

          <?php } }else if(isset ($_REQUEST['action']) ){ ?>
              <div class="tahoma_12_white2" id="loginBox">Welcome <span style="font-weight:bold; "><?php if(!empty($_SESSION['user_name'])){echo $_SESSION['user_name'];}else{echo "Visitor";}?></span> | <?php if(!empty($_SESSION['user_name'])){echo '<a href="../../../controller/logout_controller.php" class="tahoma_12_white2" onclick="return publishChangesOnLogout(this);">Logout</a>';}else{echo '<a href="../login/?action=main_view" class="tahoma_12_white2">Login</a>';}?><br />
              </div>
          <?php }else{ ?>
              <div class="tahoma_12_white2" id="loginBox">Welcome <span style="font-weight:bold; "><?php if(!empty($_SESSION['user_name'])){echo $_SESSION['user_name'];}else{echo "Visitor";}?></span> | <?php if(!empty($_SESSION['user_name'])){echo '<a href="../../../controller/logout_controller.php" class="tahoma_12_white2" onclick="return publishChangesOnLogout(this);">Logout</a>';}else{echo '<a href="../usr_login.php" class="tahoma_12_white2">Login</a>';}?><br />
              
 <?php if (isset($_SESSION['modules'])) {   ?>          
              <strong><a href="../../../controller/push_notifications_controller.php?action=make_changes_live&redirect=<?php echo $makeChangesLiveRedirection ?>"> <div  style="padding-top: 10px;"><img src="../../../images/makeChanges.png" style="text-decoration: none;border: 0px"/></div></a></strong>
 <?php } else { ?>
             <strong><a href="../../../controller/modules/app_wizard/AppWizardController.php?action=check_module_filled&module=<?php echo $menu_item; ?>">Application Wizard</a></strong>

 <?php }?>
          </div>
          <?php } ?>
  	</div>
<script type="text/javascript">
    function publishChangesOnLogout(elem) {
        elem.innerHTML = 'Please wait, loging out..';

        var xmlhttp = null;
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.open("GET","../../../controller/push_notifications_controller.php?action=check_changes",false);
        xmlhttp.send();
        
        if (xmlhttp.responseText=='1') {
            var resp = confirm('You have made some changes in a previous session and have not been published yet.\n\nDo you want to logout without publishing changes?');

            elem.innerHTML = 'Logout';
            if (resp)
                return true;
            else
                return false;
        } else if (xmlhttp.responseText=='2') {
            var resp = confirm('You have made some changes and have not been published yet.\n\nDo you want to logout without publishing changes?');

            elem.innerHTML = 'Logout';
            if (resp)
                return true;
            else
                return false;
        } else {
            return true;
        }
    }
</script>