<?php
if (count($countries)==0) {
?>
        <div id="analyticsTextBar">
            <div id="headerMiddleContentL" class="tahoma_12_blue" style="text-align: center">-- No data --</div>

    </div>

<?php
}

foreach($countries  as $country) {
?>
    <div id="analyticsTextBar">
            <div id="headerMiddleContentL" class="tahoma_12_blue"><?php echo $country[0]; ?></div>
            <div id="headerMiddleContentR" class="tahoma_12_blue"><?php echo $country[1]; ?></div>
    </div>
<?php
}

if (count($countries)>=10 && ($limit) ){
?>
<div id="headerMiddleContentR" class="tahoma_12_blue"><a href='javascript: showMoreCountryInfo();'>Show All..</a></div>
<?php
}
?>