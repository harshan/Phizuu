<?php
include("../../config/config.php");
include("../../controller/session_controller.php");
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
    <td><a href="videos/list_videos.php" target="_blank">Videos</a></td>
  </tr>
  <tr>
    <td><a href="pictures/list_pics_tbl.php" target="_blank">Pictures</a></td>
  </tr>
  <tr>
    <td><a href="music/list_music.php" target="_blank">Music</a></td>
  </tr>
  <tr>
    <td><a href="news/news.php" target="_blank">News</a></td>
  </tr>
  <tr>
    <td><a href="tours/tours.php" target="_blank">Tours</a></td>
  </tr>
  <tr>
    <td><a href="settings/settings.php" target="_blank">Settings</a></td>
  </tr>
   <tr>
    <td><a href="../../controller/logout_controller.php">Log out</a></td>
  </tr>
</table>
</body>
</html>
