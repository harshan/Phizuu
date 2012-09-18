<?php
$duration = ceil($bmusic->duration);
$seconds = $duration%60;
$minutes = ($duration - $seconds)/60;
?>
<li id="id_<?php echo $bmusic->id;?>">
    <div id="textBar"  class="tahoma_12_blue">
        <div class="move" style="cursor: move"><img src="../../../images/move.png"/></div>
        <div class="tahoma_12_blue title_note" style="line-height: 14px" id="title_<?php echo $bmusic->id;?>"><?php echo $bmusic->title;?></div>
        <div class="tahoma_12_blue duration2" id="duration_<?php echo $bmusic->id;?>"><?php echo $minutes."m ". $seconds . 's'?></div>
        <div class="tahoma_12_blue note_area note2new" style="line-height: 14px" id="note_<?php echo $bmusic->id;?>"><?php echo $bmusic->note;?></div>

        <div class="tahoma_12_blue" id="iconBox">
            <div class="tooltip" id="edit_<?php echo $bmusic->id;?>" style="cursor: pointer;float: left;height: 28px;width: 24px;padding-top: 18px;padding-left: 15px;"><a onclick="showEdit(<?php echo $bmusic->id;?>)"  ><img src="../../../images/file.png" border="0" /></a></div>
            <div class="tooltip" id="delete_<?php echo $bmusic->id;?>" style="cursor: pointer;float: left;height: 28px;width: 24px;padding-top: 18px;padding-left: 15px;"><a href="#" onclick="return deleteTrack(<?php echo $bmusic->id;?>);"><img src="../../../images/cross.png" border="0" /></a></div>
        </div>
        <div id="div_tooltip_common_<?php echo $bmusic->id;?>" class="div_tooltip_common">Edit</div>
    </div>
</li>
