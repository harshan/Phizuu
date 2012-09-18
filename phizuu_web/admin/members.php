<?php
include ("common/common.php");

$autoid = $_REQUEST["autoid"];
$Submit = $_REQUEST["Submit"];

$page_name="members.php"; 
$limit = 25;

$start = $_REQUEST["start"];
if($start =="") {
	$start = 0;
}

$eu = ($start - 0); 

$this1 = $eu + $limit; 
$back = $eu - $limit; 
$next = $eu + $limit; 


$ipage = get_param("ipage");
if($ipage =="") {
	$ipage = 0;
}

$eui = ($ipage - 0); 
$ithis1 = $eui + $limit; 
$iback = $eui - $limit; 
$inext = $eui + $limit; 

switch($Submit){
	case"delete":
		$sql= "DELETE FROM ".$tblMembers." WHERE autoid=".tosql($autoid,"Number");
		mysql_query($sql) or die(mysql_error());
		
		header("location:members.php");
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
<script language="javascript" type="text/javascript">
function Delete(autoid)
{
	var userreq=confirm('Are you sure you want to delete ?');
	if (userreq==true)
	{
		window.location='members.php?Submit=delete&autoid='+autoid;
	}
}
</script>
</head>

<body >
<?php
include ("header.php");
?>
<table align="center" width="1000" border="0">
  <form id="form" name="form" method="post" action="<?php echo $_POST['PHP_SELF']?>">
    <tr>
      <td width="280" height="22" class="SubHead">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" class="heading">Payment Details</td>
    </tr>
    <tr>
      <td height="15" class="SubHead"><span class="DotLine">--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</span></td>
    </tr>
  </form>
</table>
<table width="1000" border="0" align="center">
  <!--DWLayoutTable-->
  <tr bgcolor="#dbd9d9">
    <td width="71" valign="middle" class="NormalBold"><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td width="202" height="29" valign="middle" class="Normal"><strong>&nbsp;&nbsp;&nbsp;Name</strong></td>
    <td width="234" align="left" valign="middle" class="Normal"><strong>&nbsp;&nbsp;&nbsp;Email</strong></td>
    <td width="139" align="center" valign="middle" class="Normal"><strong>Country</strong></td>
    <td width="197" valign="middle">&nbsp;</td>
  </tr>
  <?php
	
	$sql = "select * from ".$tblMembers." where status='true' order by autoid desc limit $eu, $limit";
	$query2 = "select fname from ".$tblMembers." where status='true'";
	$rs  = mysql_query($sql) or die (mysql_error());
	$i=$start+1;
	while( $row = mysql_fetch_array($rs)) { 
		$autoid = $row["autoid"];
		$fname = $row["fname"];
		$lname = $row["lname"];
		$gender = $row["gender"];
		$email = $row["email"];
		$country = $row["country"];
			
	?>
  <tr bgcolor="#ececec" onMouseOver="this.bgColor='#dbd9d9'" onMouseOut="this.bgColor='#ececec'" onClick="">
    <td valign="middle" class="Normal" style="cursor:pointer" align="center"><?  	
			echo("[".$i."]");
			?></td>
    <td height="21" valign="middle" class="Normal">&nbsp;&nbsp;&nbsp;<?php echo $fname." ".$lname ?></td>
    <td align="left" valign="middle" class="Normal">&nbsp;&nbsp;&nbsp;<?php echo $email ?></td>
    <td align="center" valign="middle" class="Normal"><?php echo $country ?></td>
    <td align="center" valign="middle" class="Normal">&nbsp;<a href="#" class="SubHead" onClick="javascript:Delete('<?php echo $autoid?>')">Delete</a></td>
  </tr>
  <?php
	 $i++;
	  }
	  ?>
</table>
<table width="225" border="0" align="center" cellpadding="0" cellspacing="0" class="SubSubHead">
  <tr>
    <td align="right" valign="middle">&nbsp;</td>
    <td align="center" valign="middle" class="Normal" style="white-space:nowrap;">&nbsp;</td>
    <td valign="middle">&nbsp;</td>
  </tr>
  <tr>
    <td width="49" align="right" valign="middle"><?php						 
						$result2=mysql_query($query2);
						$nume=mysql_num_rows($result2);	
			
							$i=0;
							$l=0;                   				
                       		if($nume > $limit ){
							for($i=0;$i < $nume;$i=$i+$limit){						   
								$l=$l+1;
							}	
							
							if($start == 0 || $start=="")
							  {
								$k = "1";
							  }
							  else
							  {
								$k = 1+($start/$limit);
							  }	
							  
							if($back >=0)
								print "<a class='links' href='$page_name?start=$back'>&lsaquo;&lsaquo; Prev</a>"; 
							else
								print '<span class="Normal">&lsaquo;&lsaquo; Prev</span>'; 
                        ?></td>
    <td align="center" valign="middle" class="Normal" style="white-space:nowrap;">Page <span class="NormalRedSmall">
      <?php echo $k?>
      </span> of
      <?php echo $l?></td>
    <td width="55" valign="middle"><?php
							  if($this1 < $nume)
								print "<a class='links' href='$page_name?start=$next'>Next &rsaquo;&rsaquo;</a>";
							  else
								print '<span class="Normal">Next &rsaquo;&rsaquo;</span>';							
						  }
						  ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle">&nbsp;</td>
    <td align="center" valign="middle" class="Normal" style="white-space:nowrap;">&nbsp;</td>
    <td valign="middle">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<?php
include ("footer.php");
?>
</body>
</html>