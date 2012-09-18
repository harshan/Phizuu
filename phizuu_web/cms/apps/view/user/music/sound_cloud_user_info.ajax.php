<div style="float: left; width: 100%">
    <div style="padding: 4px; float: left; width: 100%; font-size: 14px;">
        You are logged in as <a target="_blank" href="<?php echo $soundCloudInfo['permalink-url']; ?>"><?php echo $soundCloudInfo['full-name']==''?$soundCloudInfo['permalink']:$soundCloudInfo['full-name'] ?></a>,
        Click <a target="_blank" href="#" onclick="return scLogout();">here</a> to logout.
    </div>
    <div style="padding: 4px; width: 100%; padding-top: 10px; float: left; cursor: pointer; overflow: hidden" id="trackListDiv">
        <img src="../../../images/soundcloud_tracks.png" onclick="soundcloudListTracks('Listing Tracks...');"/>
    </div>
</div>