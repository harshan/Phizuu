
<div id="header">
    <div id="logoContainer"><img src="../../../images/logoInner.png"width="350" height="35" /></div>
    <div class="tahoma_12_white2" id="loginBox"><a href="../../../controller/admin_logout_controller.php"  class="tahoma_12_white2">Welcome <span style="font-weight:bold; "><?php if(!empty($_SESSION['manager_name'])){echo $_SESSION['manager_name'];}else{echo "Visitor";}?></span> | Logout</a><br />
    </div>
</div>