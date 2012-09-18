<?php
$uniqText = time();
foreach ($discoArr as $disco) {
   $row = "<li id='id_{$disco['id']}'>
              <div class='dragHandle'></div>
              <div class='rowDivsDiscography tahoma_12_blue' style='width: 125px'>
                <div id='title_{$disco['id']}' class='edit_text'>{$disco['title']}</div>
              </div>
              <div class='rowDivsDiscography tahoma_12_blue' style='width: 233px'>
                <div id='info_{$disco['id']}' class='edit_text'>{$disco['info']}</div>
              </div>
              <div class='rowDivsDiscography tahoma_12_blue' style='width: 267px;height:108px;padding-top:2px' >
                <div id='details_{$disco['id']}' class='edit'>".str_replace("\n", "<br/>", $disco['details'])."</div>
              </div>
              <div class='rowDivsDiscography tahoma_12_blue description_{$disco['id']}' style='width: 60px'>
                <a href='#' onclick='javascript: return showBuyURLEdit({$disco['id']});'>Show/Edit</a>
              </div>
             <div class='rowDivsDiscography tahoma_12_blue' style='width: 100px'>
                <img onclick='javascript: showImageEdit({$disco['id']},\"{$disco['image_uri']}\");' style='cursor: pointer' title='Click Here to Change/View the Image' src='{$disco['thumb_uri']}?prevent_cache=$uniqText' width=100 height=100 />
              </div>
             <div class='ttip' style='float:left'>
             <div class='rowDivsDiscography action tahoma_12_blue' style='width: 48px' id='delete_".$disco['id']."'>
                <img  src='../../../images/cross.png' style='cursor: pointer' onclick=\"deleteItem('{$disco['id']}')\"/>
             </div>
             </div>
             <div id='div_tooltip_common_".$disco['id']."' class='div_tooltip_common'>Delete</div>
          </li>";
   if (!isset ($dontEcho)) {
      echo $row;
   }
}
?>
