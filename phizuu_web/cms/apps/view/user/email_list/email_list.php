<?php 
require_once "../../../controller/email_list_controller.php";
session_start();
$menu_item="email_list";



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>Phizuu Application</title>
<link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../../../css/swf_up/default_new.css" rel="stylesheet" type="text/css" />

</head>
<?php 
$emailListController = new email_list_controller();
$mailList = $emailListController->GetAllEmailListByAppId($_SESSION['app_id']);


//print_r($mailList[0]);

if(isset($_POST['emailList'])){
    
    header("Content-type: text/csv");  
    header("Cache-Control: no-store, no-cache");  
    header('Content-Disposition: attachment; filename="filename.csv"');

    $emailAddressList = array();
    foreach ($mailList as $fields) {
        $email = array();
        array_push($email, $fields['email']);
        array_push($emailAddressList, $email);
    }
    print_r($emailAddressList);

    $fp = fopen("php://output",'w'); 
    foreach ($emailAddressList as $fields1) {
        fputcsv($fp, $fields1);
    }
    fclose($fp);
}
?>


<body onload="MM_preloadImages('../../../images/musicAc.png','../../../images/VideoAc.png','../../../images/photosAc.png','../../../images/flyersAc.png','../../../images/newsAc.png','../../../images/ToursAc.png','../../../images/linksAc.png','../../../images/settingsAc.png'); validateRSS();">
    <div id="header">
        <div id="headerContent">
            <?php include("../common/header.php");?>
        </div>
        <div style="position: absolute; background-image: url(../../../images/menu_bg.png);height: 80px;width: 100%"></div>
    </div>
    <div id="mainWideDiv">
  <div id="middleDiv2">
  	  
		<?php  include("../common/navigator.php");?>
      <div id="body">
          <div>
              <a href="email_address_list.php"><img src="../../../images/export_mail_btn.png" style="border: 0"/></a>
          </div>
          <div style="margin-top: 20px">
	  	
              <div class="tahoma_14_white" id="newsHeader">Email Address Lists Section</div>
	  	
              <?php if(isset($mailList)){ ?> 
              <table>
                  <tr >
                      <th style="background-color: #757f81;color:#ffffff;height: 25px;padding-left: 10px;width: 200px">Name</th>
                      <th style="background-color: #757f81;color:#ffffff;height: 25px;padding-left: 10px;width: 300px">E-mail</th>
                      <th style="background-color: #757f81;color:#ffffff;height: 25px;padding-left: 10px;width: 200px">Country</th>
                      <th style="background-color: #757f81;color:#ffffff;height: 25px;padding-left: 10px;width: 192px">Feedback</th>
                  </tr>
                  <?php foreach($mailList as $value){?>
                  <tr style="border: 1px solid red;height: 25px">
                      <td style="background-color: #d2d2d2;padding-left: 10px;"><?php echo $value['name']?></td>
                      <td style="background-color: #d2d2d2;padding-left: 10px;"><?php echo $value['email']?></td>
                      <td style="background-color: #d2d2d2;padding-left: 10px;"><?php echo $value['country']?></td>
                      <td style="background-color: #d2d2d2;padding-left: 10px;"><?php echo $value['feedback']?></td>
                  </tr>
                  <?php } ?>
              </table>
              <?php } ?>
          
             
	  </div>
          <br class="clear"/>
          
            

      </div> 
  
  </div>
   
</div>
   
    <div id="result" style="display: none"></div>
       <br class="clear"/>
     <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>

</body>
    
</html>

