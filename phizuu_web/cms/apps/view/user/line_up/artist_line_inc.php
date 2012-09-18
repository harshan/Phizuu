<?php
$count = count($artistArray);
if ($count>0) {
    for ($i = 0; $i < $count; $i++) {
        $row = $artistArray[$i];
	if ($row['image_url']=='') {
	    $row['image_url'] = '../../../images/lineup_no_image.jpg';
	} else {
	    $row['image_url'] = $row['image_url'] . "?prevent_cache=".rand();
	}
?>
<li id="<?php echo "artist_id_".$row['id']; ?>" >
    <img class="artistImage" src="<?php echo $row['image_url']; ?>" />
    <div class="artistId" style="display: none"><?php echo $row['id']  ?></div>
    <div class="artistName"><?php echo $row['artist_name']  ?></div>
    <div class="artistOptions">
        <div class="tahoma_12_blue" id="iconBox">
            <div id="icon" style="cursor: pointer"><a onclick="return artistsController.showEditArtistDialog(<?php echo $row['id']; ?>);"  ><img src="../../../images/file.png" border="0" /></a></div>
            <div id="icon"><a href="#" onclick="return artistsController.deleteArtist(<?php echo $row['id']; ?>);"><img src="../../../images/cross.png" border="0" /></a></div>
        </div>
    </div>
</li>
<?php
    }
} else {
?>
<li style="text-align: center; background-color: #FFFFFF; margin-top: 15px">
Sorry, no artist found for the search!
</li>
<?php
}
?>