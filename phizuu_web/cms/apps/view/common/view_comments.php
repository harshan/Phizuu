<link href="../../css/styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/jquery-1.3.2.min.js"></script>
<?php
require_once "../../config/app_key_values.php";
session_start();
require_once "../../config/app_key_values.php";
$title = $_REQUEST['title'];
$itemId = $_REQUEST['itemId'];
$module = $_REQUEST['module'];
$noOfComments = $_REQUEST['noOfComments'];

if (isset($_REQUEST['nextrecord'])) {
    $nextrecord = $_REQUEST['nextrecord'];
    $ch = curl_init(app_key_values::$API_URL . "client/" . $_SESSION['app_id'] . "/$module/$itemId/comment/$nextrecord");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $comments = curl_exec($ch);
    $comments = json_decode($comments);
    $nextrecordexist = $comments->{'next_message'};
} else {
    $ch = curl_init(app_key_values::$API_URL . "client/" . $_SESSION['app_id'] . "/$module/$itemId/comment");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $comments = curl_exec($ch);
    $comments = json_decode($comments);
    $nextrecordexist = $comments->{'next_message'};
}
?>
<link rel="stylesheet" href="../../js/scrollBar/website.css" type="text/css" media="screen"/>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="../../js/scrollBar/jquery.tinyscrollbar.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        
        $(".more_comments div").click(function(){
           
            var arr = $(this).attr('id').split("_");;
            var id = arr[3];
            if("view"==arr[0]){
                $("#sub_comments_"+id).css('display', 'block');
                $("#hide_sub_comments_"+id).css('display', 'block');
                $("#view_sub_comments_"+id).css('display', 'none');
                
                
            }
            if("hide"==arr[0]){
                $("#sub_comments_"+id).css('display', 'none');
                $("#view_sub_comments_"+id).css('display', 'block');
                $("#hide_sub_comments_"+id).css('display', 'none');
                
            }
            
        });
        
        $('#scrollbar1').tinyscrollbar({ size: 'auto'});
        
        
        $(".more_comments div").click(function(){
            
            var scrollbar1 = $('#scrollbar1');
            scrollbar1.tinyscrollbar();
            
            scrollbar1.tinyscrollbar_update({ size: 'auto'});
            
        });
        
    });
    
</script>	

<div id="scrollbar1">
    <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
    <div class="viewport">
        <div id="comment_page" style="font-family: arial; color: #cbcbcb;font-size: 11px;padding: 5px" class="overview">

            <div style="margin: 5px;color: #fff;font-size: 12px">
                <div style="float: left;">Item Name:</div>
                <div style="float: left">&nbsp;<?php echo $title; ?></div>
            </div>
            <?php
            foreach ($comments->{'comments'} as $value) {
                ?>
                <div style="width: 500px;min-height: 70px;clear: both;padding-top: 10px">
                    <div style="float: left">
                        <div style="float: left"><img src="<?php echo $value->{"image"}->{'uri'}; ?>" width="40" height="40"/></div>
                    </div>
                    <div style="float: left;width: 450px">
                        <div style="padding-left: 10px;float: left;color: #fff"><?php echo $value->{"user_name"} ?></div>
                        <div style="float: right;padding-right: 10px"><?php
            if ($value->{"user_type"} == 'FB') {
                echo "<img src='../../images/fb_icon.png'/>";
            } else {
                echo "<img src='../../images/twitter_icon.png'/>";
            }
                ?></div>
                        <div style="float: right;padding-right: 10px;color: #fff"><?php
                        $date = date_create($value->{"timestamp"});
                        echo date_format($date, 'd-m-Y H:i');
                ?></div>
                    </div>
                    <div style="float: left;width: 450px">
                        <div style="padding-left: 10px;float: left"><?php echo $value->{"comment"} ?></div>
                        <?php
                        if (isset($value->{"image_attachment"}->{"thumb_uri"})) {
                            ?>
                            <div style="padding-left: 10px;float: left"><img src="<?php echo $value->{"image_attachment"}->{"thumb_uri"} ?>"/></div>
                            <?php
                        }
                        ?>

                    </div>
                    <?php if ($value->{"reply_count"} > 0) { ?>
                        <div style="float: left;width: 500px" class="more_comments">
                            <div style="float: right" id="view_sub_comments_<?php echo $value->{"comment_id"} ?>"><img src="../../images/expand_tour.png" style="cursor: pointer" /></div>
                            <div style="float: right;display: none" id="hide_sub_comments_<?php echo $value->{"comment_id"} ?>"><img src="../../images/collapse_tour.png" style="cursor: pointer;" /></div>
                        </div>

                        <div id="sub_comments_<?php echo $value->{"comment_id"} ?>" style="display: none;min-height: 70px">
                            <?php
                            $commentItemId = $value->{"comment_id"};
                            $ch = curl_init(app_key_values::$API_URL . "client/" . $_SESSION['app_id'] . "/$module/$itemId/item/$commentItemId");
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            $subComments = curl_exec($ch);
                            $subComments = json_decode($subComments);

                            foreach ($subComments->{'comments'} as $value1) {
                                ?>

                                <div id="sub_comment_row" style="padding-left: 50px;clear: both;padding-bottom: 5px">
                                    <div style="width: 450;height: 2px;clear: both;margin-bottom: 5px;background-image: url('../../images/seperator_pxl.png')"></div> 
                                    <div style="float: left">
                                        <div style="float: left"><img src="<?php echo $value1->{"image"}->{'uri'}; ?>" width="40" height="40"/></div>
                                    </div>
                                    <div style="float: left;width: 400px">
                                        <div style="padding-left: 10px;float: left;color: #fff"><?php echo $value1->{"user_name"} ?></div>
                                        <div style="float: right;padding-right: 10px"><?php
                    if ($value1->{"user_type"} == 'FB') {
                        echo "<img src='../../images/fb_icon.png'/>";
                    } else {
                        echo "<img src='../../images/twitter_icon.png'/>";
                    }
                                ?></div>
                                        <div style="float: right;padding-right: 10px;color: #fff"><?php
                                $date = date_create($value1->{"timestamp"});
                                echo date_format($date, 'd-m-Y H:i');
                                ?></div>
                                        <div style="float: left;width: 400px;clear: both">
                                            <div style="padding-left: 10px;float: left"><?php echo $value1->{"comment"} ?></div>
                                            <?php
                                            if (isset($value->{"image_attachment"}->{"thumb_uri"})) {
                                                ?>
                                                <div style="padding-left: 10px;float: left"><img src="<?php echo $value->{"image_attachment"}->{"thumb_uri"} ?>"/></div>
                                            <?php }
                                            ?>
                                        </div>
                                    </div>


                                </div>

                                <?php
                            }
                            ?>


                        </div>
                        
                    </div>

                    <?php
                }?>
            <div style="width: 500;height: 2px;clear: both;margin-top: 5px;background-image: url('../../images/seperator_pxl.png')"></div> 
            <?php
            }
            ?>

        </div>
    </div>
    <div style="clear: both;">
        <?php
        if (isset($_REQUEST['next'])) {
            if ($_REQUEST['next'] == true) {
                echo '<div style="float:left;padding-right:10px;cursor: pointer" OnClick="history.go( -1 );return true;">' . 'Previous' . '</div>';
            }
        }


        if (isset($nextrecordexist)) {
            if($nextrecordexist != ""){
            echo '<div ><a href="view_comments.php?title=' . $title . '&itemId=' . $itemId . '&module=' . $module . '&noOfComments=' . $noOfComments . '&nextrecord=' . $comments->{'next_message'} . '&next=true" style="text-decoration: none;">' . 'Next' . '</a></div>';
        }
        
        }
        ?>
    </div>
</div>

