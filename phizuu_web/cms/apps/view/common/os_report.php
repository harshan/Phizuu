<?php
if (count($oses)==0) {
?>
        <div id="analyticsTextBar">
            <div id="headerMiddleContentL" class="tahoma_12_blue" style="text-align: center">-- No data --</div>

    </div>

<?php
}

foreach($oses as $os) {
?>
    <div id="analyticsTextBar">
            <div id="headerMiddleContentL" class="tahoma_12_blue"><?php echo $os[0]; ?></div>
            <div id="headerMiddleContentR" class="tahoma_12_blue"><?php echo $os[1]; ?></div>
    </div>
<?php
}
?>