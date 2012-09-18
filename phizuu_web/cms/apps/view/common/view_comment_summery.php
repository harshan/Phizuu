<?php
if(isset($comments->{'comments'})){
        $data = ' <div class="comment_div_main">';
        
        foreach($comments->{'comments'} as $value){
                    if($i<=5){
               if($i==1){ 
                $data.= '<div style="clear: both;height: 68px;background: url(../../../images/tooltip_comment_top.png) no-repeat  top left;">
                <div style="padding:15px 0 0 10px;"> 
                <div style="float: left"><img src="'.$value->{"image"}->{'uri'}.'" width="25" height="25"/></div>
                <div>
                <div style="float: left;padding-left: 3px">'.$value->{"user_name"}.'</div>
                <div style="float: right;padding-right: 5px">';
                         
            if ($value->{"user_type"} == 'FB') {
                $data.= "<img src='../../../images/fb_icon.png'/>";
            } else {
                $data.= "<img src='../../../images/twitter_icon.png'/>";
            }
                 $data.='</div>
                     </div>
                     <div style="clear: both;float:left;font-size:10px;color:#bcbcbc;margin:-10px 0 0 28px">'.substr($value->{"comment"}, 0, 60);
                   if(strlen($value->{"comment"})>60){
                       $data.='...';
                   }
                   $data.='</div></div></div>';
            $i++;
               }else{
                 $data.= '<div style="clear: both;height: 59px;background: url(../../../images/tooltip_comment_middel.png) no-repeat  top left;">
                 <div style="padding:5px 0 0 10px;"> 
                 <div style="float: left"><img src="'.$value->{"image"}->{'uri'}.'" width="25" height="25"/></div>
                 <div style="float: left;padding-left: 3px">'.$value->{"user_name"}.'</div>
                 <div style="float: right;padding-right: 5px">';
                         
            if ($value->{"user_type"} == 'FB') {
                $data.= "<img src='../../../images/fb_icon.png'/>";
            } else {
                $data.= "<img src='../../../images/twitter_icon.png'/>";
            }
                $data.=       '</div> 
                   <div style="clear: both;float:left;font-size:10px;color:#bcbcbc;margin:-10px 0 0 28px">'.substr($value->{"comment"}, 0, 60);
                   if(strlen($value->{"comment"})>60){
                       $data.='...';
                   }
                   $data.='</div></div></div>';
                   
                   
                    $i++;
               }
            }
            
        }
        
        $data.= ' <div style="clear: both;height: 22px;background: url(../../../images/tooltip_comment_bottom.png) no-repeat  top left;cursor: pointer;" class="viewAllComments"></div></div>';
        }else{
            $data = '<div style="padding:15px;color: #fff;font-family: arial;font-size:12px;height:70px;background: url(../../../images/tooltip_msg.png) no-repeat  top left;">Cannot retrieve data server is busy….. </div>';
        }
        echo $data;
?>
