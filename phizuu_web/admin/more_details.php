<?php
include ("common/common.php");

$id = $_REQUEST["id"];

$sql = "select * from ".$tbl_payment_information." where autoid=".tosql($id,"Number");
$rs  = mysql_query($sql) or die (mysql_error());
$row = mysql_fetch_assoc($rs);
$autoid = $row["autoid"];
$fname = $row["fname"];
$lname = $row["lname"];
$artist_name = $row["artist_name"];
$gender = $row["gender"];
$label_name = $row["label_name"];
$email = $row["email"];
$username = $row["username"];
$password = $row["password"];
$application_name = $row["application_name"];
$package = $row["package"];
$price = $row["price"];
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
<table align="center" width="552" border="0">
  <form id="form" name="form" method="post" action="<?=$_POST['PHP_SELF']?>">
    <tr>
      <td height="22" colspan="2" class="SubHead">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="2" class="heading">Payment Details</td>
    </tr>
    <tr>
      <td height="15" colspan="2" class="SubHead"><span class="DotLine">----------------------------------------------------------------------------------------------------------------------------------------</span></td>
    </tr>
    <tr>
      <td height="15" class="SubHead"><strong>Order Number</strong></td>
      <td class="SubHead"><?php echo getOrderNo($autoid) ?></td>
    </tr>
    <tr>
      <td height="15" class="SubHead"><strong>Name</strong></td>
      <td class="SubHead"><?php echo $fname." ".$lname ?></td>
    </tr>
    <tr>
      <td height="15" class="SubHead"><strong>Artist Name</strong></td>
      <td class="SubHead"><?php echo $artist_name ?></td>
    </tr>
    <tr>
      <td height="15" class="SubHead"><strong>Gender</strong></td>
      <td class="SubHead"><?php echo $gender ?></td>
    </tr>
    <tr>
      <td height="15" class="SubHead"><strong>Management or Label Name</strong></td>
      <td class="SubHead"><?php echo $label_name ?></td>
    </tr>
    <tr>
      <td height="15" class="SubHead"><strong>Email Address</strong></td>
      <td class="SubHead"><?php echo $email ?></td>
    </tr>
    <tr>
      <td height="15" class="SubHead"><strong>Usernam</strong></td>
      <td class="SubHead"><?php echo $username ?></td>
    </tr>
    <tr>
      <td height="15" class="SubHead"><strong>Password</strong></td>
      <td class="SubHead"><?php echo $password ?></td>
    </tr>
    <tr>
      <td height="15" class="SubHead"><strong>Package</strong></td>
      <td class="SubHead"><?php echo $package." ( $".$price." )" ?></td>
    </tr>
    <tr>
      <td width="221" height="15" class="SubHead"><strong>Application Name</strong></td>
      <td width="321" class="SubHead"><?php echo $application_name ?></td>
    </tr>
    <tr>
      <td height="15" colspan="2" class="SubHead">&nbsp;</td>
    </tr>
  </form>
</table>
<p>&nbsp;</p>
<?php
include ("footer.php");
?>
</body>
</html>