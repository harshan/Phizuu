<?php
if(isset($_REQUEST['Submit'])){

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<!--demo.basic.php-->
<form action="demo.basic.php" method="post" enctype="multipart/form-data">
  <label>
  <input type="file" name="userfile" id="userfile" />
  </label>
  <input type="submit" name="Submit" id="Submit" value="Submit" />
</form>
</body>
</html>
