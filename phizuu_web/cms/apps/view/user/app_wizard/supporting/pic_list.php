<?php
if(sizeof($pics) >0){
    foreach($pics as $pic){
?>
<li id="id_<?php echo $pic->id; ?>" class="<?php echo $bankList?'addPhotoSelector':'' ?>">
    <div class="photoBox">
        <div class="photo dragHandlePhoto"><img  src="<?php echo $pic->thumb_uri; ?>" width="82" height="82" /></div>
        <div class="photoLower">
            <div class="photoName edit" id="name_id_<?php echo $pic->id;  ?>"><?php echo $pic->name;  ?></div>
            <div id="icon"><a href="../../../controller/pic_add_iphone_controller.php?id=<?php echo $pic->id;  ?>&status=<?php echo $bankList?'delete':'remove' ?>" onclick="javascript: return delete_confirm(<?php echo $pic->id;  ?>);" ></a></div>
        </div>
    </div>
</li>
<?php
    }
}
?>