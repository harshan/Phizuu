<?php
$module = 'package';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>phizuu CMS - Admin</title>
<link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../../../css/flexgrid/flexigrid/flexigrid.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />




<style type="text/css">
body{
    font-family: Tahoma;
    font-size: 12px;
    color: #616262;
}

#row_box input{
    padding: 0;
}

#row_box select{
    width: 95%;
    font-size: 10px;
}

.flexigrid div.bDiv td div .edit_right input{
    text-align: right;
}

.row_box {
    width: 948px;
    float: left;
    padding-top: 10px;
    overflow: hidden;
}

.row_box_data {
    width: 948px;
    float: left;
    padding-top: 1px;
    overflow: hidden;
}

.title {
    height: 16px;
    padding: 4px;
    margin-right: 1px;
    color: #FFFFFF;
    font-size: 12px;
    background-color: #747c7e;
    float: left;
}

.data {
    height: 20px;
    padding: 4px;
    margin-right: 1px;
    font-size: 12px;
    background-color: #F3F3F3;
    float: left;
}

.button {
    cursor: pointer;
}

</style>

</head>


<body>
    <div id="header">
        <div id="headerContent">
            <?php include("../../../view/admin/common/header.php");?>
        </div>
        <div style="position: absolute; background-image: url(../../../images/menu_bg.png);height: 80px;width: 100%"></div>
    </div>
<div id="mainWideDiv">
  <div id="middleDiv2">
        
	<?php include("../../../view/admin/common/navigator.php");?>
      <div class="row_box" style="padding-top: 20px">
          <div id="lightBlueHeader2">
             
              <div class="tahoma_14_white" id="lightBlueHeaderMiddle2"  style="width: 930px">Manage Packages</div>
             
          </div>
      </div>
        <div class="row_box" style="text-align: right;padding-top:0px">
          <img id="imgPrev" align="top" src="../../../images/create_package.png" title="Create new package and append to the list" style="cursor: pointer" onclick="javascript:createPackage()"/>
      </div>
      <div class="row_box" >
          <div class="title" style="width:60px">Package ID</div>
          <div class="title" style="width:200px">Name</div>
          <div class="title" style="width:95px">Video Limit</div>
          <div class="title" style="width:95px">Music Limit</div>
          <div class="title" style="width:95px">Photo Limit</div>
          <div class="title" style="width:95px">Message Limit</div>
          <div class="title" style="width:95px">Home Scr. Img. Lmt.</div>
          <div class="title" style="width:95px">Album Limit</div>
          <div class="title" style="width:37px">Actions</div>
      </div>
      <div class="row_box" id="viewRes" style="padding-top: 1px;">
      </div>
  </div>
</div>
    <br class="clear"/> <br class="clear"/> 
     <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>
</body>
    <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="../../../js/jquery-ui-1.7.2.custom.min.js"></script>
    <script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>
    <script type="text/javascript" src="../../../js/module/admin_module_package.js"></script>
</html>
