<?php
@session_start();
include("../../../config/config.php");
include("../../../database/Dao.php");

$sql = "SELECT * FROM image WHERE user_id = {$_SESSION['user_id']}";
$dao = new Dao();
$res = $dao->query($sql);

$arr = $dao->getArray($res);

if (count($arr)==0) {
        echo '<a href="../pictures/photos.php" class="tahoma_12_ash">Please click here to enter images to the bank in images module</a>';
        exit;
}

if(isset($_GET['callbackId'])){
    $callbackId = $_GET['callbackId'];
} else {
    $callbackId = '';
}
?>

<div id="musicEditFileUploadR">
    <div id="musicEditFileUploadR">
        
        <?php
 
            foreach($arr as $image) {

                $title=$image['name'];
                $thumb=$image['thumb_uri'];
                $image=$image['uri'];

                ?>



        <div onclick="selectImage('<?php echo $image;?>', '<?php echo $thumb;?>','<?php echo $callbackId;?>')" style="padding: 2px; margin: 1px; border: 2px #043F53 solid; width: 77px; height: 77px; float: left">
            <img border="0" alt="<?php echo $title; ?>" src="<?php echo $thumb; ?>"  width="75" height="75"/>
        </div>
        <?php
            }

        ?>
    </div><!--for count5-->

</div>
