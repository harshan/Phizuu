<?php
if(isset($_REQUEST['id']) && ($_REQUEST['id'] == 'ajx_response')){
include("../../../config/config.php");
include('../../../config/database.php');
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
include('../../../controller/settings_controller.php');
include('../../../model/settings_model.php');
include('../../../config/error_config.php');

$id=$_REQUEST['type'];

user_content($id);
}

//user_content
function user_content($id)
{
?> 
<table align="left" cellpadding="0" cellspacing="0"><tr><td>
        <?php list_user($id);?>
		<?php add_user($id);?>
</td></tr></table>		
         <?php }?>
		
        <?php
		//list_users
function list_user($id){

if($id== 'y_user'){
$lid=$_ENV['setting_youtube'];
}
else if($id== 'r_user'){
$lid=$_ENV['setting_rssfeed'];
}
else if($id== 'f_user'){
$lid=$_ENV['setting_flickr'];
}
else if($id== 't_user'){
$lid=$_ENV['setting_twiter'];
}

$settings= new Settings();
$settings_list = $settings->listSettings($lid);
$count=1;
?>
<?php
 if(sizeof($settings_list) >0){
	  foreach($settings_list as $lst_settings){
    
		  ?>
          <div id="textSettings">
			<div class="tahoma_12_blue" id="titleSettings"><?php echo $lst_settings -> value;?></div>
			<div class="tahoma_14_white" id="removeSettings">
			<?php
			 	echo '<a href="#" onclick="showEdit(\''.addslashes('../../../controller/settings_add_iphone_controller').'\',\''.$lst_settings->id.'\',\''.$id.'\',\'1\',\'delete\')" ><img src="../../../images/remove.png" width="99" height="33" border="0" /></a>';
			 
			 ?></div>
             <div class="tahoma_12_blue" id="setDefault">
			<?php
			if($lst_settings -> preferred == '1'){
				echo '<img src="../../../images/default2.png" width="99" height="33" />';
				}else{
			echo '<a href="#" onclick="showEdit(\''.addslashes('../../../controller/settings_add_iphone_controller').'\',\''.$lst_settings->id.'\',\''.$id.'\',\'1\',\'edit\')" ><img src="../../../images/default.png" width="127" height="33" border="0"  /></a>';
			}?></div>
            
		</div>
        <?php
          $count++;
	  }
	  }
	
	 }//end function
?>
     
	 <?php
	 //add_user
	 
function add_user($id_name){?>
 <form id="form1" name="form1" method="post" action="" >
        <div id="textSettings2">
        <div class="tahoma_12_blue" id="settingsUserName"><?php if($id_name == 'r_user'){echo "Feed";}else{echo "Username";}?></div>
            <div class="tahoma_14_white" id="settingsUserField"><input type="text" name="<?php echo $id_name;?>" id="<?php echo $id_name;?>" />
            </div>
           <div class="tahoma_12_blue" id="settingsSave"><input type="image" src="../../../images/save2.png" name="button" id="button"width="69" height="33" onclick="showHint(<?php echo $id_name;?>.value,'<?php echo $id_name;?>')" /></div>
          </form>
     <?php }?>       