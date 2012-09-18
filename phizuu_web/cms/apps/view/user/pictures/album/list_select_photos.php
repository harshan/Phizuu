<?php
if (count($pics)==0 && isset($forAlbum)) {
    echo "No pictures!";
}else {
    if(sizeof($pics) >0){
    foreach($pics as $pic){
?>
<div >
    <div class="albumSelectImageWrapper" onclick="javascript: selectPicture(this);">
        <img  src="<?php echo $pic->thumb_uri; ?>" width="75" height="75" />

        <span class="selURL" style="display: none"><?php echo $pic->uri;  ?></span>
        <span class="selThumbURL" style="display: none"><?php echo $pic->thumb_uri; ?></span>
    </div>
</div>
<?php

    }
}
}
?>