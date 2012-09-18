<?php
$menu_item="photos";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>phizuu - Links</title>
<style type="text/css">
    .rowBox {
        min-height: 10px;
        float: left;
        width: 100%;
        overflow: hidden;
    }
</style>

<link rel="stylesheet" type="text/css" href="../../../css/styles.css"/>

</head>
<body>
<div id="mainWideDiv">
  <div id="middleDiv">
      <?php include("../../../view/user/common/header.php");?>
      <?php include("../../../view/user/common/navigator.php");?>
      
      <div class="rowBox"></div>
      <div class="rowBox">
          <div id="lightBlueHeader2">
              <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
              <div class="tahoma_14_white" id="lightBlueHeaderMiddle2">Import Data from FaceBook</div>
              <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
          </div>
      </div>
      <?php if($loggedIn) { ?>
      <div class="rowBox tahoma_12_blue" style="padding: 5px">
          Import from your FaceBook Profile (<?php echo $me['name'] ?>). If you need <a href="<?php echo $logoutUrl ?>">click here to logout.</a>
      </div>
      <?php } else { ?>

      <div class="rowBox tahoma_12_blue" style="padding: 5px">
          Please login to Import data from Facebook: <a href="<?php echo $loginUrl; ?>">Login</a>
      </div>
      <?php } ?>

      <div class="rowBox tahoma_12_blue" style="padding: 5px" id="progressDiv">
          <?php if($loggedIn) { ?>
          <img src="../../../images/share_on_fb_btn.png" style="cursor:pointer" onclick="javascript:createAlbum();"/>
          <?php } ?>
      </div>
      <div onclick="javascript: FB.Connect.showPermissionDialog('user_photos');">Test</div>
  </div>
</div>
<div id="footerMain">
    <div id="footer2" class="tahoma_11_blue">&copy; 2012 phizuu. All Rights Reserved.</div>
</div>
<div id="fb-root"></div>

<script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../../js/ui/ui.core.js"></script>

</body>

</html>
