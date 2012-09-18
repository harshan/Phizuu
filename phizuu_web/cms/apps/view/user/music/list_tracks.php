<ul >


<?php
if (count($tracks)==0) {
?>
    <li>You dont have any <b>public</b> tracks uploaded to SoundCloud or all tracks are added to the Bank List! <a href="#" onclick="javascript: return soundcloudListTracks('Refreshing Track List..')">Refresh</a> the list after adding tracks.</li>
<?php
}

foreach ($tracks as $track) {

$duration = ceil($track['duration']/1000);
$seconds = $duration%60;
$minutes = ($duration - $seconds)/60;
?>
    
    <li id="id_<?php echo $track['id'];?>"  style="cursor: default">

        <div id="textBar" class="tahoma_12_blue" style="width: 457px; background-image: url(<?php echo $track['waveform-url']; ?>); background-color: #d3d3d3">
            <div class="tahoma_12_blue title_note" style="line-height: 14px"><?php echo $track['title'] ?></div>
            <div class="tahoma_12_blue duration2" style="width: 54px;"><?php echo $minutes."m ". $seconds . 's'?></div>
            <div class="tahoma_12_blue note_area note2new" style="width:174px;line-height: 14px"><?php echo $track['description'];?></div>

            <div class="tahoma_12_blue" id="iconBox" style="margin-top:12px; width: 60px" >
                <div id="icon" style="cursor: pointer"><img onclick="soundcloudAddTrack(<?php echo $track['id'];?>, this)" width="22" height="17" src="../../../images/photoAddOver2.png" border="0" /></div>
            </div>
        </div>
    </li>
<?php
}
?>
</ul>
