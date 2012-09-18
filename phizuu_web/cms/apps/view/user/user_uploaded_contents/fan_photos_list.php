<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if(count($photos->images)==0) {
    echo "No fan uploaded photos are found for this event!";
    exit;
}


foreach($photos->images as $photo) {
?>
<div class="photo_container">
    <div class="image_container">
        <img src="<?php echo $photo->thumb_uri ?>"/>
    </div>
    <div>
        <a href="#" onclick="javascript: return deleteImage(<?php echo $tourId ?>, <?php echo $photo->id ?>, this);">Delete</a>
    </div>
</div>
<?php
}
?>