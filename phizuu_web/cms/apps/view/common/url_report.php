<?php
if (count($urls)==0) {
?>
        <div id="analyticsTextBar">
            <div id="headerMiddleContentL" class="tahoma_12_blue" style="text-align: center">-- No data --</div>

    </div>

<?php
}

foreach($urls  as $url) {
?>
    <div id="analyticsTextBar">
            <div id="headerMiddleContentL" class="tahoma_12_blue"><?php echo $url[0]; ?></div>
            <div id="headerMiddleContentR" class="tahoma_12_blue"><?php echo $url[1]; ?></div>
    </div>
<?php
}
?>