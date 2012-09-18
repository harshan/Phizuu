<?php
if(sizeof($bank_video)>0){
    $count=0;
	  foreach($bank_video as $bVideo){

                             $duration = ceil($bVideo['duration']);
$seconds = $duration%60;
$minutes = ($duration - $seconds)/60;
              ?>

      <li id="id_<?php echo isset($iphone)?$bVideo['id']: $bVideo['vid'];?>"  style="cursor: pointer;">
      <table border="0" cellpadding="0" cellspacing="0">
      <tr>
      <td>
      <div id="textBarMusic">
          <div class="move"><img src="../../../images/move.png"/></div>
          <div class="tahoma_12_blue edit titleMusic" id="1_<?php echo isset($iphone)?$bVideo['id']: $bVideo['vid'];?>"><?php echo $bVideo['title'];?></div>
      <div class="tahoma_14_white" id="durationMusic"><span id="2_<?php echo isset($iphone)?$bVideo['id']: $bVideo['vid'];?>" class="tahoma_12_blue"><?php echo $minutes."m ". $seconds . 's'?></span></div>
			<div class="tahoma_12_blue" id="noteMusicThumb">
			  <div class="thmbImg"><img src="<?php echo $bVideo['thumb'];?>" width="50" height="44" /></div>
			</div>
            <div class="tahoma_12_blue" id="iconBoxMusic">
                <?php if(isset($iphone)) { ?>
			  <div id="icon"><a href="javascript: showEdit(<?php echo $bVideo['id'];?>)"><img src="../../../images/file.png" border="0" /></a></div>

                          <?php if(isset ($cms)) {?>
                          <div id="icon"><a onclick="javascript: return deleteVideo(<?php echo $bVideo['id'];?>);" href="#"><img src="../../../images/cross.png" border="0" /></a></div>
			
                          <?php } else {?>
                          <div id="icon"><a href="../../../controller/video_add_iphone_controller.php?id=<?php echo $bVideo['id'];?>&status=delete" onclick="return delete_confirm();"><img src="../../../images/cross.png" border="0" /></a></div>

                          <?php }?>
			  
                        <?php } else {?>
                        
			  <div id="icon" class="add_video"><a href="#" onclick="return addVideo(<?php echo $count?>,'<?php echo $bVideo['vid'];?>');"><img src="../../../images/photoAddOver2.png" border="0" /></a></div>
    
      
      <?php } ?>

            </div>
      </div>
    </td>
      </tr>
      </table>
      </li>

      <?php
      $count++;
	  }
	  } else {?>
      <div class="tahoma_12_blue">No public videos found or all the videos added!</div>
      <? } ?>