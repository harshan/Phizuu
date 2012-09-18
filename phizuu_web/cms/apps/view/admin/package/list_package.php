<?php
function getUsersHTMLPackage($packages) {
    ob_start();
    foreach($packages as $package) {

       $id = $package['id']; 
        ?>
      <div class="row_box_data" id="parent_<?php echo $id?>">
          <div class="data" style="width:60px" id='id|<?php echo $id?>'><?php echo $package['id']; ?></div>
          <div class="data edit" style="width:200px" id='name|<?php echo $id?>'><?php echo $package['name']; ?></div>
          <div class="data edit" style="width:95px" id='video_limit|<?php echo $id?>'><?php echo $package['video_limit']; ?></div>
          <div class="data edit" style="width:95px" id='music_limit|<?php echo $id?>'><?php echo $package['music_limit']; ?></div>
          <div class="data edit" style="width:95px" id='photo_limit|<?php echo $id?>'><?php echo $package['photo_limit']; ?></div>
          <div class="data edit" style="width:95px" id='message_limit|<?php echo $id?>'><?php echo $package['message_limit']; ?></div>
          <div class="data edit" style="width:95px" id='home_screen_images|<?php echo $id?>'><?php echo $package['home_screen_images']; ?></div>
          <div class="data edit" style="width:95px" id='album_limit|<?php echo $id?>'><?php echo $package['album_limit']; ?></div>
          <div class="data" style="width:37px">
              <img class="button" src="../../../images/album_del_icon.png" title="Delete Package" onclick="javascript: deletePackage(<?php echo $package['id']; ?>)"/>&nbsp;&nbsp;
          </div>
          
      </div>
<?php
    }
    $rtn = ob_get_contents();
    ob_end_clean();
    return $rtn;
}
?>
