<?php

//get image module item analytic data

$ch = curl_init(app_key_values::$API_URL . "analytic/" . $_SESSION['app_id'] . "/image/getall");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$allItems = curl_exec($ch);
$allItems = json_decode($allItems);
$dataList = array();
if(isset($allItems->{"image"})){
foreach ($allItems->{"image"} as $value) {
    $dataList[$value->{"item_id"}] = array($value->{"like_count"}, $value->{"share_count"}, $value->{"comment_count"}, $value->{"view_count"});
}}
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
                    <div class="description <?php echo isset($flickrList) ? '' : 'edit' ?>" id="1_<?php echo $pic->id; ?>"><?php if (isset($pic->name)) {
                echo $pic->name;
            } ?></div>
                  
                    
                    <div class="icons">
                        <?php if (!isset($flickrList)) { ?>
                            <div class="iconLeft clickable" onclick="javascript: delete_confirm(<?php echo $pic->id; ?>);"></div>
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
                            <div class="iconRight clickable" onclick="javascript: moveItem(this);"></div>
                        <?php } else { ?>
                            <div class="<?php echo $pic->added ? 'addIconDis' : 'addIcon'; ?> clickable" onclick="javascript: addItemFlickr(this,<?php echo $pic->added ? 'true' : 'false'; ?>);" id="add_<?php echo $pic->id; ?>"></div>
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
                        "margin":"165px 0 0 9px",
                        "padding":"10px 0 0 0px"
                    });
                    $("#div_tooltip_common_"+id).show();
                    $("#zoom_"+id).mouseout(function(){
                        $("#div_tooltip_common_"+id).hide();
                    });
                    
              
            }
            else if("add"==arr[0]){
               
                    
                    $("#div_tooltip_common_"+id).text("Add");
                    $("#div_tooltip_common_"+id).css({
                        "margin":"165px 0 0 26px",
                        "padding":"10px 0 0 0px"
                    });
                    $("#div_tooltip_common_"+id).show();
                    $("#add_"+id).mouseout(function(){
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
                        $(".div_tooltip").css("margin","160px 0 0 0px");
                       
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
                            $("#tooltip_comment"+id).html('<div style="padding:10px;color: #747C7E;"><img src="../../../images/ajax-loader.gif"/>&nbsp;Loading...</div>');
                            $.post("../../../controller/music_all_controller.php?action=get_comment_summery",{ 'module':'image', 'itemId':id}, 
                            function(data){
                                $("#tooltip_comment"+id).html(data);
                            });
                    
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
                            $("#comments_view_full").show();
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
