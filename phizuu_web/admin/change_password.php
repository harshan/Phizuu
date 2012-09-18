<?php
include ("common/common.php");
//Collect data from session and form
$username=$_SESSION['username'];
$password=get_param("password");
$repassword=get_param("repassword");
$btSubmit=get_param("Submit");

switch ($btSubmit)
{
	case "update":	
	if ($repassword==$password && $password!=""){
		
		$password = md5($password);
		$sql="update ".$tblAdmin." set".
		" password = ".tosql($password,"Text").
		" where username=".tosql($username,"Text");
		mysql_query($sql) or die(mysql_error());	
		
		$form_msg="new password updated";
	
	}else{	
		$form_msg="new password , re-enter password does not match";
	}
	break;
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
<?php
include ("header.php");
?>
<table width="800" height="500" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <!--DWLayoutTable-->
  <tr>
    <td width="972" height="415" align="center" valign="top"><table align="center" width="290" border="0">
      <form id="form" name="form" method="post" action="<?=$_POST['PHP_SELF']?>">
        <tr>
          <td height="22" colspan="2" class="SubHead">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" colspan="2" class="heading">Change Password</td>
        </tr>
        <tr>
          <td height="15" colspan="2" class="SubHead"><span class="DotLine">-----------------------------------------------------------------------</span></td>
        </tr>
        <tr>
          <td height="20" colspan="2" class="NormalRedSmall"><?=$form_msg?></td>
          </tr>
        <tr>
          <td width="155" class="SubHead">Enter New Password </td>
          <td width="125" align="left" class="style3"><input name="password" type="password" class="NormalTextBox" id="password" value="<?=$password?>" /></td>
        </tr>
        <tr>
          <td class="SubHead">Re-Enter New Password </td>
          <td align="left" class="style3"><input name="repassword" type="password" class="NormalTextBox" id="repassword" value="<?=$repassword?>" /></td>
        </tr>
        <tr>
          <td height="27" class="Link2"><!--DWLayoutEmptyCell-->&nbsp;</td>
          <td height="27" align="left" class="Link2"><input name="Submit" type="submit" class="StandardButton" id="Submit" value="update" title="enter new password and click on this button to update"  /></td>
          </tr>
      </form>
    </table>
    </td>
  </tr>
</table>
<?php
include ("footer.php");
?>
</body>
</html>