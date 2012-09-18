<?php
foreach ($buyLinks as $buyLink) {
?>
<div id="id_buy_link<?php echo $buyLink['id']; ?>" style="width: 778px; float: left; height: 37px;">
    <div style="width: 200px;" class="tahoma_12_blue buyLinkRow edit_buy" id="title_<?php echo $buyLink['id']; ?>_buylink"><?php echo $buyLink['title']; ?></div>
    <div style="width: 486px;" class="tahoma_12_blue buyLinkRow edit_buy" id="link_<?php echo $buyLink['id']; ?>_buylink"><?php echo $buyLink['link']; ?></div>
    <div style="width: 27px;" class="tahoma_12_blue buyLinkRow">
        <img alt="Delete" title="Delete" src='../../../images/cross.png' style='cursor: pointer' onclick="deleteBuyLink('<?php echo $buyLink['id']; ?>')"/>
    </div>
</div>
<?php
}
?>
