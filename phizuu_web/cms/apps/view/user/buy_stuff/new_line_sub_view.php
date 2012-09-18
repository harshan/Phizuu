<?php
foreach ($linksArr as $link) {
   $row = "<li id='id_{$link['id']}'>
              <div class='dragHandle'></div>
              <div class='rowDivs tahoma_12_blue' style='width: 300px'>
                <div id='1_{$link['id']}' class='edit'>{$link['title']}</div>
              </div>
              <div class='rowDivs tahoma_12_blue' style='width: 529px'>
                <div id='2_{$link['id']}' class='edit'>{$link['uri']}</div>
             </div>
                  <div class='ttip' style='float:left'>
             <div class='rowDivs action tahoma_12_blue' style='width: 28px' id='delete_".$link['id']."'>
                <img src='../../../images/cross.png' style='cursor: pointer' onclick=\"deleteItem('{$link['id']}')\"/>
             </div>
                </div>
                  <div id='div_tooltip_common_".$link['id']."' class='div_tooltip_common'>Delete</div>
          </li>";
   if (!isset ($dontEcho)) {
      echo $row;
   }
}
?>
