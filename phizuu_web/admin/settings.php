<?php
include ("common/common.php");

$email = trim($_REQUEST['email']);
$paypal_status = trim($_REQUEST['paypal_status']);
$api_uername = trim($_REQUEST['api_uername']);
$api_password = trim($_REQUEST['api_password']);
$api_signature = trim($_REQUEST['api_signature']);
$paypal_transaction = trim($_REQUEST['paypal_transaction']);

$Submit = $_REQUEST["Submit"];

switch($Submit)
{
	case 'Save Changes':		
		
		
		$sql="update ".$tblSettings." set".	
					" email = ".tosql($email,"Text").
					" ,paypal_status = ".tosql($paypal_status,"Text").
					" ,api_uername = ".tosql($api_uername,"Text").	
					" ,api_password = ".tosql($api_password,"Text").	
					" ,api_signature = ".tosql($api_signature,"Text").	
					" ,paypal_transaction = ".tosql($paypal_transaction,"Text").					
					" where autoid=1";
		mysql_query($sql) or die (mysql_error());
		
		do_paypal_settings();
		header( "Location:settings.php" );	
		
	break;
	default:
		$sql = "select * from ".$tblSettings." where autoid=1";
		$rs  = mysql_query($sql) or die (mysql_error());
		$row = mysql_fetch_assoc($rs);
		
		$email=$row['email'];	
		$paypal_status=$row['paypal_status'];	
		$api_uername=$row['api_uername'];	
		$api_password=$row['api_password'];	
		$api_signature=$row['api_signature'];	
		$paypal_transaction=$row['paypal_transaction'];	
	
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Administration</title>
<script language="javascript" type="text/javascript" src="js/dropdowntabs.js"></script>
<link href="css/style.css" rel="stylesheet" type="text/css">
<link href="css/body.css" rel="stylesheet" type="text/css">
<link href="css/ddcolortabs.css" rel="stylesheet" type="text/css">
</head>

<body >
<?php include ("header.php");?>
<table width="800" height="500" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <!--DWLayoutTable-->
  <tr>
    <td width="972" height="415" align="center" valign="top"><form name="form1" method="post" action="">
      <table width="363" border="0" class="Normal">
        <!--DWLayoutTable-->
        <tr>
          <td height="21" colspan="2" valign="top" class="heading"><!--DWLayoutEmptyCell-->&nbsp;</td>
        </tr>
        <tr>
          <td width="102" height="21" valign="top" class="Normal"><strong>Support Email</strong></td>
          <td width="251" valign="top"><input name="email" type="text" class="NormalTextBox" id="email" value="<?php echo $email?>" size="50"></td>
        </tr>
        <tr>
          <td height="21" colspan="2" valign="top" class="heading"><!--DWLayoutEmptyCell-->&nbsp;</td>
        </tr>
        <tr>
          <td height="21" colspan="2" valign="top" class="heading"><strong>PayPal Express Checkout</strong></td>
        </tr>
        <tr>
          <td colspan="2" valign="top" class="Normal"><strong>Enable PayPal Express Checkout</strong><br>
            Do you want to accept PayPal Express Checkout payments?<br>
            <?php
              if($paypal_status =="true")
			  {
				   $true ="CHECKED";
				   $false ="";
			  }else
			  {
				   $true ="";
				   $false ="CHECKED";
			  }
			  ?>
            <input type="radio" name="paypal_status" value="true" <?php echo $true?>>
            True<br>
            <input type="radio" name="paypal_status" value="false" <?php echo $false?>>
            False<br>
            <br>
            <strong>API Username </strong><br>
            The username to use for the PayPal API service<br>
            <input name="api_uername" type="text" class="NormalTextBox" value="<?php echo $api_uername?>" size="50">
            <br>
            <br>
            <b>API Password</b><br>
            The password to use for the PayPal API service<br>
            <input name="api_password" type="text" class="NormalTextBox" value="<?php echo $api_password?>" size="50">
            <br>
            <br>
            <b>API Signature</b><br>
            The signature to use for the PayPal API service<br>
            <input name="api_signature" type="text" class="NormalTextBox" id="api_signature" value="<?php echo $api_signature?>" size="50">
            <br>
            <br>
            <b>Transaction Server</b><br>
            Use the live or testing (sandbox) gateway server to process transactions?<br>
            <br>
            <?php
              if($paypal_transaction =="Live")
			  {
				   $Live ="CHECKED";
				   $Sandbox ="";
			  }else
			  {
				   $Live ="";
				   $Sandbox ="CHECKED";
			  }
			  ?>
            <input name="paypal_transaction" type="radio" value="Live" <?php echo $Live ?> />
            Live<br>
            <input type="radio" name="paypal_transaction" value="Sandbox" <?php echo $Sandbox ?> />
            Sandbox<br></td>
        </tr>
        <tr>
          <td colspan="2" valign="top" class="Normal"><!--DWLayoutEmptyCell-->&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" valign="top" class="Normal"><input name="Submit" type="submit" class="NormalTextBox" id="button" value="Save Changes"></td>
        </tr>
      </table>
    </form></td>
  </tr>
</table>
<?php include ("footer.php");?>
</body>
</html>
