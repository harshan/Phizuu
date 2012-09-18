<?php
if(sizeof($albums) >0){
    foreach($albums as $album){
?>
<li id="aid_<?php echo $album['id']; ?>">
    <div class="albumWrapper">
        <div class="description dragHandlePhoto"><span class="titleSel"><?php echo $album['album_name'];  ?></span> (<span class="imageCountSel"><?php echo $album['image_count'];  ?></span>)</div>
        <div class="image dragHandlePhoto"><img class="imageSel"  src="<?php echo $album['thumb_uri']; ?>?no_chache=x" width="95" height="50" /></div>
        <div class="icons">
            <div class="iconLeft clickable" onclick="javascript: delete_confirm_album(<?php echo $album['id'];  ?>);" id="delete_<?php echo $album['id']; ?>"></div>
            <div class="iconMiddle clickable" onclick="javascript: openAlbum(<?php echo $album['id'];?>);" id="open_<?php echo $album['id']; ?>"></div>
            <div class="iconRight clickable" onclick="javascript: showEditAlbum(<?php echo $album['id'];?>);" id="edit_<?php echo $album['id']; ?>"></div>
        </div>
        <div id="div_tooltip_common_<?php echo $album['id']; ?>" class="div_tooltip_common">Edit</div>
    </div>
</li>
<?php
    }
}
?>

<script type="text/javascript">
      
            
    $(document).ready(function(){
                
        $(".icons div").mouseover(function(){
            var arr = $(this).attr('id').split("_");
            var id = arr[1];
                    
            if("delete"==arr[0]){
              
                    
                $("#div_tooltip_common_"+id).text("Delete");
                $("#div_tooltip_common_"+id).css({
                    "margin":"105px 0 0 0px",
                    "padding":"10px 0 0 0px"
                });
                $("#div_tooltip_common_"+id).show();
                $("#delete_"+id).mouseout(function(){
                    $("#div_tooltip_common_"+id).hide();
                });
                    
              
            }
            else if("open"==arr[0]){
               
                    
                $("#div_tooltip_common_"+id).text("Open");
                $("#div_tooltip_common_"+id).css({
                    "margin":"105px 0 0 30px",
                    "padding":"10px 0 0 0px"
                });
                $("#div_tooltip_common_"+id).show();
                $("#open_"+id).mouseout(function(){
                    $("#div_tooltip_common_"+id).hide();
                });
                    
              
            }
            else if("edit"==arr[0]){
              
                    
                $("#div_tooltip_common_"+id).text("Edit");
                $("#div_tooltip_common_"+id).css({
                    "margin":"105px 0 0 63px",
                    "padding":"10px 0 0 0px"
                });
                $("#div_tooltip_common_"+id).show();
                $("#edit_"+id).mouseout(function(){
                    $("#div_tooltip_common_"+id).hide();
                });
                    
            }
                            
        });
        
    });
    
    </script>