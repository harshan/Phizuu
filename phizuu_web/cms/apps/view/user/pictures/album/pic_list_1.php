
<?php
//get image module item analytic data

$ch = curl_init(app_key_values::$API_URL . "analytic/" . $_SESSION['app_id'] . "/image/getall");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$allItems = curl_exec($ch);
$allItems = json_decode($allItems);
$dataList = array();
if (isset($allItems)) {
    foreach ($allItems->{"image"} as $value) {
        $dataList[$value->{"item_id"}] = array($value->{"like_count"}, $value->{"share_count"}, $value->{"comment_count"}, $value->{"view_count"});
    }
}
?>
<div id="comments_view_full" ></div>
<?php
if (count($pics) == 0 && isset($forAlbum)) {
    echo "<li style='font-family: Tahoma; font-size: 16px; color: #03455A'>No pictures!</li>";
} else {
    if (sizeof($pics) > 0) {
        foreach ($pics as $pic) {
            ?>
            <li id="id_<?php echo $pic->id; ?>">
                <div class="albumImageWrapper">
                    <div class="image dragHandlePhoto"><img  src="<?php echo $pic->thumb_uri; ?>" width="94" height="94" /></div>
                    <div class="description <?php echo isset($flickrList) ? '' : 'edit' ?>" id="1_<?php echo $pic->id; ?>"><?php
            if (isset($pic->name)) {
                echo $pic->name;
            }
            ?></div>

                    <div id="viewToolTip<?php echo $pic->id ?>" class="div_tooltip" style="width: 110px;height: 98px;background: url('../../../images/small_tooltip1.png') no-repeat  top left"></div>
                    <div id="tooltip_comment<?php echo $pic->id ?>" class="div_tooltip_comment" style="margin: 150px 0 0 10px; "></div>

                    <?php
                    if (array_key_exists($pic->id, $dataList)) {
                        $itemExist = TRUE;
                    }
                    ?>
                    <div style="float: left;padding-left: 5px;margin-top: 0px;height: 16px;width: 46px;vertical-align: bottom;font-family: arial;font-size: 12px;color: #777f81" id="div_source" >
                        <?php
                        if ((isset($pic->iphone_status)) && $pic->iphone_status == 1) {
                            ?>
                            <input type="hidden" value="
                            <?php
                            if (isset($itemExist) && $itemExist == TRUE):echo $dataList[$pic->id][3];
                            else: echo '0';
                            endif;
                            ?>" id="viewCount_<?php echo $pic->id ?>"/>
                            <span class="showViews" id="showViews_<?php echo $pic->id; ?>"><img src="../../../images/img_icon_share.png" /><span style="padding: 0px 0px 0px 0px"></span></span>
                            <input type="hidden" value="
                            <?php
                            if (isset($itemExist) && $itemExist == TRUE):echo $dataList[$pic->id][0];
                            else: echo '0';
                            endif;
                            ?>" id="viewLikes_<?php echo $pic->id ?>"/>
                <!--                        <span class="showViews" id="showLikes_<?php echo $pic->id; ?>"><img src="../../../images/icon_like.png"/><span style="padding: 0 2px 2px 0px"></span></span>-->
                            <input type="hidden" value="
                            <?php
                            if (isset($itemExist) && $itemExist == TRUE):echo $dataList[$pic->id][1];
                            else: echo '0';
                            endif;
                            ?>" id="viewShare_<?php echo $pic->id ?>"/>
                <!--                        <span class="showViews" id="showShare_<?php echo $pic->id; ?>"><img src="../../../images/icon_share.png"/><span style="padding: 0 2px 2px 0px"></span></span>-->
                            <input type="hidden" value="
                            <?php
                            if (isset($itemExist) && $itemExist == TRUE):echo $dataList[$pic->id][2];
                            else: echo '0';
                            endif;
                            ?>" id="viewComment_<?php echo $pic->id ?>"/>
                            <span class="showViews" id="showComments_<?php echo $pic->id; ?>" style="padding-left: 3px"><img src="../../../images/img_icon_comment.png"/><span style="padding: 0 0px 0px 0px"></span></span><input type="hidden" id="commentsCount_<?php echo $pic->id; ?>" value="<?php
                   if (isset($itemExist) && $itemExist == TRUE):echo $dataList[$pic->id][2];
                   else: echo '0';
                   endif;
                   $itemExist = FALSE;
                            ?>"/>
                                                                                                                                                                                                                                   <?php } ?>        
                    </div>
                    <div class="icons">
                        <?php if (!isset($flickrList)) { ?>
                            <div class="iconLeft clickable" onclick="javascript: delete_confirm(<?php echo $pic->id; ?>);" id="delete_<?php echo $pic->id; ?>"></div>
                        <?php } else { ?>
                            <div class="iconLeftNone clickable" onclick="javascript: delete_confirm(<?php echo $pic->id; ?>);"></div>
                        <?php } ?>

                        <div class="iconMiddle clickable" onclick="javascript: zoomImage('<?php echo $pic->uri; ?>');" id="zoom_<?php echo $pic->id; ?>"></div>
                        <!--            hidden fields-->
                        <div>

                            <input type="hidden" value="<?php echo $pic->uri; ?>" id="uri<?php echo $pic->id; ?>"/>
                            <input type="hidden" value="<?php echo $pic->thumb_uri; ?>" id="thumb_uri<?php echo $pic->id; ?>"/>
                        </div>
                        <?php if (!isset($flickrList)) { ?>
                            <div class="iconRight clickable" onclick="javascript: moveItem_1(this);" id="move_<?php echo $pic->id; ?>"></div>
                        <?php } else { ?>
                            <div class="<?php echo $pic->added ? 'addIconDis' : 'addIcon'; ?> clickable" onclick="javascript: addItemFlickr(this,<?php echo $pic->added ? 'true' : 'false'; ?>);"></div>
                        <?php } ?>

                    </div>
                    <div id="div_tooltip_common_<?php echo $pic->id; ?>" class="div_tooltip_common">Edit</div>
                </div>
                <?php if (isset($flickrList)) { ?>
                    <span class="selName" style="display: none"><?php echo $pic->name; ?></span>
                    <span class="selURL" style="display: none"><?php echo $pic->uri; ?></span>
                    <span class="selThumbURL" style="display: none"><?php echo $pic->thumb_uri; ?></span>
                    <span class="selPID" style="display: none"><?php echo $pic->id; ?></span>
                <?php } ?>
            </li>
            <?php
        }
    }
}
?>

<script type="text/javascript">
      
            
    $(document).ready(function(){
                
        $(".icons div").mouseover(function(){
            var arr = $(this).attr('id').split("_");
            var id = arr[1];
                    
            if("zoom"==arr[0]){
              
                    
                    $("#div_tooltip_common_"+id).text("Zoom");
                    $("#div_tooltip_common_"+id).css({
                        "margin":"165px 0 0 63px",
                        "padding":"10px 0 0 0px"
                    });
                    $("#div_tooltip_common_"+id).show();
                    $("#zoom_"+id).mouseout(function(){
                        $("#div_tooltip_common_"+id).hide();
                    });
                    
              
            }
            else if("delete"==arr[0]){
               
                    
                    $("#div_tooltip_common_"+id).text("Delete");
                    $("#div_tooltip_common_"+id).css({
                        "margin":"165px 0 0 47px",
                        "padding":"10px 0 0 0px"
                    });
                    $("#div_tooltip_common_"+id).show();
                    $("#delete_"+id).mouseout(function(){
                        $("#div_tooltip_common_"+id).hide();
                    });
                    
              
            }
            else if("move"==arr[0]){
              
                    
                    $("#div_tooltip_common_"+id).text("Move");
                    $("#div_tooltip_common_"+id).css({
                        "margin":"165px 0 0 83px",
                        "padding":"10px 0 0 0px"
                    });
                    $("#div_tooltip_common_"+id).show();
                    $("#move_"+id).mouseout(function(){
                        $("#div_tooltip_common_"+id).hide();
                    });
                    
            }
                            
        });
        
        $(".showViews").mouseover(function(){
                   
            var arr = $(this).attr('id').split("_");;
            var id = arr[1];
                    
            if('showViews'==arr[0])
            {
                var viewCount = $("#viewCount_"+id).val();
                var viewLikes = $("#viewLikes_"+id).val();
                var viewShares = $("#viewShare_"+id).val();
                var comment_count = $("#commentsCount_"+id).val();
                $("#viewToolTip"+id).show();
                $("#viewToolTip"+id).html('<div style="padding: 10px 0 0 15px;text-align: left;">'+viewCount+' Views</div><div style="padding: 2px 0 0 15px;text-align: left;">'+viewLikes+' Likes</div><div style="padding: 2px 0 0 15px;text-align: left;">'+viewShares+' Shares</div><div style="padding: 2px 0 0 15px;text-align: left;">'+comment_count+' Comments</div>');
                $(".div_tooltip").css({"margin":"160px 0 0 0px",'background':"url('../../../images/small_tooltip1.png') no-repeat  top left","width":"106px"});
                       
            }
            //                    else if('showLikes'==arr[0]){
            //                        var viewLikes = $("#viewLikes_"+id).val();
            //                        $("#viewToolTip"+id).show();
            //                        $("#viewToolTip"+id).html('<div style="padding: 10px 0 0 0px">'+viewLikes+' Likes</div>');
            //                        $(".div_tooltip").css("margin","140px 0 0 30px");
            //
            //                    }
            //                    else if('showShare'==arr[0]){
            //                         var viewShares = $("#viewShare_"+id).val();
            //                        $("#viewToolTip"+id).show();
            //                        $("#viewToolTip"+id).html('<div style="padding: 10px 0 0 0px">'+viewShares+' Shares</div>');
            //                        $(".div_tooltip").css("margin","140px 0 0 60px");
            //
            //                    }
            else if('showComments'==arr[0]){
                        
                var comment_count = $("#commentsCount_"+id).val();
                if(comment_count != 0){
                    $("#tooltip_comment"+id).show();
                    $("#tooltip_comment"+id).html('<div style="padding:10px;padding-top:20px;color: #fff;background:url(../../../images/tooltip_msg.png) no-repeat  top left;height:70px"><img src="../../../images/ajax-loader.gif"/>&nbsp;Loading...</div>');
                    $.post("../../../controller/music_all_controller.php?action=get_comment_summery",{ 'module':'image', 'itemId':id}, 
                    function(data){
                        $("#tooltip_comment"+id).html(data);
                    });
                    
                        
                }else{
                    $("#viewToolTip"+id).show();
                    $("#viewToolTip"+id).html('<div style="padding: 10px 0 0 3px;">no comments</div>');
                    $(".div_tooltip").css({'margin':'160px 0 0 25px','background':"url('../../../images/small_tooltip2.png') no-repeat  top left","width":"91px"});
                }
            }
                    
                    
                   
        })
        $(".showViews").mouseout(function(){
            var arr = $(this).attr('id').split("_");;
            var id = arr[1];
                    
            $("#viewToolTip"+id).hide();
                    

        })
        $(".showViews").mouseout(function(){
            var arr = $(this).attr('id').split("_");;
            var id = arr[1];
                    
            $("#tooltip_comment"+id).mouseover(function(){
                $("#tooltip_comment"+id).show();
                $(".viewAllComments").click(function(){
                    //alert("hi");
                    $("#comments_view_full").fadeIn(1000);

                    var title = $("#1_"+id).text();
                    var noOfComments = $("#commentsCount_"+id).val();
                            
                    $("#comments_view_full").html('<div style="float: left"><iframe src="../../common/view_comments.php?title='+title+'&itemId='+id+'&module=image&noOfComments='+noOfComments+'" width="520" height="500" frameborder="0" scrolling="no" ></iframe></div><div style="cursor: pointer;float: left;margin: -10px -10px 0 0 "><img src="../../../images/close.png" id="comment_closs"/></div>');
                    $("#comment_closs").click(function(){
                        $("#comments_view_full").hide();
                    });
                })
            })
            $("#tooltip_comment"+id).mouseout(function(){
                $("#tooltip_comment"+id).hide();
            })
            $("#tooltip_comment"+id).hide();
        })
               
                
                
        $(".items div").click(function(){
            var arr = $(this).attr('id').split("_");;
            var id = arr[1];
                    
            var test = $("#title_"+id).text();
            alert(test);
        })

    });
                
                       
                
        
            
</script>
