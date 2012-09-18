<?php
include("../../controller/admin_session_controller.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<table width="200" border="1">
  <tr>
    <td>Menu <?php echo $_SESSION['user_id'];?></td>
  </tr>
  <tr>
    <td><a href="package/package.php" target="_blank">Package</a></td>
  </tr>
  <tr>
    <td><a href="users/user.php" target="_blank">User</a></td>
  </tr>
	<tr>
    <td><a href="box/box.php" target="_blank">Box Users</a></td>
  </tr>
  <tr>
    <td><a href="module/module.php" target="_blank">Modules</a></td>
  </tr>
   <tr>
    <td><a href="../../controller/admin_logout_controller.php">Log out</a></td>
  </tr>
</table>
</body>
</html>
