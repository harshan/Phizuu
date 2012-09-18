<?php
session_start();
require_once "../../../../database/Dao.php";
require_once "../../../../config/config.php";

?>
<!DOCTYPE html>
<html>
    <head>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

 <style type="text/css">
    #resizable { width: 100px; height: 100px; background: silver; }
  </style>
  <script>
  $(document).ready(function() {
    $("#resizable").resizable();
  });
  </script>
  </head>
<body>  
<div>
    <div style="width: 450px;float: left">
        <div>
            <div>Do you want to select hot spot for selected image?</div>
            <div><input type="radio" name="hotSpot" value="male" /> Yes<br />
                <input type="radio" name="hotSpot" value="female" /> No</div>
        </div>
        <div>
            <div>Please select hot spot type</div>
            <div><input type="radio" name="type" value="male" /> Module<br />
                <input type="radio" name="type" value="female" /> Link</div>
        </div>
        <div>

            Link URL <span><input type="text" name="linkurl" maxlength="100"/></span>
        </div>
        <div>
            Module 
            <select name="module">
                <?php
                $dao = new Dao();
                $userId = $_SESSION['user_id'];
                $sql = "SELECT * FROM ab_modules WHERE user_id='$userId'";
                $res = $dao->query($sql);
                $abModules = $dao->getArray($res);

                foreach ($abModules as $value) {
                    ?>
                    <option value="<?php echo $value; ?>"><?php echo $value['module_name']; ?></option>
                <?php } ?>
            </select>
        </div>




    </div>
    <div style="float: right;width: 300px">
        <div style="margin-top: 0px;width: 260px;height: 500px;float: right;">
            
            <?php 
                $id = $_REQUEST['id'];
                $dao = new Dao();
                $appId = $_SESSION['app_id'];
                $sql = "SELECT * FROM home_image WHERE id='$id'";
                $res = $dao->query($sql);
                $image = $dao->getArray($res);
                
                
            ?>
            <img src="<?php echo $image[0]['image_url']; ?>" width="320" height="367"/>
            
        </div>
    </div>
</div>
    <div id="resizable"></div>
</body>