<?php
$module = 'user';
$str = "{";//"{'1':'Garage Band','2':'Idol','3':'Rock Star'}"
$first = true;
foreach ($packages as $package) {
    if($first) {
        $comma = '';
        $first = false;
    }else{
        $comma = ',';
    }
    $str .= "$comma'{$package['id']}':'{$package['name']}'";
}
$str .= "}";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>phizuu CMS - Admin</title>
<link href="../../../css/styles.css" rel="stylesheet" type="text/css" />

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
    width: 958px;
    float: left;
    padding-top: 10px;
    overflow: hidden;
}

.row_box_data {
    width: 958px;
    float: left;
    padding-top: 1px;
    overflow: hidden;
}

.searchbox input, .searchbox select{
    width: 64px;
    height: 15px;
    border: 1px solid gray;
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

.moduleFloating {
background-color:#043F53;
color:#FFFFFF;
display:none;
position:absolute;
width:830px;
padding: 5px;
height: 40px;
z-index: 10;
}
</style>

</head>
	

<body>
    <img id="pointerArrow" src="../../../images/admin_top_module.png" style="display:none; position: absolute; z-index: 5"/>
    <div class="moduleFloating" id="module_list"></div>
 <div id="header">
        <div id="headerContent">
            <?php include("../../../view/admin/common/header.php");?>
        </div>
        <div style="position: absolute; background-image: url(../../../images/menu_bg.png);height: 80px;width: 100%"></div>
    </div>
    <div id="mainWideDiv">
  <div id="middleDiv2">
        <?php // include("../../../view/admin/common/header.php");?>
	<?php include("../../../view/admin/common/navigator.php");?>
      
      <div class="row_box" style="padding-top: 20px">
          <div id="lightBlueHeader2">
              
              <div class="tahoma_14_white" id="lightBlueHeaderMiddle2" style="width: 940px">Manage Users</div>
            
          </div>
      </div>
      <div class="row_box searchbox" style="padding-top:0px">
          Username: <input id="search_username" class="sel_search"/>&nbsp;<b>AND</b>&nbsp;
          App ID: <input id="search_app_id" class="sel_search"/>&nbsp;<b>AND</b>&nbsp;
          User ID: <input id="search_user_id" class="sel_search"/>&nbsp;<b>AND</b>&nbsp;
          App. Name: <input id="search_app_name" class="sel_search"/>&nbsp;<b>AND</b>&nbsp;
          Email: <input id="search_email" class="sel_search"/>&nbsp;<b>AND</b>&nbsp;
          Status: <select id="search_status" class="sel_search">
              <option selected value="">--</option>
              <option value="3">Freezed</option>
              <option value="1">CMS</option>
              <option value="0">App Wizard</option>
              <option value="4">Built</option>
          </select>
          <img align="top" src="../../../images/search_icon.gif" title="Search" style="cursor: pointer" onclick="javascript:searchData()"/>
          <img align="top" src="../../../images/delete_conference_icon.png" title="Reset" style="cursor: pointer" onclick="javascript:resetSearch()"/>
      </div>

      <div class="row_box">
          <div class="title" style="width:41px">User ID</div>
          <div class="title" style="width:100px">Username</div>
          <div class="title" style="width:60px">App ID</div>
          <div class="title" style="width:140px">App Name</div>
          <div class="title" style="width:180px">Email</div>
          <div class="title" style="width:70px">Package</div>
          <div class="title" style="width:40px">Paid</div>
          <div class="title" style="width:67px">User Status</div>
          <div class="title" style="width:60px">Confirmed</div>
          <div class="title" style="width:110px">Actions</div>
      </div>
      <div class="row_box" id="viewRes" style="padding-top: 1px;">
      </div>
      <div class="row_box" style="text-align: right">
          <div id="totalDiv" style="float:left; font-weight: bold"></div>
          <img id="imgPrev" align="top" src="../../../images/btn_prev.png" title="Previous Page" style="cursor: pointer" onclick="javascript:goBack()"/>
          <img id="imgNext" align="top" src="../../../images/btn_next.png" title="Next Page" style="cursor: pointer" onclick="javascript:goNext()"/>
      </div>
	
  </div>	
</div>
        <br class="clear"/> <br class="clear"/> 
     <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>
</body>
    <script type="text/javascript">
         var packageArr = <?php echo $str; ?>;
    </script>
    <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="../../../js/jquery-ui-1.7.2.custom.min.js"></script>
    <script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>
    <script type="text/javascript" src="../../../js/module/admin_module.js"></script>
</html>
